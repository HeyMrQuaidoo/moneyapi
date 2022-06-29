<?php
namespace App\Http\Models;

/**
 * @SWG\Definition(
 *   required={"_id","transaction_date", "type", "status", "payee_id", "amount"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class DepositModel {
    /** 
     * Unique deposit identifier
     * @SWG\Property(type="string")
     */
    public $_id;
    
    /** 
     * Transaction date of deposit initiation
     * @SWG\Property(type="string")
     */
    public $transaction_date;
    
    /** 
     * Type of transaction
     * @var string
     * @SWG\Property(enum={"deposit", "withdrawal", "transfer"})
     */
    public $type;
    
     /** 
     * Current status of deposit
     * @var string
     * @SWG\Property(enum={"pending", "cancelled", "completed"})
     */
    public $status;
    
    /** 
     * Unique id associated with account of payee
     * @SWG\Property(type="string")
     */
    public $payee_id;
    
    /** 
     * Amount to be deposited
     * @SWG\Property(type="integer", format="int64")
     */
    public $amount;
    
    /** 
     * Description of current deposit
     * @SWG\Property(type="string")
     */
    public $description;
}