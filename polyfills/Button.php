<?
// @since 2.3
class Button {
    static function create($title, $name = '', $parameters = array())
    {
        if ($name) {
            $parameters['name'] = $name;
        }
        $parameters['class'] .= ' button';
        
        $tag  = '<button';
        foreach ($parameters as $attribute => $value) {
            $value = trim($value);
            $tag .= sprintf(' %s="%s"', $attribute, htmlspecialchars($value));
        }
        $tag .= '>' . htmlReady($title) . '</button>'; 
        return $tag;
    }
    
    static function createAccept($title, $name = '', $parameters = array())
    {
        $parameters['class'] .= ' accept';
        return self::create($title, $name, $parameters);
    }

    static function createCancel($title, $name = '', $parameters = array())
    {
        $parameters['class'] .= ' cancel';
        return self::create($title, $name, $parameters);
    }
}
