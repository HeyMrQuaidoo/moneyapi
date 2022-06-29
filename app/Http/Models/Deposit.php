<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id","payee_id"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class Deposit extends Model{
    public $incrementing = false; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'payee_id', 'transaction'];
    
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
     * Unique deposit identifier
     * @SWG\Property(type="string")
     */
    protected $id;
    
    /** 
     * Unique id associated with account of payee
     * @SWG\Property(type="string")
     */
    protected $payee_id;
    
    /**
     * Get the transaction record associated this type.
     */
    public function transaction(){
        return $this->morphMany('App\Http\Models\Types\Transaction', 'cloneable');
    }
}