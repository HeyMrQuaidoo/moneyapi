<?php
namespace App\Http\Models;

use App\Http\Models\Types;

/**
 * @SWG\Definition(
 *   type="object",
 *   required={"_id","geolocation", "alias"},
 *   @SWG\Xml(name="AtmModel"),
 * )
 */

class AtmModel {
    /** 
     * Unique atm identifier
     * @SWG\Property(type="integer", format="int32")
     */
    public $_id;
    
    /** 
     * Geolocation of ATM address
     * @SWG\Property(ref="#/definitions/Geolocation")
     */
    public $geolocation;
    
    /** 
     * Unique alias associated with atm
     * @SWG\Property(type="string")
     */
    public $alias;
    
     /** 
     * Supported languages on ATM
     * @SWG\Property(type="string")
     */
    public $supported_lang;
    
    /** 
     * Indicates amount of money left in ATM
     * @SWG\Property(type="integer", format="int64")
     */
    public $atm_balance;
}