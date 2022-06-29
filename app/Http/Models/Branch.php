<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   type="object",
 *   required={"_id","name", "hours"},
 *   @SWG\Xml(name="Branch"),
 * )
 */

class Branch extends Model{
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branches';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'hours', 'tel', 'address'];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
    
    /** 
     * Unique branch identifier
     * @SWG\Property(type="integer", format="int32")
     */
    protected $id;
    
    /** 
     * Unique name of branch
     * @SWG\Property(type="string")
     */
    protected $name;
    
    /** 
     * Hours of operation of branch
     * @SWG\Property(
     *   type="array",
     *   @SWG\Items(type="string")
     * )
     */
    protected $hours;
    
     /** 
     * Contact of branch
     * @SWG\Property(type="string")
     */
    protected $tel;
    
    /** 
     * Address of branch
     * @SWG\Property(ref="#/definitions/Address")
     */
    protected $address;
    
    /**
     * Get the address record associated with the branch.
     */
    public function address(){
        return $this->morphMany('App\Http\Models\Types\Address', 'cloneable');
    }
}