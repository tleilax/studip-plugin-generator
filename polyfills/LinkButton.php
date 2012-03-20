<?
// @since 2.3
class LinkButton
{
    static function create($title, $url = '', $parameters = array())
    {
        $parameters['href'] = $url;
        $parameters['class'] .= ' button';
        
        $tag  = '<a';
        foreach ($parameters as $attribute => $value) {
            $value = trim($value);
            $tag .= sprintf(' %s="%s"', $attribute, htmlspecialchars($value));
        }
        $tag .= '>' . htmlReady($title) . '</a>'; 
        return $tag;
    }

    static function createAccept($title, $url = '', $parameters = array())
    {
        $parameters['class'] .= ' accept';
        return self::create($title, $url, $parameters);
    }

    static function createCancel($title, $url = '', $parameters = array())
    {
        $parameters['class'] .= ' cancel';
        return self::create($title, $url, $parameters);
    }
}