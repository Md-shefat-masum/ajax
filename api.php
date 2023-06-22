function get_data()
{
    if(!file_exists(public_path('d.json'))){
        file_put_contents('d.json',"[]");
    }
    $data = json_decode(file_get_contents(public_path('d.json')));
    $data = array_reverse($data);
    return $data;
}
Route::get('/user', function () {
    $data = get_data();
    if(request()->has('page')){
        $page = request()->page;
        $per_page = 10;
        $items = array_chunk($data, $per_page)[$page-1];
        $paginate = new \Illuminate\Pagination\LengthAwarePaginator($items,count($data),$per_page,$page, [
            "path" => "/api/user"
        ]);
        $paginate_html = $paginate->render();
        return response()->json([$paginate, $paginate_html]);
    }
    return response()->json($data);
});

Route::post('/user', function () {
    $validator = Validator::make(request()->all(), [
        'full_name' => ['required'],
        'email' => ['required'],
        'dob' => ['required'],
        'user_role' => ['required'],
        'gender' => ['required'],
        'courses' => ['required'],
        'description' => ['required'],
        'image' => ['required'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'err_message' => 'validation error',
            'data' => $validator->errors(),
        ], 422);
    }

    $data = get_data();
    $req_data['id'] = count($data)+1;
    $req_data = array_merge($req_data, request()->all());
    $data[] = $req_data;

    file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
    $data = array_reverse($data);
    return response()->json($data);
});

Route::put('/user/{id}', function ($id) {
    $validator = Validator::make(request()->all(), [
        'full_name' => ['required'],
        'email' => ['required'],
        'dob' => ['required'],
        'user_role' => ['required'],
        'gender' => ['required'],
        'courses' => ['required'],
        'description' => ['required'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'err_message' => 'validation error',
            'data' => $validator->errors(),
        ], 422);
    }
    $data = get_data();
    $index = array_search($id,array_column($data,'id'));
    $item = $data[$index];
    foreach (request()->all() as $key => $value) {
        $item->$key = $value;
    }
    $data[$index] = $item;
    file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
    return response()->json($item);
});

Route::delete('/user/{id}', function ($id) {
    $data = get_data();
    $index = array_search($id,array_column($data,'id'));
    unset($data[$index]);
    file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
    return response()->json(["$id deleted", $data]);
});