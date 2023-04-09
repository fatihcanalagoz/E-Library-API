<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class isPunished
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
     {
       
      $user = User::findOrFail(auth()->user()->id);
        if(Carbon::parse(auth()->user()->punished_time) < Carbon::now()){
            $user->is_punished = 0;
            $user->punished_time =null;
            $user->update();
            return $next($request);
        }
        
        return response()->json(['message' => 'Hesabınız '.Carbon::parse(auth()->user()->punished_time).' tarihine kadar yasaklandı','reason' => 'Kitap teslim tarihi aşıldı']);
        
    }
}
