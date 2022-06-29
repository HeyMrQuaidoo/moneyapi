<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Client;
use App\Http\Models\Branch;
use App\Http\Models\Account;
use App\Http\Models\Types\Address;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class BranchController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class BranchController extends ApiController {
    
    /**
     * Returns the accounts that have been assigned to you
     * @Get("branches")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/branches",
     *     description="Returns all branches.",
     *     summary="Get all branches",
     *     operationId="get.branches",
     *     produces={"application/json"},
     *     tags={"Branches"},
     *     @SWG\Response(
     *         response=200,
     *         description="List of branches",
     *         @SWG\Schema(ref="#/definitions/Branch")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching branches",
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
    public function getBranches() {
        try{
            $statusCode = 200;

            $branch_raw = Branch::all();

            $branch = $branch_raw->map(function ($item, $key) {
                $address = $item->address()->get()->first();
                
                if(empty($address)){
                   abort(404);
                }
                
                $item->address = $address;
                return $item;
            });

            $response = $branch->all();
        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching client"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns a branch based on a specific ID
     * @Get("branches/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/branches/{id}",
     *     description="Returns a branch based on a specific ID",
     *     summary="Get branch by id",
     *     operationId="get.branch.via.id",
     *     produces={"application/json"},
     *     tags={"Branches"},
     *     @SWG\Parameter(
     *         description="ID of branch that needs to be fetched",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of branch by specific ID",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Branch")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching branches",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getBranchesById($id) {
        try{
            $statusCode = 200;

            $branch_raw = Branch::findOrFail($id);
            $branch_raw->address = $branch_raw->get()->first();
            
            $response = $branch_raw;
            
        }catch (\Exception $e){
            $statusCode = 404;
            $response = [
                [
                    "code" => $statusCode,
                    "message" => "Error fetching client"
                ]
            ];
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
}