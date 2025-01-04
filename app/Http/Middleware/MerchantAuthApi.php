<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Merchant;
use Session;

class MerchantAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty($request->token)){
            $merchantChedk =Merchant::where('token',$request->token)
            ->first();
            if($merchantChedk){
                if($merchantChedk->status == 0 || $merchantChedk->verify == 0){
                  return response()->json(["status" => "error", "msg" => "Opps! your account has been review", "code" => 403]);
                }else{
                    Session::put("id", $merchantChedk->id);
                    Session::put("merchantId", $merchantChedk->id);
                    Session::put('merchantName',$merchantChedk->username);
                    return $next($request);     
                }
            }else{
                return response()->json(["status" => "error", "msg" => "No Account found", "code" => 404]);
            }
        }else{
            return response()->json(["status" => "error", "msg" => "No input was given!", "code" => 404]);
        }
    }
}
