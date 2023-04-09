<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\User\UserBookController;
 
;

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


//PUBLIC ENDPOINTS [

//Login-Register Process
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/books/search',[UserBookController::class,'search']);
//                 ]



//Reader
Route::group(['middleware' => ['auth:sanctum',]],function(){
     
    Route::get('/book',[BookController::class,'index']);
    Route::get('/book/{id}',[BookController::class,'show']);
    Route::get('/book/{books_id}/get',[UserBookController::class,'getBook'])->middleware('isPunished');
    Route::get('/book/{books_id}/left',[UserBookController::class,'leftBook'])->middleware('isPunished');
    Route::get('/hour',[UserBookController::class,'hour']);
 
//Authenticated User Info
    Route::get('/user/me/',function(Request $request){      
        $users = User::leftJoin('books', 'users.id', '=', 'books.user_id')
        ->select('users.*', DB::raw('GROUP_CONCAT(books.name) as books'))
        ->where('user_id',auth()->user()->id)
        ->first();
        if ($users) {
            $users->books = explode(',', $users->books);
           
        }
         return $users;
        });    
    });
    Route::get('/logout', [AuthController::class,'logout']);
 
  
    
//Worker
Route::group(['middleware' => ['auth:sanctum','isAdmin'],'prefix' => 'admin'],function(){
    Route::resource('/book', BookController::class);
    Route::resource('/user', AdminController::class);
    
});

