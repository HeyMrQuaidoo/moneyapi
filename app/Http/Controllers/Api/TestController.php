<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Models\Client;
use App\Http\Models\Account;
use App\Http\Models\Types\Address;

/**
 * Class TestController
 *
 * @Controller(prefix="v1/api")
 *
 * @package App\Http\Controllers\Api
 */
class TestController extends ApiController {
    
    
    /**
     * Test Class
     *
     * @Get("test")
     *
     * @return \Illuminate\Http\Response
     *
     */
     public function index(Request $request){
        try{
            // $user = Client::find('d9917902-5f90-3a0f-bb25-28d5e23b')->address()->get(array("street_name"));
            // print_r( Client::all()[1]->get(array("id")));
            // echo Client::all()[1]->get(array("id"));
            // $firstname = $user2->getAttribute("first_name");
            // $model = Client::find($id)->address()->get(array("street_name"));
            // $user = Client::find('d9917902-5f90-3a0f-bb25-28d5e23b')->address()->get(array("street_name"));
            // echo $id;
            // echo "\n";
            // echo $hex;
            // echo "\n";
            // echo hex2bin($hex);
            // echo "\n";
            
            $statusCode = 200;
            $user = Client::all()[1];
            $id = $user->id;
            $hex = bin2hex($id);
            $model = Client::find($id)->address();
            $item = collect($model->get(array("id"))->first());
            print_r($item->get('id'));
            
            $response = [
                'message'   => 'No content'
            ];

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    
    /**
     * Test Class
     *
     * @Get("testAccounts/{id}")
     *
     * @return \Illuminate\Http\Response
     *
     */
     public function test(Request $request, $id){
        try{
            $statusCode = 200;
            
            // $id = "b660dab580423666b44a9fc47852742e";
            $client = Client::find($id);
            $address = $client->address()->get()->first()->toJson();
            
            //Prepare response model;
            $response = json_decode($client->toJson());
            $response->address = json_decode($address);

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
}