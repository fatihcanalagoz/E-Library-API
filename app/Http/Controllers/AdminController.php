<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        if($user){

            return response()->json($user, 200);
        } else {
            return   response()->json(['message' => 'Something went wrong'],500);
        }
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
            'name' => 'required|max:20|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|max:50',
            'type' => 'nullable'
        ]);
        
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
           
        ]);
        $user->type = $fields['type'];
        $user->save();
        if($user) {
            return response()->json(['message' => 'User has been created','User' => $user->first()->latest()->limit(1)->get()],201);
        }else {
            return response()->json(['message' => 'User has not been created'],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json($user, 200);
        }else {
            return response()->json(['message' => 'The user you are looking for is not found'],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
            'name' =>  'string|max:20',
            'type' => 'string|in:worker,reader',
            'is_punished' => 'int|in:0,1',
            
        ]);
         
        $user = User::where('id',$id)->first();
         
        if($user){
            $user->name = $fields['name'];;
            $user->type = $fields['type'];
            $user->is_punished = $fields['is_punished'];
            $user->update();
            return response()->json(['message' => 'The user has been updated','user' => $user]);
        }else{
            return response()->json(['message' => 'While user is updating,an error occurs','user' => $user],404);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id',$id)->first();
        if($user){
            User::destroy($id);
            return response()->json(['message' => 'The user has been deleted']);
        }
        return response()->json(['message' => 'User is not found'],404);
    }
}
