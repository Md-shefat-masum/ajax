function get_data()
{
if(!file_exists(public_path('d.json'))){
file_put_contents('d.json',"[]");
}
$data = json_decode(file_get_contents(public_path('d.json')));
return $data;
}
Route::get('/user', function () {
$data = get_data();
$data = array_reverse($data);
if(request()->has("search")){
$key = request()->search;
$data = array_filter($data, function($item) use($key){
return str_contains($item->full_name, $key);
});
}
if(request()->has('page')){
$page = request()->page;
$per_page = 10;
$items = array_chunk($data, $per_page)[$page-1];
$paginate = new \Illuminate\Pagination\LengthAwarePaginator($items,count($data),$per_page,$page, [
"path" => "/user"
]);
// $paginate_html = $paginate->render();
return response()->json($paginate);
}
return response()->json($data);
});

Route::get('/user/{id}', function ($id) {
$data = get_data();
$index = array_search($id,array_column($data,'id'));
return response()->json($data[$index]);
});

Route::post('/user', function () {
$validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
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
$req_data['id'] = count($data) ? $data[count($data)-1]->id+1 : 1;
$req_data = array_merge($req_data, request()->all());
if(request()->hasFile('image')){
$req_data['image'] = url("avatar.png");
}
if(gettype($req_data["courses"]) != "string"){
$req_data["courses"] = json_encode($req_data['courses']);
}
$data[] = $req_data;

file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
return response()->json($req_data);
});

Route::post('/user/{id}', function ($id) {
$validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
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
if(request()->hasFile('image')){
$data['image'] = url("avatar.png");
}
if(gettype($item->courses) != "string"){
$item->courses = json_encode($item->courses);
}
$data[$index] = $item;
file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
return response()->json($item);
});

Route::delete('/user/{id}', function ($id) {
$data = get_data();
$index = array_search($id,array_column($data,'id'));
array_splice($data,$index,1);
file_put_contents(public_path('d.json'), json_encode($data, JSON_PRETTY_PRINT));
return response()->json(["$id deleted", $data]);
});

