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
        
        $_REQUEST["data"] = $_POST;
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->path = $this->remove_slash_from_entry_end($this->path);
        $this->method = $_SERVER["REQUEST_METHOD"];
    }

    public function parse_multipart_content(?string $content, ?string $boundary): ?array
    {
        if (empty($content) || empty($boundary)) return null;
        $sections = array_map("trim", explode("--$boundary", $content));
        $parts = [];
        foreach ($sections as $section) {
            if ($section === "" || $section === "--") continue;
            $fields = explode("\r\n\r\n", $section);
            if (preg_match_all("/([a-z0-9-_]+)\s*:\s*([^\r\n]+)/iu", $fields[0] ?? "", $matches, PREG_SET_ORDER) === 2) {
                $headers = [];
                foreach ($matches as $match) $headers[$match[1]] = $match[2];
            } else $headers = null;
            $parts[] = ["headers" => $headers, "value"   => $fields[1] ?? null];
        }
        return empty($parts) ? null : $parts;
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


function paginate($array, $limit, $endpoint = "")
{
    // create an empty array for the pagination object
    $pagination = array();

    // calculate the current page number
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        // get the page parameter from the URL
        $page = (int) $_GET['page'];
    } else {
        // use 1 as the default page
        $page = 1;
    }

    // calculate the total number of pages
    $total_pages = ceil(count($array) / $limit);

    // calculate the offset for slicing the array
    $offset = ($page - 1) * $limit;

    // slice a subset of the array based on the offset and limit
    $data = array_slice($array, $offset, $limit);

    // add some properties to the pagination object
    $pagination['current_page'] = $page;
    $pagination['data'] = $data;
    $pagination['first_page_url'] = $endpoint . '?page=1';
    $pagination['from'] = $offset + 1;
    $pagination['last_page'] = $total_pages;
    $pagination['last_page_url'] = $endpoint . '?page=' . $total_pages;
    $pagination['next_page_url'] = ($page < $total_pages) ? $endpoint . '?page=' . ($page + 1) : null;
    $pagination['path'] = $endpoint;
    $pagination['per_page'] = $limit;
    $pagination['prev_page_url'] = ($page > 1) ? $endpoint . '?page=' . ($page - 1) : null;
    $pagination['to'] = min($offset + $limit, count($array));

    $pagination['total'] = count($array);

    // generate an array of links for the pagination object 
    $links = array();

    // add a link for the previous page if it exists 
    if ($pagination['prev_page_url']) {
        $links[] = array(
            'url' => $pagination['prev_page_url'],
            'label' => '« Previous',
            'active' => false
        );
    }

    // add a link for each page number 
    for ($i = 1; $i <= $total_pages; $i++) {
        $links[] = array(
            'url' => $endpoint . '?page=' . $i,
            'label' => (string)$i,
            'active' => ($i == $page)
        );
    }

    // add a link for the next page if it exists 
    if ($pagination['next_page_url']) {
        $links[] = array(
            'url' => $pagination['next_page_url'],
            'label' => 'Next »',
            'active' => false
        );
    }

    // add the links array to the pagination object 
    $pagination['links'] = $links;

    return $pagination;
}
