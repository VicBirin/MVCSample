<?php

/**
 *  Global config
 */

// Http Url
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_URL', '/'. substr_replace(trim($_SERVER['REQUEST_URI'], '/'), '', 0, strlen($scriptName)));

// Define system paths
define('SCRIPT', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/');
define('CORE', SCRIPT . 'Core/');
define('CONTROLLERS', SCRIPT . 'Controllers/');
define('MODELS', SCRIPT . 'Models/');

define('SORT_FIELDS', ['id', 'userName', 'email', 'completed']);

// Database settings
define('DATABASE', [
    'Port'   => '3306',
    'Host'   => '127.0.0.1',
    'Driver' => 'PDO',
    'Name'   => 'sample_mvc',
    'User'   => 'mvc',
    'Pass'   => 'mvc_123#'
]);

