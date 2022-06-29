<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Client;
use App\Http\Models\Account;
use App\Http\Models\Types\Address;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class AccountController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class AccountController extends ApiController {
    /**
     * Returns all accounts
     * @Get("accounts")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts",
     *     description="Returns all accounts",
     *     summary="Get all accounts",
     *     operationId="get.accounts",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         name="type",
     *         in="query",
     *         description="Empty Param will return all types of accounts. Type of accounts that can be fetched (options: Credit Card, Savings, Checking)",
     *         required=false,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi",
     *         enum={"Credit Card", "Checking", "Savings"}
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of accounts",
     *         @SWG\Schema(ref="#/definitions/Account")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching accounts",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     ),
     *     @SWG\ExternalDocumentation(
     *         description="find more info here",
     *         url="https://swagger.io/about"
     *     ),
     *     security={
     *         {
     *             "api_key": {"write:pets", "read:pets"}
     *         }
     *     }
     * )
     *
     */
    public function getAccounts() {
         try{
            $statusCode = 200;
            
            $account_raw;
            
            if(Input::has('type')){
                $type = Input::get('type');
                $account_raw = Account::where('type', $type)->get();
            }else{
                $account_raw = Account::all();
            }
           
            $response = $account_raw;
        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching accounts"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns an account based on a specific ID
     * @Get("accounts/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts/{id}",
     *     description="Returns an account based on a specific ID",
     *     summary="Get account by id",
     *     operationId="get.accounts.via.id",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         description="ID of account that needs to be fetched",
     *         format="int32",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of accounts",
     *         @SWG\Schema(ref="#/definitions/Account")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching accounts. Account id doesn't exist",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getAccountsById(Request $request, $id) {
        
        try{
            $statusCode = 200;
            $response = Account::findOrFail($id);

        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching accounts. Account id doesn't exist"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns the accounts associated with the specific client
     * @Get("clients/{id}/accounts")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/clients/{id}/accounts",
     *     description="Returns the accounts associated with the specific client",
     *     summary="Get accounts by client id",
     *     operationId="get.accounts.via.client.id",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         description="ID of client to fetch accounts for",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="List of accounts",
     *         @SWG\Schema(ref="#/definitions/Account")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching accounts. Client id doesn't exist",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getAccountsByClientId(Request $request, $id) {
        try{
            
            $statusCode = 200;
            $account_raw;
            
            if(!empty($id)){
                $account_raw = Account::where('client_id', $id)->get();
                
                if($account_raw->isEmpty()){
                    throw new \Exception;
                }
            }
           
            $response = $account_raw;

        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching accounts. Client id doesn't exist"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Creates an account for the client with the id provided
     * @Post("clients/{id}/accounts")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/clients/{id}/accounts",
     *     description="Creates an account for the client with the id provided",
     *     summary="Create an account",
     *     operationId="create.account.for.client.id",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         description="ID of client that account will belong to",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Account to be created",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Account"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="List of accounts",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating account",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function createNewAccountForClientId(Request $request, $id){
        try{
            $statusCode = 201;
            $response = [
                [
                    "code" => 201,
                    "message" => "Valid account creation",
                    "status" => "Not yet created"
                ]
            ];

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Updates account based on a specific ID
     *
     * @Put("accounts/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/accounts/{id}",
     *     description="Updates account based on a specific ID",
     *     summary="Update a specific existing account",
     *     operationId="update.account.for.id",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         description="ID of specific account that needs to be updated",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Account update object",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Account"),
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Valid account update",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating account",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function updateAccountsById(Request $request, $id){
        try{
            $statusCode = 202;
            $response = [
                [
                    "code" => 202,
                    "message" => "Valid account update",
                    "status" => "Not yet created"
                ]
            ];

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Deletes account based on a specific ID
     *
     * @Delete("accounts/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/accounts/{id}",
     *     description="Deletes account based on a specific ID",
     *     summary="Delete a specific existing account",
     *     operationId="delete.account.for.id",
     *     produces={"application/json"},
     *     tags={"Accounts"},
     *     @SWG\Parameter(
     *         description="ID of specific account that needs to be deleted",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="No content",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating account",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function deleteAccountsById(Request $request, $id){
        try{
            $statusCode = 204;
            Account::findOrFail($id)->delete();
            
            $response = "";

        }catch (\Exception $e){
            $statusCode = 204;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "No content"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
}