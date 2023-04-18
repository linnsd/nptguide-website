<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyApiToken
{
     private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if($request->headers->get('Content-Type') === 'application/json'){
            $apiKey =  $request->headers->get('Authorization');


            $user = $this->userRepository->findByField('api_token',$apiKey)->first();
            if (!$user) {
                return response(['responseCode'=>0,'message'=>"API Key does not match.",'result'=>null]);
            }

           
        }else{
            return response(['responseCode'=>0,'message'=>"Unsupported Media Type",'status' => 415]);
        }
         return $next($request);

        
    }
}
