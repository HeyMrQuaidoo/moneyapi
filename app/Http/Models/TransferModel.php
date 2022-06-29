<?php
namespace App\Http\Models;

/**
 * @SWG\Definition(
 *   required={"_id","transaction_date", "type", "status", "payee_id", "payer_id", "amount"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class TransferModel {
    /** 
     * Unique transfer identifier
     * @SWG\Property(type="string")
     */
    public $_id;
    
    /** 
     * Transaction date of transfer initiation
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
     * Current status of transfer
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
     * Unique id associated with account of payer
     * @SWG\Property(type="string")
     */
    public $payer_id;
    
    /** 
     * Amount to be transfered
     * @SWG\Property(type="integer", format="int64")
     */
    public $amount;
    
    /** 
     * Description of current transfer
     * @SWG\Property(type="string")
     */
    public $description;
}