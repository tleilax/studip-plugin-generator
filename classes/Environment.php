<?
/**
 * 
 **/
abstract class Environment
{
    protected $generator;
    
    function __construct($generator)
    {
        $this->generator = $generator;
    }
    
    function constructor()
    {
        return '';
    }
    
    function initialize()
    {
        return '';
    }
    
    function plugin()
    {
        return '';
    }
    
    function assets()
    {
        return array();
    }
}
