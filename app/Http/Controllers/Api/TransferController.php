<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Types\Transaction;
use App\Http\Models\Transfer;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class TransferController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class TransferController extends ApiController {
    /**
     * Returns the transfers assocaited with the specified account
     * @Get("accounts/{id}/transfer")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts/{id}/transfer",
     *     description="Returns the transfers assocaited with the specified account",
     *     summary="Get all transfers by account id",
     *     operationId="get.transfer.via.account.id",
     *     produces={"application/json"},
     *     tags={"Transfer"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of account that the transfer is associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Transfer associated with specified account id",
     *         @SWG\Schema(ref="#/definitions/Transfer")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching transfer",
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
    public function getTransferByAccountId($id) {
        try{
            $statusCode = 200;
            $transaction_raw = Transfer::where('payer_id', $id)->get();
            
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
                    "message" => "Error fetching transfer"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns the transfers associated with the specified id
     * @Get("transfers/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/transfers/{id}",
     *     description="Returns the transfers associated with the specified id",
     *     summary="Get all transfers by id",
     *     operationId="get.transfers.via.id",
     *     produces={"application/json"},
     *     tags={"Transfer"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of unique transfer associated with account",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Retrieve all transfers.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Transfer")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching transfer.",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getTransfersById($id) {
        try{
            $statusCode = 200;
            $transaction_raw = Transfer::where('id', $id)->get();
            
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
                    "message" => "Error fetching transfer"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Creates a new transfer
     * @Post("accounts/{id}/transfers")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/accounts/{id}/transfers",
     *     description="Creates a new transfer",
     *     summary="Create a new transfer",
     *     operationId="create.transfer",
     *     produces={"application/json"},
     *     tags={"Transfer"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of client that the transfer would be associated with (payer)",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Transfer to be created",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Transfer"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Creation request accepted",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating transfer",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function createNewTransfer(Request $request, $id){
        try{
            $statusCode = 201;
            $account = Account::findOrFail($id);
            $aux = Account::where('id', '!=' , $id)->orWhereNull('id')->get();
            $a = (int)rand(1, count($aux) - 1);
            
            $transactionItem = factory(\App\Http\Models\Transfer::class)->create([
                                    'payer_id' => $account->id,
                                    'payee_id' => $aux[$a],
                                ]);
            $transactionItem->transaction()->save(factory(\App\Http\Models\Types\Transaction::class)->make());
            $transactionItem->transaction = $transactionItem->transaction()->get();
            
            $response = [
                [
                    "code" => 201,
                    "message" => "Transfer created",
                    "objectCreated" => $transactionItem
                ]
            ];

        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching transfer"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Updates transfer information based on a specific ID
     *
     * @Put("transfers/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/transfers/{id}",
     *     description="Updates transfer information based on a specific ID",
     *     summary="Update a specific existing transfer",
     *     operationId="update.transfer.for.id",
     *     produces={"application/json"},
     *     tags={"Transfer"},
     *     @SWG\Parameter(
     *         description="ID of specific transfer that needs to be updated",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Alter amount for associated transfer",
     *         in="query",
     *         name="amount",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="query",
     *         description="Update the status of the transfer",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi",
     *         enum={"pending", "cancelled", "completed", "rejected"}
     *     ),
     *     @SWG\Parameter(
     *         description="ID of specific transfer that needs to be updated",
     *         in="query",
     *         name="description",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Valid transfer update",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error updating transfer",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function updateTransferById(Request $request, $id){
        try{
            $statusCode = 200;
            
            $transfer = Transfer::where('id', $id)->get()->first();
            if(empty($transfer)){
                throw new \Exception;
            }
                
            $transaction = $transfer->transaction()->get()->first();
            
            //Update fields in model
            $transaction->amount = Input::get('amount');
            $transaction->description = Input::get('description');
            $transaction->status = Input::get('status');
            
            //Prepare response model;
            $transaction->save();
            $response = [
                [
                    "code" => 202,
                    "message" => "Valid transfer update"
                ]
            ];

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching transfer"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Deletes transfer based on a specific ID
     *
     * @Delete("transfers/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/transfers/{id}",
     *     description="Deletes transfers based on a specific ID",
     *     summary="Delete a specific existing transfer",
     *     operationId="delete.transfer.for.id",
     *     produces={"application/json"},
     *     tags={"Transfer"},
     *     @SWG\Parameter(
     *         description="ID of specific transfer that needs to be deleted",
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
     *         description="Error creating transfer",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function deleteTransferById(Request $request, $id){
        try{
            $statusCode = 204;
            Transfer::where('id', $id)->delete();
            Transaction::where('cloneable_id', $id)->delete();
            $response = "";

        }catch (\Exception $e){
            $statusCode = 400;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching transfer"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
}