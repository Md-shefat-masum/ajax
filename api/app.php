<?php
class App
{
    protected $request;
    protected $path;
    protected $routes = [];
    protected $method;
    protected $prefix = "";

    public function __construct()
    {
        $this->request = json_decode(file_get_contents('php://input'));
        $_REQUEST["data"] = $this->request;
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->path = $this->remove_slash_from_entry_end($this->path);
        $this->method = $_SERVER["REQUEST_METHOD"];
    }

    public function remove_slash_from_entry_end($path)
    {
        if ($path[0] == "/") {
            $path = substr($path, 1, strlen($path));
        }
        if (substr($path, -1) == "/") {
            $path = substr_replace($path, "", -1);
        }
        return $path;
    }

    public function add_route($path, $method, $callback)
    {
        $router = [
            "path" => $this->remove_slash_from_entry_end($this->prefix . $path),
            "method" => $method,
            "callback" => $callback,
            "params" => [],
        ];
        if (strpos($path, "{")) {
            foreach (explode('/', substr($path, 1, strlen($path) - 1)) as $key => $s_path) {
                if (preg_match("/\{.*?\}/", $s_path)) {
                    array_push($router["params"], (object)[
                        "name" => $s_path,
                        "value" => null,
                        "position" => $key + 2,
                    ]);
                }
            }
        }
        array_push($this->routes, (object)$router);
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function get($path, $callback)
    {
        $this->add_route($path, "GET", $callback);
        return $this;
    }

    public function post($path, $callback)
    {
        $this->add_route($path, "POST", $callback);
        return $this;
    }

    public function put($path, $callback)
    {
        $this->add_route($path, "PUT", $callback);
        return $this;
    }

    public function delete($path, $callback)
    {
        $this->add_route($path, "DELETE", $callback);
        return $this;
    }

    public function run()
    {
        $path = explode('/', $this->path);
        $path_count = count($path);

        foreach ($this->routes as $route_index => $route) {
            $item_path = explode('/', $route->path);
            $item_path_count = count(explode('/', $route->path));

            if (
                ($path_count == $item_path_count) &&
                ($route->method == $this->method)
            ) {
                $param_values = [];
                foreach ($route->params as $param) {
                    $position = $param->position;
                    if (isset($path[$position])) {
                        $url_param_position_value = $path[$position];
                        $item_path[$position] = $url_param_position_value;
                        $param->value = $url_param_position_value;
                        $param_values[] = $url_param_position_value;
                    }
                }

                // echo "<br>";
                // echo "<br>";
                // print_r(strcmp(implode('/',$item_path), implode('/',$path)));
                // echo "<br>";
                // print_r(implode('/',$item_path));
                // echo "<br>";
                // print_r(implode('/',$path));
                // echo "<br>";
                // print_r($item_path);
                // echo "<br>";
                // print_r($path);
                // echo "<br>";

                if (implode('/', $item_path) == implode('/', $path)) {
                    call_user_func($this->routes[$route_index]->callback, ...$param_values);
                }
            }
            // print_r($this->routes);
        }
    }
}
