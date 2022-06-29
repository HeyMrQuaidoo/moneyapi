<?php
use App\Http\Controllers\Auth\MyCustomUserStorageClass;
use App\Http\Controllers\Auth\DoLoginPdo;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

App::singleton('oauth2', function() {

    // $server = new OAuth2\Server($storage);
	$storage = new DoLoginPdo(DB::connection()->getPdo());
	$server = new OAuth2\Server($storage, array('allow_implicit' => true));
	
	$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
	$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array(
		'always_issue_new_refresh_token' => true,
		'refresh_token_lifetime'         => 2419200,
	)));
	
    return $server;
});


Route::post('oauth/token', function() {
	$bridgedRequest  = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
	$bridgedResponse = new OAuth2\HttpFoundationBridge\Response();
	$server = App::make('oauth2');
	
	if(Request::get('response_type') != "code"){
		$bridgedResponse = $server->handleTokenRequest($bridgedRequest, $bridgedResponse);
	}else{
		$auth_ctrl = $server->getAuthorizeController();
		$bridgedResponse = $auth_ctrl->handleAuthorizeRequest(OAuth2\Request::createFromGlobals(), $bridgedResponse , true);
	}
	
	return $bridgedResponse;
});

//Check if token is valid
Route::post('oauth/validate', function () {
	$server = App::make('oauth2');
	
    if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
		return $server->getResponse()->send();
	}
	
	$response = array('success' => true, 'message' => 'You accessed my APIs!');
	
	return \Response::json($response, 200);
});

//Revoke access for a token
Route::post('oauth/revoke', function () {
	$bridgedRequest  = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
	$bridgedResponse = new OAuth2\HttpFoundationBridge\Response();
	$server = App::make('oauth2');
	
    if ($server->handleRevokeRequest($bridgedRequest, null)) {
		return $server->getResponse()->send();
	}
	
	$response = array('success' => false, 'message' => 'Revoking access token failed!');
	
	return \Response::json($response, 401);
});

Route::get('oauth/test/{id}', ['middleware' => 'nq-oauth', function ($id) {
    return "Authentication success! You are authorized to view this page";
}]);

Route::post('oauth/private', function() {
	$bridgedRequest  = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
	$bridgedResponse = new OAuth2\HttpFoundationBridge\Response();
	
	if (App::make('oauth2')->verifyResourceRequest($bridgedRequest, $bridgedResponse)) {
		
		$token = App::make('oauth2')->getAccessTokenData($bridgedRequest);
		
		return \Response::json(array(
			'private' => 'stuff',
			'user_id' => $token['user_id'],
			'client'  => $token['client_id'],
			'expires' => $token['expires'],
		));
	}
	else {
		return \Response::json(array(
			'error' => 'Unauthorized'
		), $bridgedResponse->getStatusCode());
	}
});

Route::get('/', function () {
    return view('welcome');
});