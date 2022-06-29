<?php
namespace App\Http\Models;

/**
 * @SWG\Definition(
 *   required={"_id","client_id"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */

class AccountModel {
    /** 
     * Unique account identifier
     * @SWG\Property(type="integer", format="int32")
     */
    public $_id;
    
    /** 
     * Type associated with account
     * @var string
     * @SWG\Property(enum={"Savings", "Checking", "Credit Card"})
     */
    public $type;
    
    /** 
     * Alias associated with account
     * @SWG\Property(type="string")
     */
    public $alias;
    
     /** 
     * Balance associated with account
     * @SWG\Property(type="integer", format="int64")
     */
    public $balance;
    
    /** 
     * 16 digit Account Number that is associated with the account e.g. a Credit Card/Debit Card Number
     * @SWG\Property(type="string")
     */
    public $account_number;
    
    /** 
     * Unique id of client who owns the account
     * @SWG\Property(type="string")
     */
    public $client_id;
}