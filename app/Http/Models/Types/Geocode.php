<?php
namespace App\Http\Models\Types;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   @SWG\Xml(name="Geocode")
 * )
 */
class Geocode extends Model
{    
    public $incrementing = true; 
    
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['atm'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'longitude', 'latitude'];
    
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
     * Longitude of ATM address - (-180 : 180)
     * @SWG\Property(type="number", maximum = 180,  minimum = -180)
     */
    protected $longitude;
	
    /**
     * Latitude of ATM address - (-90 : 90)
     * @SWG\Property(type="number", maximum = 90,  minimum = -90)
     */
    protected $latitude;
    
    /**
     * Get the atm that owns the geocode.
     */
    public function atm(){
        return $this->belongsTo('App\Http\Models\Atm');
    }
}