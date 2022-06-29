<?php
namespace App\Http\Models;

use App\Http\Models\Types;

/**
 * @SWG\Definition(
 *   type="object",
 *   required={"_id","name", "hours"},
 *   @SWG\Xml(name="BranchModel"),
 * )
 */

class BranchModel {
    /** 
     * Unique branch identifier
     * @SWG\Property(type="integer", format="int32")
     */
    public $_id;
    
    /** 
     * Unique name of branch
     * @SWG\Property(type="string")
     */
    public $name;
    
    /** 
     * Hours of operation of branch
     * @SWG\Property(
     *   type="array",
     *   @SWG\Items(type="string")
     * )
     */
    public $hours;
    
     /** 
     * Contact of branch
     * @SWG\Property(type="string")
     */
    public $tel;
    
    /** 
     * Address of branch
     * @SWG\Property(ref="#/definitions/Address")
     */
    public $address;
}