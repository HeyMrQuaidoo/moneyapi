<?php
namespace App\Http\Models;

/**
 * @SWG\Definition(
 *   required={"_id","first_name", "last_name", "address"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */

class ClientModel {
    /** 
     * Unique client identifier
     * @SWG\Property(type="string")
     */
    public $_id;
    
    /** 
     * First name of client
     * @SWG\Property(type="string")
     */
    public $first_name;
    
    /** 
     * Last name of client
     * @SWG\Property(type="string")
     */
    public $last_name;
    
     /** 
     * Balance associated with account
     * @SWG\Property(ref="#/definitions/Address")
     */
    public $address;
}