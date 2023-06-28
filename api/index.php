<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

include_once(__DIR__ . "/controllers/UserController.php");
include_once(__DIR__ . "/app.php");

$app = new App();

$app->prefix("api/user")
    ->get('/', "UserController@get_all_data")
    ->get('/{id}', "UserController@get_single_data")
    ->post('/', "UserController@store_data")
    ->post('/{id}', "UserController@update_data")
    ->delete('/{id}', "UserController@delete_data");

$app->run();
