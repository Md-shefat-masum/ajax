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
    if ($_GET["page"]) {
        $data = paginate($data, 10, "/api/user");
    }
    return $data;
}

function PUT(string $name)
{
    /* PUT data comes in on the stdin stream */
    $putdata = fopen("php://input", "r");

    /* Open a file for writing */
    $fp = fopen("myputfile.txt", "w");

    /* Read the data 1 KB at a time
   and write to the file */
    $liness = [];
    while (($line = fgets($putdata)) !== false) {
        if (strpos($line, '------WebKitFormBoundary') !== 0 && $line != "\r\n") {
            if(strpos($line, 'name')){
                $liness[substr($line, 38, -3)] = "";
            }else{
                $liness[] = $line; //lines
            }
        }
    }
    echo print_r($liness);
    exit;

    while ($data = fread($putdata, 1024)) {
        fwrite($fp, $data);
    }

    /* Close the streams */
    fclose($fp);
    fclose($putdata);

    $lines = file('php://input');
    $keyLinePrefix = 'Content-Disposition: form-data; name="';

    $PUT = [];
    $findLineNum = null;
    $names = [];
    $real_lines = [];


    foreach ($lines as $num => $line) {

        if (strpos($line, '------WebKitFormBoundary') !== 0 && $line != "\r\n") {
            $real_lines[] = $line;
        }

        if (strpos($line, $keyLinePrefix) !== false) {
            $key_name = substr($line, 38, -3);
            $names[$key_name] = "";

            if ($findLineNum) {
                // break;
            }
            if ($name !== substr($line, 38, -3)) {
                continue;
            }
            $findLineNum = $num;
        } else if ($findLineNum) {
            $PUT[] = $line;
        }
    }

    // $names = array_unique($names);
    $values = [];
    foreach ($names as $name) {
        $values[$name] = mb_substr(implode('', $name), 0, -2, 'UTF-8');
    }

    array_shift($PUT);
    array_pop($PUT);

    echo json_encode([$real_lines, $names, $values, $lines]);
    exit;

    return mb_substr(implode('', $PUT), 0, -2, 'UTF-8');
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

        echo json_encode([PUT("email")]);
        exit;

        if (gettype($req_data["courses"]) != "string") {
            $req_data["courses"] = json_encode($req_data['courses']);
        }
        $data[] = $req_data;

        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($req_data, JSON_PRETTY_PRINT);
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

        echo json_encode(["data" => PUT("email")]);
        exit;

        foreach ((array) $_REQUEST['data'] as $key => $value) {
            $item->$key = $value;
        }
        if (gettype($item->courses) != "string") {
            $item->courses = json_encode($item->courses);
        }
        $data[$index] = $item;
        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($item, JSON_PRETTY_PRINT);
    })

    ->delete('/{id}', function ($id) {
        $data = get_data();
        $index = array_search($id, array_column($data, 'id'));
        array_splice($data, $index, 1);
        file_put_contents(__DIR__ . '/d.json', json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode($data, JSON_PRETTY_PRINT);
    })

    ->run();
