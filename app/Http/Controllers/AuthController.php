<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\User;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
$user = Auth::User();
        return response([
            'status' => 'success',
                'user'=>$user->id
        ])
        ->header('Authorization', $token);
    }
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);

        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }
    public function doremove(Request $request){

     $user_id= Auth::user()->id;
     \DB::table('prefered')->where(array('user_id'=>$user_id,'shop_id'=>$request->get('shop_id')))->update(['deleted'=>1]);         return response([
            'status' => 'success']);
    }

    public function shop(Request $request){

         $user_id= Auth::user()->id;
          $shop= \DB::select("select `shop`.`id`,`shop`.`name`, `shop`.`image`, `voteshop`.`vote`, TIMESTAMPDIFF(SECOND,now(),DATE_ADD(voteshop.date_vote, INTERVAL 2 HOUR)) as date_vote from `shop` left join `voteshop` on `voteshop`.`shop_id` = `shop`.`id` and `voteshop`.`user_id` ='".$user_id."' and `voteshop`.`date_vote` > SUBDATE( CURRENT_TIMESTAMP, INTERVAL 2 HOUR)  ORDER BY shop.`id` limit 10 offset 0  ");
       
        return $shop;
     
    }
public function like(Request $request){
  
     $user_id= Auth::user()->id;
        \DB::table('voteshop')->insert(array('shop_id'=>$request->get('shop_id'),'user_id'=>$user_id,'vote'=>1,'date_vote'=>date('ymdHis')));
    $prefered=  \DB::table('prefered')->where(array('user_id'=>$user_id,'shop_id'=>$request->get('shop_id')))->get();
if(count($prefered)==0){

        \DB::table('prefered')->insert(array('shop_id'=>$request->get('shop_id'),'user_id'=>$user_id,'deleted'=>0));}
        return response([
            'status' => 'success']);
}
     
    public function unlike(Request $request){

         $user_id= Auth::user()->id;
        \DB::table('voteshop')->insert(array('shop_id'=>$request->get('shop_id'),'user_id'=>$user_id,'vote'=>0,'date_vote'=>date('ymdHis')));
      $prefered=  \DB::table('prefered')->where(array('user_id'=>$user_id,'shop_id'=>$request->get('shop_id')))->get();
if(count($prefered)>0){

  \DB::table('prefered')->where(array('user_id'=>$user_id,'shop_id'=>$request->get('shop_id')))->update(['deleted'=>1]);
}
        return response([
            'status' => 'success']);
    }
     public function preferred(Request $request)
    
    {
         $user_id= Auth::user()->id;

$shop= \DB::table('shop')->join('prefered','prefered.shop_id','shop.id')->where(array('prefered.user_id'=>$user_id,'prefered.deleted'=>0))->select('shop.id','shop.name','shop.image')->paginate(10);
        $shop->setPath('api/preferred');
return $shop;
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

}
