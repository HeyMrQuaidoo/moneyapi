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
 * Class ClientController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class ClientController extends ApiController {
    /**
     * Returns the client that the specified account belongs to.
     * @Get("accounts/{id}/client")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/accounts/{id}/client",
     *     description="Returns the client that the specified account belongs to.",
     *     summary="Get the client associated with the specified account",
     *     operationId="get.client.via.account.id",
     *     produces={"application/json"},
     *     tags={"Client"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of account that the client is associated with",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Client associated with specified id",
     *         @SWG\Schema(ref="#/definitions/Client")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching client",
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
    public function getClientByAccountId(Request $request, $id) {
        try{

            $account = Account::findOrFail($id);

            $statusCode = 200;
            $client = $account->client()->get()->first();
            $client->address = $client->address()->get()->first();
            
            $response = $client;

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
     * Returns all clients
     * @Get("clients")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/clients",
     *     description="Returns all clients.",
     *     summary="Get all clients",
     *     operationId="get.clients",
     *     produces={"application/json"},
     *     tags={"Client"},
     *     @SWG\Response(
     *         response=200,
     *         description="Retrieve all clients.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Client")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching clients.",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function getClients() {
        try{
            $statusCode = 200;

            $clients_raw = Client::all();

            $clients = $clients_raw->map(function ($item, $key) {
                $item->address = $item->address()->get()->first();
                
                return $item;
            });

            $response = $clients->all();
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
     * Returns the client associated with the specified client id.
     * @Get("clients/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/clients/{id}",
     *     description="Returns the client associated with the specified client id.",
     *     summary="Get client by id",
     *     operationId="get.client.via.client.id",
     *     produces={"application/json"},
     *     tags={"Client"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of client being queried",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Client associated with specified id",
     *         @SWG\Schema(ref="#/definitions/Client")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error fetching client",
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
     public function getClientsByClientId(Request $request, $id) {
        try{
            $statusCode = 200;

            $client = Client::findOrFail($id);
            
            //Prepare response model;
            $response = $client;
            $response->address = $client->address()->get()->first();

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
     * Creates a new client
     * @Post("clients")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/clients",
     *     description="Creates a new client",
     *     summary="Create a client",
     *     operationId="create.client",
     *     produces={"application/json"},
     *     tags={"Client"},
     *     @SWG\Parameter(
     *         description="Client to be created",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Client"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Creation request accepted",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error creating client",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function createNewClient(Request $request){
        try{
            $statusCode = 201;

            $input_raw = collect(Input::all());

            $faker = Faker::create();
            $client = new Client;
            $address = new Address;

            $address->id = str_replace("-","",$faker->Uuid);
            $address->street_name = $faker->streetAddress;
            $address->street_number = $faker->streetName;
            $address->city = $faker->city;
            $address->state = $faker->state;
            $address->zip = $faker->postcode;

            $client->id = str_replace("-","",$faker->Uuid);
            $client->first_name = $faker->firstName;
            $client->last_name = $faker->lastName;

            $address->client_id = $client->id;
            $client->address = $address->id;

            $client->save();
            $address->save();

            $response = "Creation request accepted...";

         }catch (Exception $e){
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
     * Updates client information based on a specific ID
     *
     * @Put("clients/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/clients/{id}",
     *     description="Updates client information based on a specific ID",
     *     summary="Update a specific existing client",
     *     operationId="update.client.for.id",
     *     produces={"application/json"},
     *     tags={"Client"},
     *     @SWG\Parameter(
     *         description="ID of specific client that needs to be updated",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Client update object",
     *         in="body",
     *         name="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Address"),
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Valid client update",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Error updating client",
     *         @SWG\Schema(
     *             ref="#/definitions/ErrorModel"
     *         )
     *     )
     * )
     */
     public function updateClientById(Request $request, $id){
        try{
            $statusCode = 200;

            $input_raw = collect(Input::all());

            //Find client and associated address
            $client = Client::findOrFail($id);
            $address = $client->address()->get()->first();

            //Check if input address model is not null and update client address
            $input = $input_raw->map(function ($item, $key)  use ($address) {

                if(!empty($item) && ($item !== "string")){
                    $address->$key = $item;
                }

                return $address->$key;
            });

            $address->save();
            $response = $address;

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