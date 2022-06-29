<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"_id","client_id"}
 * )
 */
class Account extends Model
{
    public $incrementing = true; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'type', 'alias', 'balance', 'account_number', 'client_id'];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['_id', 'created_at', 'updated_at'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['_id', 'created_at', 'updated_at'];
    
    /** 
     * Unique account identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * Type associated with account
     * @var string
     * @SWG\Property(enum={"Savings", "Checking", "Credit Card"})
     */
    protected $type;
    
    /** 
     * Alias associated with account
     * @SWG\Property(type="string")
     */
    protected $alias;
    
     /** 
     * Balance associated with account
     * @SWG\Property(type="integer", format="int64")
     */
    protected $balance;
    
    /** 
     * 16 digit Account Number that is associated with the account e.g. a Credit Card/Debit Card Number
     * @SWG\Property(type="string")
     */
    protected $account_number;
    
    /** 
     * Unique id of client who owns the account
     * @SWG\Property(type="string")
     */
    protected $client_id;
  
    /**
     * Get the client that owns the account.
     */
    public function client(){
        return $this->belongsTo('App\Http\Models\Client', 'client_id', 'id');
    }
    
    /**
     * Get the account type associated with the account.
     */
    public function type(){
        return $this->belongsTo('App\Http\Models\Types\AccountType', 'type', 'type');
    }
}
