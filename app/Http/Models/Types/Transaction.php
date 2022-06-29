<?php
namespace App\Http\Models\Types;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   type="object",
 *   @SWG\Xml(name="Transaction")
 * )
 */
class Transaction extends Model{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'status', 'description', 'amount', 'opening_balance', 'closing_balance'];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['_id', 'id', 'created_at', 'updated_at'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['_id', 'created_at', 'updated_at', 'cloneable_id', 'cloneable_type'];
    
    /** 
     * Unique transfer identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * Transaction date of transfer initiation
     * @SWG\Property(type="string")
     */
    protected $created_date;
    
    /** 
     * Type of transaction
     * @var string
     * @SWG\Property(enum={"deposit", "withdrawal", "transfer"})
     */
    protected $type;
    
     /** 
     * Current status of current transaction
     * @var string
     * @SWG\Property(enum={"pending", "cancelled", "completed", "rejected"})
     */
    protected $status;
    
    /** 
     * Amount of of current transaction
     * @SWG\Property(type="integer", format="int64")
     */
    protected $amount;
    
    /** 
     * Opening balance of current transaction
     * @SWG\Property(type="integer", format="int64")
     */
    protected $opening_balance;
    
    /** 
     * Closing balance of current transaction
     * @SWG\Property(type="integer", format="int64")
     */
    protected $closing_balance;
    
    /** 
     * Description of current transaction
     * @SWG\Property(type="string")
     */
    protected $description;
    
    /**
     * Get all of the owning imageable models.
     */
    public function cloneable() {
        return $this->morphTo();
    }
    
    /**
     * Get the deposit that owns the transaction.
     */
    public function deposit(){
        return $this->belongsTo('App\Http\Models\Deposit');
    }
    
    /**
     * Get the transfer that owns the transaction.
     */
    public function transfer(){
        return $this->belongsTo('App\Http\Models\Transfer');
    }
    
    /**
     * Get the withdrawal that owns the transaction.
     */
    public function withdrawal(){
        return $this->belongsTo('App\Http\Models\Withdrawal');
    }
}