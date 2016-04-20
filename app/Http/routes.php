<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use App\User;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {
    /**
     * Show Task Dashboard
     */

   // });


Route::get('/', function () {
        return view('welcome');
    });

Route::get('setting', function () {
   return view('setting', [
            'users' => User::orderBy('name', 'desc')->get()
        ]);


    });
Route::get('home2', function () {
if(Auth::guest()){
return Redirect::to('auth/login');
}else{
echo 'Your Are Auth user' . Auth::user()->email . '.' ;
}   
 });


//Route::get('task', function () {
//if(Auth::guest()){
//return Redirect::to('auth/login');
//}else{
  //      return view('view');
//}
   // });


//Authenication Rules ... AuthController
Route::get('auth/login','Auth\AuthController@getLogin');
Route::post('auth/login','Auth\AuthController@postLogin');
Route::get('auth/logout','Auth\AuthController@logout');
//Registeration Routes 
Route::get('auth/register','Auth\AuthController@getRegister');
Route::post('auth/register','Auth\AuthController@postRegister');

    Route::get('/view', function () {
if(Auth::guest()){
return Redirect::to('auth/login');
}else{

        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'desc')->get()
        ]);
}
    });


    /**
     * Add New Task
     */
    Route::post('/task', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
	    'owner' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/view')
                ->withInput()
                ->withErrors($validator);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->owner = $request->owner;
        $task->save();

        return redirect('/view');
    });



    /**
     * Delete Task
     */
    Route::delete('/task/{id}', function ($id) {
        Task::findOrFail($id)->delete();

        return redirect('/view');
    });

Route::delete('/setting/{id}', function ($id) {
        User::findOrFail($id)->delete();

        return redirect('/setting');
    });

});

Route::auth();

Route::get('/home', 'HomeController@index');
