<?php
class UserController
{
    public $data = [];
    public $data_path = "";

    public function __construct() {
        $this->data_path = data_path("users.json");
        $this->data = get_data("/api/user","users.json");
    }

    public function get_all_data()
    {
        $data = $this->data;
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
}
