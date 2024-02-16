<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

include_once(__DIR__ . "/controllers/UserController.php");
include_once(__DIR__ . "/app.php");

$app = new App();

$app->prefix("")
    // ->get('/', "UserController@get_all_data")
    ->get('/', function () {
        echo "home";
    });

$app->prefix("/blog")
    ->get('/all', function () {
        echo "all blog";
    })
    ->get('/reads/{id}', "UserController@get_single_data")
    ->get('/read/{id}', function () {
        echo "read blog";
    });

$app->run();
