<?php
	if (get_magic_quotes_gpc() or get_magic_quotes_runtime()) {
		function stripslashes_deep($value) {
			return is_array($value)
				? array_map('stripslashes_deep', $value)
				: stripslashes($value);
		}
		$_COOKIE = stripslashes_deep($_COOKIE);
		$_REQUEST = stripslashes_deep($_REQUEST);
	}

	function html_trim_whitespace($string) {
		$MARKER = '{FIXED:'.md5(uniqid('fixed', true)).':FIXED}';

		preg_match_all('/<(script|pre|textarea)[^>]*?>.*?<\/\\1>/is', $string, $matches);
		$string = str_replace($matches[0], $MARKER, $string);

		$string = preg_replace('/(?:^\s+|\s+$)/m', '', $string);

		$parts = explode($MARKER, $string);
		$result = array_shift($parts);
		foreach ($parts as $part) {
			$result .= array_shift($matches[0]).$part;
		}

		return $result;
	}

	function array_collect($array, $method) {
		$arguments = array_slice(func_get_args(), 2);
		$result = array();
		foreach ($array as $item) {
			$result[] = call_user_func_array(array($item, $method), $arguments);
		}
		return array_filter($result);
	}

	function request($index, $default = '') {
		if (!array_key_exists($index, $_REQUEST)) {
			return $default;
		}
		return $_REQUEST[$index];
	}

	function populate($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$_REQUEST[$key] = request($key, $value);
			}
		}
	}

	function redirect($to) {
		header('Location: '.$to);
		die;
	}

	function output_if_equal($key, $value, $default, $text) {
		$val  = $key;//request($key, $default);
		$test = is_array($val) ? in_array($value, $val) : $value === $val;
		return $test ? $text : '';
	}
	function checked($index, $value, $default = '') {
		return output_if_equal($index, $value, $default, 'checked');
	}
	function selected($index, $value, $default = '') {
		return output_if_equal($index, $value, $default, 'selected');
	}

	function rglob($pattern, $dir = '', $omit_dir = false, $root = '') {
		if (empty($root)) {
			$root = $dir;
		}

		$temp = glob($dir.$pattern);

		$files = array();
		foreach ($temp as $t) {
			if (is_dir($t)) {
				$files = array_merge($files, rglob($pattern, $t.'/', $omit_dir, $root));
			} else {
				$files[] = $omit_dir ? str_replace($root, '', $t) : $t;
			}
		}
		return $files;
	}

	/**
	 * Renders a template from the templates-subfolders with an additional layout
	 *
	 * Templates have to be stored in a subdir called "templates".
	 * Absolutely nothing is validated so you better do that first.
	 *
	 * Have in mind that variables are passed from template to layout and the
	 * variables from the function's signature cannot be used in a template
	 * because they would break the function by overwriting vital information.
	 *
	 * Example usage:
	 * <samp>
	 *   $section = render('section', $variables = array(
	 *     'title'      => 'Section #1',
	 *     'content'    => 'Sample content',
	 *     'page_title' => 'Example page',
	 *   ));
	 *   $html = render('section', $variables, 'layout');
	 * </samp>
	 * with "templates/section.php":
	 * <code>
	 *   <h1><?= $section ?></h1>
	 *   <p><?= $content ?></p>
	 * </code>
	 * and "templates/layout.php":
	 * <code>
	 *   <html>
	 *     <head><title><?= $title ?></title></head>
	 *     <body><?= $CONTENT ?></body>
	 *   </html>
	 * </code>
	 *
	 * @param string $_template  the template to render (note: no extension)
	 * @param array  $_variables the variables to be substituted
	 * @param mixed  $_layout    an additional layout template
	 * @param string $_content   name of content variable used in layout
	 *
	 * @return string the rendered template
	 */
	function render($_template, $_variables = array(), $_layout = false, $_content = null, $_filter = '') {
		if ($_content === null) {
			$_content = 'CONTENT';
		}

		ob_start();
		extract($_variables);
		include 'templates/'.$_template.'.php';
		$$_content = ob_get_clean();

		if ($_layout) {
			ob_start();
			include 'templates/'.$_layout.'.php';
			$$_content = ob_get_clean();
		}

		if ($_filter and is_callable($_filter)) {
			$$_content = $_filter($$_content);
		}

		return $$_content;
	}
