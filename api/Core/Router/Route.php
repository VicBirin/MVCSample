<?php

namespace Router;

use Exception;

/**
 * Class Route For Save Route
 */
final class Route {
    
    /**
     *  Http Method.
     * 
     * @var string 
     */
    private $method;

    /**
     *  The path for this route.
     * 
     *  @var string 
     */
    private $pattern;

    /**
     * The action, controller, callable. this route points to.
     * 
     * @var mixed
     */
    private $callback;

    /**
     *  Allows these HTTP methods.
     *
     *  @var array
     */
    private $list_method = ['GET', 'POST', 'PUT', 'DELETE', 'OPTION'];

    /**
     *  Constructor
     * @param String $method
     * @param String $pattern
     * @param $callback
     * @throws Exception
     */
    public function __construct(String $method, String $pattern, $callback) {
        $this->method = $this->validateMethod(strtoupper($method));
        $this->pattern = cleanUrl($pattern);
        $this->callback = $callback;
    }

    /**
     *  Check allowed HTTP method
     * @param string $method
     * @return string
     * @throws Exception
     */
    private function validateMethod(string $method) {
        if (in_array(strtoupper($method), $this->list_method)) 
            return $method;
        
        throw new Exception('Invalid Method Name');
    }

    /**
     *  Get method
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     *  Get pattern
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     *  Get callback
     */
    public function getCallback() {
        return $this->callback;
    }
}
