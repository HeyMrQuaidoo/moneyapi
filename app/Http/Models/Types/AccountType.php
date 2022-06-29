<?php

namespace App\Http\Models\Types;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id","type"}
 * )
 */
class AccountType extends Model
{
    public $incrementing = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type'];
    
     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['_id'];
    
    /** 
     * Unique account type identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * Possible account types
     * @var string
     * @SWG\Property(enum={"Savings", "Checking", "Credit Card"})
     */
    protected $type;
    
    /**
     * Get the accounts owned by the type.
     */
    public function accounts(){
        return $this->hasMany('App\Http\Models\Account', 'type', 'type');
    }
}
