<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Types\Transaction;
use App\Http\Models\Deposit;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class DepositController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class DepositController extends ApiController {
    /**
     * Returns the deposits assocaited with the specified account
     * @Get("accounts/{id}/deposit")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts/{id}/deposit",
     *     description="Returns the deposits assocaited with the specified account",
     *     summary="Get all deposits by account id",
     *     operationId="get.deposit.via.account.id",
     *     produces={"application/json"},
     *     tags={"Deposit"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of account that the deposit is associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Deposit associated with specified account id",
     *         @SWG\Schema(ref="#/definitions/Deposit")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching deposit",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     ),
     *     @SWG\ExternalDocumentation(
     *         description="find more info here",
     *         url="https://swagger.io/about"
     *     )
     * )
     *
     */
    public function getDepositByAccountId($id) {
        try{
            $statusCode = 200;
            $transaction_raw = Deposit::where('payee_id', $id)->get();
            
            $transaction = $transaction_raw->map(function ($item, $key) {
                
                if(!$item->transaction()->get() !== null){
                    $item->transaction = $item->transaction()->get();
                }
                return $item;
            });
            
            //Prepare response model;
            $response = $transaction;
        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching deposit"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns the deposits associated with the specified id
     * @Get("deposits/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/deposits/{id}",
     *     description="Returns the deposits associated with the specified id",
     *     summary="Get all deposits by id",
     *     operationId="get.deposits.via.id",
     *     produces={"application/json"},
     *     tags={"Deposit"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of unique deposit associated with account",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Retrieve all deposits.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Deposit")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching deposit.",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getDepositsById($id) {
        try{
            $statusCode = 200;
            $transaction_raw = Deposit::where('id', $id)->get();
            
            $transaction = $transaction_raw->map(function ($item, $key) {
                
                if(!$item->transaction()->get() !== null){
                    $item->transaction = $item->transaction()->get();
                }
                return $item;
            });
            
            //Prepare response model;
            $response = $transaction;

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching deposit"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Creates a new deposit
     * @Post("accounts/{id}/deposits")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/accounts/{id}/deposits",
     *     description="Creates a new deposit",
     *     summary="Create a new deposit",
     *     operationId="create.deposit",
     *     produces={"application/json"},
     *     tags={"Deposit"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of client that the deposit would be associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Deposit to be created",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Deposit"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Creation request accepted",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating deposit",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function createNewDeposit(Request $request, $id){
        try{
            $statusCode = 201;
            $account = Account::findOrFail($id);
            $transactionItem = factory(\App\Http\Models\Deposit::class)->create([
                                    'payee_id' => $account->id,
                                ]);
            $transactionItem->transaction()->save(factory(\App\Http\Models\Types\Transaction::class)->make());
            $transactionItem->transaction = $transactionItem->transaction()->get();
            
            $response = [
                [
                    "code" => 201,
                    "message" => "Deposit created",
                    "objectCreated" => $transactionItem
                ]
            ];

        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching deposit"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Updates deposit information based on a specific ID
     *
     * @Put("deposits/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/deposits/{id}",
     *     description="Updates deposit information based on a specific ID",
     *     summary="Update a specific existing deposit",
     *     operationId="update.deposit.for.id",
     *     produces={"application/json"},
     *     tags={"Deposit"},
     *     @SWG\Parameter(
     *         description="ID of specific deposit that needs to be updated",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Alter amount for associated deposit",
     *         in="query",
     *         name="amount",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="query",
     *         description="Update the status of the deposit",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi",
     *         enum={"pending", "cancelled", "completed", "rejected"}
     *     ),
     *     @SWG\Parameter(
     *         description="ID of specific deposit that needs to be updated",
     *         in="query",
     *         name="description",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Valid deposit update",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error updating deposit",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function updateDepositById(Request $request, $id){
        try{
            $statusCode = 200;
            
            $deposit = Deposit::where('id', $id)->get()->first();
            if(empty($deposit)){
                throw new \Exception;
            }
                
            $transaction = $deposit->transaction()->get()->first();
            
            //Update fields in model
            $transaction->amount = Input::get('amount');
            $transaction->description = Input::get('description');
            $transaction->status = Input::get('status');
            
            //Prepare response model;
            $transaction->save();
            $response = [
                [
                    "code" => 202,
                    "message" => "Valid deposit update"
                ]
            ];

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching deposit"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Deletes deposit based on a specific ID
     *
     * @Delete("deposits/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/deposits/{id}",
     *     description="Deletes deposits based on a specific ID",
     *     summary="Delete a specific existing deposit",
     *     operationId="delete.deposit.for.id",
     *     produces={"application/json"},
     *     tags={"Deposit"},
     *     @SWG\Parameter(
     *         description="ID of specific deposit that needs to be deleted",
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
     *         description="Error creating deposit",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function deleteDepositById(Request $request, $id){
        try{
            $statusCode = 204;
            Deposit::where('id', $id)->delete();
            Transaction::where('cloneable_id', $id)->delete();
            $response = "";

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching deposit"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
}