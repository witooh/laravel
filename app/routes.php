<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::options('{uri}', function(){
        exit();
    })->where('uri', '[\S\s]*');

Route::post('auth', function(){

        if(Input::get('type') == 'session'){
            if(Auth::attempt(Input::get('credentials'))){
                JsonResponse::success(Auth::user()->toArray());
            }
        }elseif(Input::get('type') == 'http'){

            list($username, $password) = explode(':', base64_decode(substr(Request::header('Authorization'), 6)));

            $db = DB::table('user');
            foreach(Input::get('credentials') as $key=>$value){
                if($key != 'password'){
                    $db->where($key, $value);
                }
            }
            $user = $db->first();

            if(!is_null($user) && Hash::check($password, $user['password'])){
                Auth::basic(Input::get('by'));
                JsonResponse::success($user);
            }
        }

        JsonResponse::auth('Authenticate Failed');
    });

Route::get('checkauth', function(){
        if(Auth::check()){
            JsonResponse::success(Auth::user()->toArray());
        }
        JsonResponse::auth();
    });

Route::get('logout', function(){
        Auth::logout();
    });

Route::post('inlinevalidate', function(){

        $validator = Validators::get(Input::get('rule'));

        return Response::json($validator->inlineValidate(Input::get('field')));
    });