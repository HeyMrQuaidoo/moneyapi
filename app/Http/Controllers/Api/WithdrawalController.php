<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Types\Transaction;
use App\Http\Models\Withdrawal;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class WithdrawalController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class WithdrawalController extends ApiController {
    /**
     * Returns the withdrawals assocaited with the specified account
     * @Get("accounts/{id}/withdrawal")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts/{id}/withdrawal",
     *     description="Returns the withdrawals assocaited with the specified account",
     *     summary="Get all withdrawals by account id",
     *     operationId="get.withdrawal.via.account.id",
     *     produces={"application/json"},
     *     tags={"Withdrawal"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of account that the withdrawal is associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Withdrawal associated with specified account id",
     *         @SWG\Schema(ref="#/definitions/Withdrawal")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching withdrawal",
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
    public function getWithdrawalByAccountId($id) {
         try{
            $statusCode = 200;
            $transaction_raw = Withdrawal::where('payer_id', $id)->get();
            
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
                    "message" => "Error fetching withdrawal"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns the withdrawals associated with the specified id
     * @Get("withdrawals/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/withdrawals/{id}",
     *     description="Returns the withdrawals associated with the specified id",
     *     summary="Get all withdrawals by id",
     *     operationId="get.withdrawals.via.id",
     *     produces={"application/json"},
     *     tags={"Withdrawal"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of unique withdrawal associated with account",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Retrieve all withdrawals.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Withdrawal")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching withdrawal.",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getWithdrawalsById($id) {
        try{
            $statusCode = 200;
            $transaction_raw = Withdrawal::where('id', $id)->get();
            
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
                    "message" => "Error fetching withdrawal"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Creates a new withdrawal
     * @Post("accounts/{id}/withdrawals")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/accounts/{id}/withdrawals",
     *     description="Creates a new withdrawal",
     *     summary="Create a new withdrawal",
     *     operationId="create.withdrawal",
     *     produces={"application/json"},
     *     tags={"Withdrawal"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of client that the withdrawal would be associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Withdrawal to be created",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Withdrawal"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Creation request accepted",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating withdrawal",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function createNewWithdrawal(Request $request, $id){
        try{
            $statusCode = 201;
            $account = Account::findOrFail($id);
            $transactionItem = factory(\App\Http\Models\Withdrawal::class)->create([
                                    'payer_id' => $account->id,
                                ]);
            $transactionItem->transaction()->save(factory(\App\Http\Models\Types\Transaction::class)->make());
            $transactionItem->transaction = $transactionItem->transaction()->get();
            
            $response = [
                [
                    "code" => 201,
                    "message" => "Withdrawal created",
                    "objectCreated" => $transactionItem
                ]
            ];

        }catch (Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching withdrawal"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Updates withdrawal information based on a specific ID
     *
     * @Put("withdrawals/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/withdrawals/{id}",
     *     description="Updates withdrawal information based on a specific ID",
     *     summary="Update a specific existing withdrawal",
     *     operationId="update.withdrawal.for.id",
     *     produces={"application/json"},
     *     tags={"Withdrawal"},
     *     @SWG\Parameter(
     *         description="ID of specific withdrawal that needs to be updated",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Alter amount for associated withdrawal",
     *         in="query",
     *         name="amount",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="query",
     *         description="Update the status of the withdrawal",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi",
     *         enum={"pending", "cancelled", "completed", "rejected"}
     *     ),
     *     @SWG\Parameter(
     *         description="ID of specific withdrawal that needs to be updated",
     *         in="query",
     *         name="description",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Valid withdrawal update",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error updating withdrawal",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function updateWithdrawalById(Request $request, $id){
        try{
            $statusCode = 200;
            
            $withdrawal = Withdrawal::where('id', $id)->get()->first();
            if(empty($withdrawal)){
                throw new \Exception;
            }
                
            $transaction = $withdrawal->transaction()->get()->first();
            
            //Update fields in model
            $transaction->amount = Input::get('amount');
            $transaction->description = Input::get('description');
            $transaction->status = Input::get('status');
            
            //Prepare response model;
            $transaction->save();
            $response = [
                [
                    "code" => 202,
                    "message" => "Valid withdrawal update"
                ]
            ];

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching withdrawal"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Deletes withdrawal based on a specific ID
     *
     * @Delete("withdrawals/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/withdrawals/{id}",
     *     description="Deletes withdrawals based on a specific ID",
     *     summary="Delete a specific existing withdrawal",
     *     operationId="delete.withdrawal.for.id",
     *     produces={"application/json"},
     *     tags={"Withdrawal"},
     *     @SWG\Parameter(
     *         description="ID of specific withdrawal that needs to be deleted",
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
     *         description="Error creating withdrawal",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function deleteWithdrawalById(Request $request, $id){
        try{
            $statusCode = 204;
            Withdrawal::where('id', $id)->delete();
            Transaction::where('cloneable_id', $id)->delete();
            $response = "";

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching withdrawal"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
}