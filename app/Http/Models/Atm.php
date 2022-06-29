<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   type="object",
 *   required={"_id","geolocation", "alias"},
 *   @SWG\Xml(name="Atm"),
 * )
 */

class Atm extends Model{
    public $incrementing = false; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'geolocation', 'alias', 'atm_balance', 'language_id'];
    
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
     * Unique atm identifier
     * @SWG\Property(type="integer", format="int32")
     */
    protected $id;
    
    /** 
     * Geolocation of ATM address
     * @SWG\Property(ref="#/definitions/Geolocation")
     */
    protected $geocode;
    
    /** 
     * Unique alias associated with atm
     * @SWG\Property(type="string")
     */
    protected $alias;
    
     /** 
     * Supported languages on ATM
     * @SWG\Property(type="string")
     */
    protected $supported_lang;
    
    /** 
     * Indicates amount of money left in ATM
     * @SWG\Property(type="integer", format="int64")
     */
    protected $atm_balance;
    
    /**
     * Get the geocode record associated with the atm.
     */
    public function geocode(){
        return $this->hasOne('App\Http\Models\Types\Geocode');
    }
    
    /**
     * Get the language that belongs the atm.
     */
    public function language(){
        return $this->belongsTo('App\Http\Models\Types\Language', 'language_id', 'type');
    }
}