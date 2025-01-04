<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Deliveryman;
use Session;

class DeliverymanAuthApi
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
            $Check =Deliveryman::where('token',$request->token)
            ->first();
            if($Check){
                if($Check->status == 0){
                  return response()->json(["status" => "error", "msg" => "Opps! your account has been review", "code" => 403]);
                }else{
                    Session::put("id", $Check->id);
                    Session::put("deliverymanId", $Check->id);
                    Session::put('name',$Check->name);
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
