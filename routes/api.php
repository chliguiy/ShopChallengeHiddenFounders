<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'AuthController@register');
Route::any('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
	Route::get('auth/user', 'AuthController@user');
	Route::post('auth/logout', 'AuthController@logout');
	
Route::any('/shop_preferred','AuthController@preferred');

Route::any('/doremove','AuthController@doremove');

Route::any('/like','AuthController@like');


Route::any('/shop','AuthController@shop');

Route::any('/unlike','AuthController@unlike');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
	Route::get('auth/refresh', 'AuthController@refresh');
});

Route::post('/upload', function (Request $request) {

  
        $imageData = $request->get('image');
        $fileName = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        \Image::make($request->get('image'))->save(public_path('images/').$fileName);
        \DB::table('shop')->insert(array('name'=>$request->get('name'),'image'=>public_path('images/').$fileName));
        return response()->json(['success'=>true]);
 
});

Route::any('test',function(){
	$user_id=1;
 $shop= \DB::select("select `shop`.`name`, `shop`.`image`, `voteshop`.`vote`, TIMESTAMPDIFF(SECOND,voteshop.date_vote,now()) as date_vote from `shop` left join `voteshop` on `voteshop`.`shop_id` = `shop`.`id` and `voteshop`.`user_id` ='".$user_id."' and `voteshop`.`date_vote` >= SUBDATE( CURRENT_TIMESTAMP, INTERVAL 2 HOUR) limit 10 offset 0");
 return $shop;
});



