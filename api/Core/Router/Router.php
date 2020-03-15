<?php

namespace Router;

use Exception;

/**
 * Handles routing
 */
class Router {

    /**
     * Routes list.
     * @var array
     */
    private $router = [];

    /**
     * Match routes list.
     * @var array
     */
    private $matchRouter = [];

    /**
     * Request url.
     * @var string
     */
    private $url;

    /**
     * Request http method.
     * @var string
     */
    private $method;

    /**
     * Params list for route pattern
     * @var array
     */
    private $params = [];

    /**
     *  Response Class
     */
    private $response;

    /**
     *  Constructor
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method) {
        $this->url = rtrim($url, '/');
        $this->method = $method;

        // get response class of $GLOBALS var
        $this->response = $GLOBALS['response'];
    }

    /**
     *  Set GET request http method for current route
     * @param $pattern
     * @param $callback
     * @throws Exception
     */
    public function get($pattern, $callback) {
        $this->addRoute("GET", $pattern, $callback);
    }

    /**
     *  Set POST request http method for route
     * @param $pattern
     * @param $callback
     * @throws Exception
     */
    public function post($pattern, $callback) {
        $this->addRoute('POST', $pattern, $callback);
    }

    /**
     *  Set PUT request http method for route
     * @param $pattern
     * @param $callback
     * @throws Exception
     */
    public function put($pattern, $callback) {
        $this->addRoute('PUT', $pattern, $callback);
    }

    /**
     *  Set DELETE request http method for route
     * @param $pattern
     * @param $callback
     * @throws Exception
     */
    public function delete($pattern, $callback) {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    /**
     *  Add route object into router var
     * @param $method
     * @param $pattern
     * @param $callback
     * @throws Exception
     */
    public function addRoute($method, $pattern, $callback) {
        array_push($this->router, new Route($method, $pattern, $callback));
    }

    /**
     *  Filter requests by HTTP method
     */
    private function getMatchRoutersByRequestMethod() {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod())
                array_push($this->matchRouter, $value);
        }
    }

    /**
     * Filter route patterns by request url
     * @param $pattern
     */
    private function getMatchRoutersByPattern($pattern) {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            if ($this->dispatch(cleanUrl($this->url), $value->getPattern()))
                array_push($this->matchRouter, $value);
        }
    }

    /**
     *  Dispatch url and pattern
     * @param $url
     * @param $pattern
     * @return bool
     */
    public function dispatch($url, $pattern) {
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/' ) {
	        $patternAsRegex = $patternAsRegex . '?';
	    }
        $patternAsRegex = '@^' . $patternAsRegex . '$@';
        
        // check match request url
        if (preg_match($patternAsRegex, $url, $paramsValue)) {
            array_shift($paramsValue);
            foreach ($params[0] as $key => $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->setParams($val, urldecode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    /**
     *  Get router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * Set param
     * @param $key
     * @param $value
     */
    private function setParams($key, $value) {
        $this->params[$key] = $value;
    }

    /**
     * Convert Pattern To Regex
     * @param $matches
     * @return string
     */
    private function convertPatternToRegex($matches) {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    /**
     *  Run application
     * @throws Exception
     */
    public function run() {
        if (!is_array($this->router) || empty($this->router)) 
            throw new Exception('NON-Object Route Set');

        $this->getMatchRoutersByRequestMethod();
        $this->getMatchRoutersByPattern($this->matchRouter);

        if (!$this->matchRouter || empty($this->matchRouter)) {
			$this->sendNotFound();        
		} else {
            // call to callback method
            if (is_callable($this->matchRouter[0]->getCallback()))
                call_user_func($this->matchRouter[0]->getCallback(), $this->params);
            else
                $this->runController($this->matchRouter[0]->getCallback(), $this->params);
        }
    }

    /**
     * Run as controller
     * @param $controller
     * @param $params
     * @return mixed
     */
    private function runController($controller, $params) {$parts = explode('@', $controller);
        $file = CONTROLLERS . ucfirst($parts[0]) . '.php';

        if (file_exists($file)) {
            require_once($file);

            // controller class
            $controller = 'Controllers\\' . ucfirst($parts[0]);

            if (class_exists($controller))
                $controller = new $controller();
            else
				$this->sendNotFound();

            // set function in controller
            if (isset($parts[1])) {
                $method = $parts[1];
				
                if (!method_exists($controller, $method))
                    $this->sendNotFound();
				
            } else {
                $method = 'index';
            }

            // call to controller
            if (is_callable([$controller, $method]))
                return call_user_func([$controller, $method], $params);
            else
				$this->sendNotFound();
        }
        $this->sendNotFound();
    }
	
	private function sendNotFound() {
		$this->response->sendStatus(404);
		$this->response->setContent(['error' => 'Sorry This Route Not Found !', 'status_code' => 404]);
	}
}
