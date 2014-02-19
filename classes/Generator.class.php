<?php
class Generator implements ArrayAccess {

	const ENVIRONMENT_DIRECTORY = 'environments/';
	const TEMPLATE_BOOTSTRAP = 'stub-bootstrap';
	const TEMPLATE_MIGRATION = 'stub-migration';
	const TEMPLATE_PLUGIN = 'stub-plugin';
	const TMP_DIR = 'cache/';

	private $environments = array();
	private $tmp;
	private $variables = array();
	private $zip;

	public function __construct() {
	// Create zip file
		$this->tmp = getcwd().'/'.self::TMP_DIR.md5(uniqid('plugin-stub', true));
		$this->zip = new ZipArchive;
		if (true !== ($result = $this->zip->open($this->tmp, ZIPARCHIVE::CREATE))) {
			throw new GeneratorException('Could not create temp file "'.$tmp.'", error #'.$result);
		}
	}
	
	public function addEnvironment($environment) {
	// Environment file
		$environment_file  = self::ENVIRONMENT_DIRECTORY.$environment.'.php';
		$environment_class = ucfirst($environment).'Environment';

		if (!file_exists($environment_file)) {
			throw new GeneratorException('Decorator for environment "'.$environment.'" not found');
		}
		require $environment_file;

		if (!class_exists($environment_class)) {
			throw new GeneratorException('Environment file is invalid, class "'.$environment_class.'" not defined');
		}
		$this->environments[$environment] = new $environment_class;
	}

	public function setVariables(Array $variables) {
		$this->variables = $variables;
	}
	
	public function addFromString($name, $content) {
		if ($this->variables['tab']) {
			$content = str_replace("\t", str_repeat(' ', $this->variables['tab']), $content);
		}
		$this->zip->addFromString($name, $content);
	}

	public function addIcon($name, $content) {
		$this->variables['icon'] = $name;
		$this->zip->addFromString($name, $content);
	}

	public function createIcon($name, $content) {
		$this->variables['icon'] = $name;
		// #abb7ce light blue

		$source = imagecreatefromstring($content);
		$width = imagesx($source);
		$height = imagesy($source);

		$destination = imagecreatetruecolor($width * 4, $height);
		$alpha = imagecolorallocatealpha($destination, 0, 0, 0, 127);
		imagefilledrectangle($destination, 0, 0, $width * 4, $height, $alpha);
		imagealphablending($destination, false);
		imagesavealpha($destination, true);

		for ($y = 0; $y < $height; $y++) {
			for ($x = 0; $x < $width; $x++) {
				$pixel = imagecolorat($source, $x, $y);
				$blue = imagecolorallocatealpha($destination, 0xab, 0xb7, 0xce, $pixel >> 24 & 0x7f);
				for ($i = 0; $i < 4; $i++) {
					imagesetpixel($destination, $x + $i * $width, $y, $i % 2 ? $pixel : $blue);
				}
			}
		}
		imagedestroy($source);

		// Add stars
		$star = imagecreatefrompng($width > 16 ? 'assets/images/star_2x.png' : 'assets/images/star.png');
		$offs = $width > 16 ? 32 : 10;
		for ($y = 0; $y < $offs; $y++) {
			for ($x = 0; $x < $offs; $x++) {
				$pixel = imagecolorat($star, $x, $y);
				if (($pixel >> 24 & 0x7f) !== 127) {
					for ($i = 0; $i < 2; $i++) {
						$u = $width * (2 + $i) + $width - $offs + $x;
						imagesetpixel($destination, $u, $y, $pixel);
					}
				}
			}
		}
		imagedestroy($star);

		ob_start();
		imagepng($destination);
		$content = ob_get_clean();

		imagedestroy($destination);

		$this->zip->addFromString($name, $content);
	}

	public function addManifest() {
		$manifest = '';
		$manifest_file = 'plugin.manifest';

		$variables = array(
			'pluginname', 'pluginclassname', 'origin', 'version',
			'description', 'homepage', 'studipMinVersion', 'studipMaxVersion',
			'dbscheme', 'uninstalldbscheme', 'updateURL'
		);
		foreach ($variables as $key) {
			if (!empty($this[$key])) {
				$manifest .= sprintf("%s=%s\n", $key, $this[$key]);
			}
		}

		$this->addFromString($manifest_file, $manifest);
	}

	public function addPluginClass() {
		$pluginclass      = $this->render(self::TEMPLATE_PLUGIN, array('environments' => $this->environments));
		$pluginclass_file = $this['pluginclassname'].'.class.php';

		$this->addFromString($pluginclass_file, $pluginclass);
	}

	public function addBootstrap() {
		$bootstrap = $this->render(self::TEMPLATE_BOOTSTRAP, array('environments' => $this->environments));
		$this->addFromString('bootstrap.php', $bootstrap);		
	}

	public function addAssets($assets) {
		foreach ($assets as $key) {
			if (!empty($this[$key])) {
				$asset_file    = ltrim($this[$key], './');
				$asset_content = $this[$key.'_content'];
				$this->addFromString($asset_file, $asset_content);
			}
		}
	}

	public function addMigration() {
		if ($this['migration']) {
			$migration_class = implode(array_map('ucfirst', preg_split('/[^[:alnum:]]+/', $this['migration'])));
			$migration       = $this->render(self::TEMPLATE_MIGRATION, array('class' => $migration_class));
			$migration_file  = 'migrations/1_'.implode('_', preg_split('/[^[:alnum:]]+/', strtolower($this['migration']))).'.php';
			$this->addFromString($migration_file, $migration);
		}
	}

	public function addResources() {
		foreach (array_keys($this->environments) as $environment) {
			$zip = self::ENVIRONMENT_DIRECTORY.$environment.'.zip';
			if (!file_exists($zip)) {
				throw new EnvironmentException('Resource file "'.basename($zip).'" not found');
			}
			$resources = new ZipArchive;
			if (true !== ($result = $resources->open($zip))) {
				throw new EnvironmentException('Invalid resource file "'.basename($zip).'"');
			}
			for ($i=0; $i < $resources->numFiles; $i++) {
				$name     = $resources->getNameIndex($i);
				$resource = $resources->getFromIndex($i);
				$this->zip->addFromString($name, $resource);
			}
			$resources->close();
		}
	}

	public function close() {
		$this->zip->close();
	}

	public function output() {
		$filename = sprintf('%s-%s.zip', $this['pluginclassname'], $this['version']);

		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		readfile($this->tmp);
	}

	public function __destruct() {
		@unlink($this->tmp);
	}

## PRIVATE STUFF ##

	private function render($template, Array $variables = null) {
		return render($template, $this->variables + $variables);
	}

## ARRAY ACCESS ##

	public function offsetExists($offset) {
		return isset($this->variables[$offset]);
	}

	public function offsetGet($offset) {
		return $this->variables[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->variables[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->variables[$offset]);
	}
}

class GeneratorException extends Exception {}
