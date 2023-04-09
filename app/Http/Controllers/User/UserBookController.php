<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBookController extends Controller
{
    
    
    public function getBook($book_id){
        $now = Carbon::now();
        $user = User::findOrFail(auth()->user()->id);
        $book = Book::findOrFail($book_id);
        $borrowed_at = Carbon::parse($book->borrowed_at);
        $return_deadline = $borrowed_at->addSecond(15);
        if($user->is_punished == 1) {
            return response()->json(['message' => 'Hesabınız kalıcı olarak banlandı'], 403);
        }
            
         
        if(!$book){
            return response()->json(['message' => 'The book you are looking for is not found']);
        }
        $countBook = auth()->user()->book()->pluck('name')->toArray();
         
            if(count($countBook) > 2) {
                return  response()->json(['message' => 'You cannot take book more than 2'],403);
            }
           
             if($book && !$book->user_id  ){
               
                $book->user_id = auth()->user()->id;
                $book->borrowed_at = Carbon::now();
                $book->update();
                    return response()->json(['message' => 'success','data' => [$book->join('departs','departs.id','=','books.depart_id')
                                                                                ->select('books.*','departs.name as depart_name')
                                                                                ->where('books.id',$book_id)
                                                                                ->first()]]);
        }else {
             return response()->json(['message' => 'Failed! '.$book->name.' has already taken.'],403);
        }
 
            
        
    }


    public function leftBook($book_id){
       
        $book = Book::findOrFail($book_id);
        $borrowed_at = Carbon::parse($book->borrowed_at);
        $return_deadline = $borrowed_at->addSecond(15);
        $user = User::findOrFail(auth()->user()->id);
        
        if(Carbon::now() > $return_deadline){
            $book->borrowed_at = null;
            $book->user_id = null;
            $book->update();
            $user->is_punished = 1;
            $user->punished_time = Carbon::now()->addSecond(30);
            $user->update();
            return response()->json(['message' => 'Hesabınız '.Carbon::parse(auth()->user()->punished_time).' tarihine kadar yasaklandı','reason' => 'Kitap teslim tarihi aşıldı'],403);
         }elseif($book->user_id == auth()->user()->id){

            $book->user_id = null;
            $book->borrowed_at = null;
            $book->update();
             return response()->json(['message' => 'You has left the '.$book->name]);   
         }
        
       

       
      
        return response()->json(['message' => 'An error has occured when you left the book'],500);
    }

    

    public function search(Request $request) {
    $query = Book::query();

    if($request->has('q')){
        $query->where('books.name','LIKE','%'.$request->query('q').'%');
    }
    if($request->has('category')){
        $query->where('category', $request->query('category'));
    }
    if($request->has('author')){
        $query->where('author','LIKE','%'.$request->query('author').'%');
    }
    $result = $query->join('departs','departs.id','=','books.depart_id')
                    ->select('books.*','departs.name as depart_name')
                    ->get();
        return response()->json(['data' => $result]);
        
    }

  
}


// public function search(Request $request){
       
    //     $search = $request->query('with');
    //     $category = $request->query('category');
       
    //     $fields = $request->validate([
    //         'with' => 'array|nullable',
            
    //     ]);
         
    //     $searchResults = [];
    //     $finalResult =[];
    //     if ($fields['with'] != null){
    //        foreach ($search as $result){
           
    //          if($category != null){
    //             $book = Book::where('name','LIKE','%'.$result.'%')
    //                 ->where('category',$category)
    //                 ->get();
    //          }else {
    //             $book = Book::where('name','LIKE','%'.$result.'%')->get();
    //          }
    //          array_push($searchResults,$book);
    //          return $searchResults;
             
    //        }

    //     foreach($searchResults as $searchResult){

    //         foreach($searchResult as $item) {
    //             array_push($finalResult,$item);
    //         }
    //     }
    //      return response()->json(['data' => $finalResult]);
    //     }
        
       
    // }