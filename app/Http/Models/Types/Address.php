<?php

namespace App\Http\Models\Types;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(
 *   type="object",
 *   @SWG\Xml(name="Address")
 * )
 */
class Address extends Model
{   
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';
    
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['client', 'branch'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'street_name', 'street_number', 'city', 'state', 'zip'];
    
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
     * Street name assigned to address
     * @SWG\Property(type="string")
     */
    protected $street_name;
	
    /**
     * Street number assigned to address
     * @SWG\Property(type="string")
     */
    protected $street_number;
    
    /**
     * City location of assigned address
     * @SWG\Property(type="string")
     */
    protected $city;
    
    /**
     * State of assigned address
     * @SWG\Property(type="string")
     */
    protected $state;
    
    /**
     * Zip code of assigned address
     * @SWG\Property(type="number", format="int32")
     */
    protected $zip;
    
    /**
     * Get all of the owning imageable models.
     */
    public function cloneable() {
        return $this->morphTo();
    }
    
    /**
     * Get the client that owns the address.
     */
    public function client(){
        return $this->belongsTo('App\Http\Models\Client');
    }
    
    /**
     * Get the branch that owns the address.
     */
    public function branch(){
        return $this->belongsTo('App\Http\Models\Branch');
    }
}