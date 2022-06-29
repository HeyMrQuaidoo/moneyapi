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
class WithdrawalModel {
    /** 
     * Unique withdrawal identifier
     * @SWG\Property(type="string")
     */
    public $_id;
    
    /** 
     * Transaction date of withdrawal initiation
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
     * Current status of withdrawal
     * @var string
     * @SWG\Property(enum={"pending", "cancelled", "completed"})
     */
    public $status;
    
    /** 
     * Unique id associated with account of payer
     * @SWG\Property(type="string")
     */
    public $payer_id;
    
    /** 
     * Amount to be withdrawaled
     * @SWG\Property(type="integer", format="int64")
     */
    public $amount;
    
    /** 
     * Description of current withdrawal
     * @SWG\Property(type="string")
     */
    public $description;
}