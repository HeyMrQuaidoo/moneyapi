<?php

namespace App\Http\Middleware;

use Closure;

class MyCustomMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        //Setup server
        $server = \App::make('oauth2');
        
        $response = new \OAuth2\Response();
        $requests = \OAuth2\Request::createFromGlobals();
        
        //Get access token globals vars
        $token = $server->getAccessTokenData($requests, $response);
        $clientId = $token["client_id"];
        $expiry = $token["expires"];
        
        if (!$server->verifyResourceRequest($requests)) {
        
            $responseBody = json_decode($server->getResponse()->getResponseBody());
            $reason = $responseBody->error;
            $message = $responseBody->error_description;
            $accessTokenResponse = $server->getResponse();
            
            if($reason == "expired_token"){
                $refresh_header_token = $request->headers->get("refresh_token");
                
                //Try and issue new access_token based on refresh token
                $params = array(
                    'response_type' => 'token',
                    'grant_type' => 'refresh_token',
                    'client_id' => 'testclient',
                    'client_secret' => 'testpass',
                    'refresh_token' => $refresh_header_token,
                );
                $refreshRequest = new \OAuth2\Request();
                $refreshRequest->server['REQUEST_METHOD'] = 'POST';
                $refreshRequest->request = $params;
                
                //Send request to auth server and parse responses
                $refresh_token = $server->handleTokenRequest($refreshRequest, $response);
                $responseBody = json_decode($server->getResponse()->getResponseBody());
                $reason = $responseBody->error;
                $message = $responseBody->error_description;
                $refreshTokenResponse = $server->getResponse();
        
                return $refreshTokenResponse->send();
            }
            
            return $accessTokenResponse->send();
        }
        
        return $next($request);
    }
}
