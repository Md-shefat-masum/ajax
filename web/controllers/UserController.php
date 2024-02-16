<?php
class UserController
{
    public $data = [];
    public $data_path = "";
    public $api_end_point = "";

    public function __construct()
    {
        $this->data_path = data_path("users.json");
        $this->data = get_data("users.json");
        $this->api_end_point = "";
    }

    public function get_all_data()
    {
        $data = $this->data;
        if (isset($_GET["search"]) && !empty($_GET["search"])) {
            $key = $_GET["search"];
            $data = array_filter($data, function ($item) use ($key) {
                return count(explode($key, $item->full_name)) > 1 ;
            });
        }
        if (isset($_GET["page"])) {
            $data = paginate($data, 10, $this->api_end_point);
        }
        $data = array_reverse($data);
        return json($data);
    }

    public function get_single_data($id)
    {
        $data = $this->data;
        $index = array_search($id, array_column($data, 'id'));
        if ($index >= 0) {
            return json($data[$index]);
        } else {
            echo "404 not found $id $index";
        }
    }

    public function store_data()
    {   
        $errors = $this->validate($_REQUEST["data"], [
            'full_name' => ['required'],
            'email' => ['required'],
            'dob' => ['required'],
            'user_role' => ['required'],
            'gender' => ['required'],
            'courses' => ['required'],
            'description' => ['required'],
        ]);

        if ($errors && count($errors)) {
            header("HTTP/1.1 422 Unprocessable Content");
            echo json_encode(['err_message' => 'The given data was invalid.', 'data' => $errors], 422);
            exit;
        }

        $data = $this->data;
        $req_data['id'] = count($data) ? $data[count($data) - 1]->id + 1 : 1;
        $req_data = array_merge($req_data, (array) $_REQUEST["data"]);

        if (gettype($req_data["courses"]) != "string") {
            $req_data["courses"] = json_encode($req_data['courses']);
        }

        $req_data["image"] = image_upload("pp_", $req_data["id"]);

        $data[] = $req_data;
        store($this->data_path, $data);
        return json($req_data);
    }

    public function update_data($id)
    {
        $data = $this->data;
        $index = array_search($id, array_column($data, 'id'));
        $item = $data[$index];
        foreach ((array) $_REQUEST['data'] as $key => $value) {
            $item->$key = $value;
        }

        if (gettype($item->courses) != "string") {
            $item->courses = json_encode($item->courses);
        }

        $item->image = image_upload("pp_", $item->id, $item->image);

        $data[$index] = $item;
        store($this->data_path, $data);
        return json($item);
    }

    public function delete_data($id)
    {
        $data = $this->data;
        $index = array_search($id, array_column($data, 'id'));
        array_splice($data, $index, 1);

        store($this->data_path, $data);
        return json($data);
    }

    public function validate($request, $fields)
    {
        $request = (object) $request;
        // Create an empty array to store the errors
        $errors = [];

        // Loop through the fields and check if they are present in the request
        foreach ($fields as $field => $actions) {
            if (!isset($request->$field) || empty($request->$field)) {
                // Add an error message to the array
                $errors[$field] = ["The $field field is required."];
            }
        }

        // Check if the errors array is not empty
        if (!empty($errors)) {
            // Return a response with status code 422 and the errors array
            return $errors;
        }

        // Return null if there are no errors
        return null;
    }
}
