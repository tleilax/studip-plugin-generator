<?
class Polyfill
{
    static $polyfills = array();
    static $versions  = array();
    
    static function define($definition_file)
    {
        if (!(file_exists($definition_file) and is_readable($definition_file))) {
            throw new Exception('Unable to load polyfill definition file');
        }
        
        self::$polyfills = json_decode(file_get_contents($definition_file), true);
        
        foreach (self::$polyfills as $name => $polyfill) {
            $version = $polyfill['since'];
            if (!isset(self::$versions[$version])) {
                self::$versions[$version] = array();
            }
            self::$versions[$version][] = $name;
        }
    }
    
    static function getVersions()
    {
        $result = array();

        foreach (self::$versions as $version => $polyfills) {
            $result[$version] = array();
            foreach ($polyfills as $polyfill) {
                $result[$version][$polyfill] = self::$polyfills[$polyfill];
            }
        }

        return $result;
    }

}