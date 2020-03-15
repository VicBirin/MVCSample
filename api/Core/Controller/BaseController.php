<?php

/**
 * Base controller class
 */
namespace Controller;

class BaseController {

    /**
     * Global Request class.
     */
    public $request;

    /**
     * Global Response class.
     */
    public $response;

	/**
	*  Constructor
	*/
    public function __construct() {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    /**
     * Creates and returns requested Model
     * @param string $model
     * @return object
     */
    public function model($model) {
        $file = MODELS . ucfirst($model) . '.php';

		// check exists file
        if (file_exists($file)) {
            require_once $file;

            $model = 'Models\\' . str_replace('/', '', ucwords($model, '/'));
			// check class exists
            if (class_exists($model))
                return new $model;
            else 
                throw new Exception(sprintf('{ %s } model class not found', $model));
        } else {
            throw new Exception(sprintf('{ %s } model file not found', $file));
        }
    }

    /**
     * Returns HTTP response
     * @param int $status
     * @param $msg
     */
    public function send($status = 200, $msg) {
        $this->response->setHeader(sprintf('HTTP/1.1 ' . $status . ' %s' , $this->response->getStatusCodeText($status)));
        $this->response->setContent($msg);
    }
}
