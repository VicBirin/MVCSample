<?php
// task endpoints
$router->get('/task-list', 'task@list');
$router->get('/task-list/:sort', 'task@list');
$router->get('/task-list/:sort', 'task@list');
$router->get('/task-list/:page/:perPage', 'task@list');
$router->get('/task-list/:page/:perPage/:sort', 'task@list');
$router->get('/task-get/:id', 'task@get');
$router->post('/task-add', 'task@add');
$router->post('/task-edit', 'task@edit');
$router->delete('/task/:id', 'task@delete');

$router->get('/user-get', 'user@get');
$router->post('/user-login', 'user@login');
$router->post('/user-logout', 'user@logout');

// default endpoint path
$router->get('/', function() {
    echo 'Welcome to the tasks management API';
});
