<?php

namespace App\Models;

use App\Models\User;
use App\Models\Depart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['name','author','page','publisher','user_id','isbn','category','depart_id'];
    
    public function user(){
        return $this->belongsTo('App\Models\User');
        
    }
    
    public function depart(){
        return $this->hasMany(Depart::class);
    }
}

