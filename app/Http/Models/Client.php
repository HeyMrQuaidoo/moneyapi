<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"_id","first_name", "last_name", "address"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class Client extends Model{
    public $incrementing = false; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'first_name', 'last_name', 'address'];
    
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
    protected $hidden = ['_id', 'created_at', 'updated_at', 'password'];
    
    /** 
     * Unique client identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * First name of client
     * @SWG\Property(type="string")
     */
    protected $first_name;
    
    /** 
     * Last name of client
     * @SWG\Property(type="string")
     */
    protected $last_name;
    
     /** 
     * Balance associated with account
     * @SWG\Property(ref="#/definitions/Address")
     */
    protected $address;
    
    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getFirstNameAttribute($value){
        return $value;
    }
    
    /**
     * Get the accounts for the client.
     */
    public function accounts(){
		return $this->hasMany('App\Http\Models\Account', 'client_id', 'id');
	}
    
    /**
     * Get the address record associated with the client.
     */
    public function address(){
        return $this->morphMany('App\Http\Models\Types\Address', 'cloneable');
    }
}