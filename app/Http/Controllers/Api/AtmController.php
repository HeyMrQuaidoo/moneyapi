<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Client;
use App\Http\Models\Atm;
use App\Http\Models\Account;
use App\Http\Models\Types\Address;
use App\Http\Models\Types\Geocode;
use Illuminate\Support\Facades\Input;
use Faker\Factory as Faker;

/**
 * Class AtmController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class AtmController extends ApiController {
    
    /**
     * Returns the accounts that have been assigned to you
     * @Get("atms")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/atms",
     *     description="Returns all ATMs in the specified search area.",
     *     summary="Get all atms",
     *     operationId="get.atms",
     *     produces={"application/json"},
     *     tags={"ATM"},
     *     @SWG\Parameter(
     *         name="latitude",
     *         format="int32",
     *         in="query",
     *         description="Latitude of where you're looking for an ATM. Remember Negatives for West",
     *         required=false,
     *         type="number"
     *     ),
     *     @SWG\Parameter(
     *         name="longitude",
     *         format="int32",
     *         in="query",
     *         description="Longitude of where you're looking for an ATM. Remember Negatives for South",
     *         required=false,
     *         type="number"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of ATMs",
     *         @SWG\Schema(ref="#/definitions/Atm")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching atms.",
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
    public function getATMs() {
        try{
            $statusCode = 200;
            $atm_raw = Atm::all();
            
            $atm = $atm_raw->map(function ($item, $key) {
                $item->geocode = $item->geocode()->get()->first();;
                return $item;
            });
            
            $response = $atm->all();
            
        }catch (\Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Returns an account based on a specific ID
     * @Get("atms/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/atms/{id}",
     *     description="Returns an ATM based on a specific ID",
     *     summary="Get ATM by id",
     *     operationId="get.atm.via.id",
     *     produces={"application/json"},
     *     tags={"ATM"},
     *     @SWG\Parameter(
     *         description="ID of ATM that needs to be fetched",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of ATM by specific ID",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Atm")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching atms.",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getATMById($id) {
        try{
            $statusCode = 200;
            $atm = Atm::findOrFail($id);
            
            //Prepare response model;
            $response = $atm;
            $response->geocode = $atm->geocode()->get()->first();

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