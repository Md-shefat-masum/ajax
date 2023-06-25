<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json; charset=utf-8');

include_once(__DIR__ . "/app.php");
$app = new App();

function get_data()
{
    if (!file_exists(__DIR__ . "/d.json")) {
        file_put_contents(__DIR__ . "/d.json", "[]");
    }
    $data = json_decode(file_get_contents(__DIR__ . "/d.json"));
    return $data;
}

$app->prefix("api/user")

    ->get('/', function () {
        $data = get_data();
        $data = array_reverse($data);
        echo json_encode($data);
    })

    ->post('/', function () {
        $data = get_data();
        $req_data['id'] = count($data) ? $data[count($data) - 1]->id + 1 : 1;
        $req_data = array_merge($req_data, (array) $_REQUEST["data"]);

        if (gettype($req_data["courses"]) != "string") {
            $req_data["courses"] = json_encode($req_data['courses']);
        }
        $data[] = $req_data;

        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($data);
    })

    ->get('/{id}', function ($id) {
        $data = get_data();
        $index = array_search($id, array_column($data, 'id'));
        if ($index >= 0) {
            echo json_encode($data[$index], JSON_PRETTY_PRINT);
        } else {
            echo "404 not found $id $index";
        }
        exit;
    })

    ->put('/{id}', function ($id) {
        $data = get_data();
        $index = array_search($id, array_column($data, 'id'));
        $item = $data[$index];
        foreach ((array) $_REQUEST['data'] as $key => $value) {
            $item->$key = $value;
        }
        if (gettype($item->courses) != "string") {
            $item->courses = json_encode($item->courses);
        }
        $data[$index] = $item;
        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($data , JSON_PRETTY_PRINT);
    })

    ->delete('/{id}', function ($id) {
        $data = get_data();
        $index = array_search($id, array_column($data, 'id'));
        array_splice($data, $index, 1);
        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($data , JSON_PRETTY_PRINT);
    })

    ->run();
