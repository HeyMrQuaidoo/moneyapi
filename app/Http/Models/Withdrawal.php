<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id", "payer_id"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class Withdrawal extends Model{
    public $incrementing = false; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'payer_id'];
    
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
    protected $hidden = ['_id', 'created_at', 'updated_at'];
    
    /** 
     * Unique withdrawal identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * Unique id associated with account of payer
     * @SWG\Property(type="string")
     */
    protected $payer_id;
    
    /**
     * Get the transaction record associated this type.
     */
    public function transaction(){
        return $this->morphMany('App\Http\Models\Types\Transaction', 'cloneable');
    }
}