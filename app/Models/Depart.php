<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depart extends Model
{
    use HasFactory;
    protected $fillable = ['name','book_id'];
    protected $guard = [];
     public function book(){
       return $this->hasMany(Book::class);

    }
    
}
