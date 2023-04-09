<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
         return response()->json(['data' => $books], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:50',
            'author' => 'required|string|max:20',
            'publisher' => 'required|string',
            'category' => 'required|string',
            'page' => 'required|int',
            'isbn' => 'required|int',
            'depart_id' => 'required|int',
            

        ]);

        $books = Book::create([
            'name' => $fields['name'],
            'author' => $fields['author'],
            'publisher' => $fields['publisher'],
            'category' => $fields['category'],
            'page' => $fields['page'],
            'isbn' => $fields['isbn'],
            'depart_id' => $fields['depart_id'],
            
         
        ]);

         return response()->json(['data' => [collect($books)->except(['created_at','updated_at'])]],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $books = Book::join('departs','departs.id','=','books.depart_id')
            ->select('books.*',DB::raw('GROUP_CONCAT(departs.name) as depart_name'))
            ->where('books.id',$id)->first();
        
            
                return $books;
                // return response()->json(['data' => [collect($books)->except(['created_at','updated_at'])]], 200 );
           
 
       
        return response()->json(['data' => ['message' => 'Book is not found']],404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:50',
            'author' => 'required|string|max:20',
            'publisher' => 'required|string',
            'category' => 'required|string',
            'page' => 'required|int',
            'isbn' => 'required|int',
            'depart_id' => 'int|required',
            'user_id' => 'int|max:4|nullable'
            
        ]);
        $now = Carbon::now();
        $books = Book::where('id',$id)->first();
        if($books){
            $books->borrowed_at = $now;
            $books->update([
                'name' => $fields['name'],
                'author' => $fields['author'],
                'publisher' => $fields['publisher'],
                'category' => $fields['category'],
                'page' => $fields['page'],
                'isbn' => $fields['isbn'],
                'depart_id' => $fields['depart_id'],
                'user_id' => $fields['user_id'],
                
            ]);
            if($books->user_id == null){
                $books->borrowed_at = null;
                $books->update();
            }
            return  response()->json(['data' => ['message' => 'Book is updated','book' => $books]]);
        }
        return response()->json(['data' => ['message' => 'Book is not found']],404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $books = Book::where('id',$id)->first();
        if($books){
            Book::destroy($id);
            return ['message' => 'Book is deleted'];
        }
          return response()->json(['data' => ['message' => 'Book is not found']],404);
    }
}
