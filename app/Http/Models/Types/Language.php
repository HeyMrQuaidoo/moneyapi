<?php
namespace App\Http\Models\Types;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(
 *   @SWG\Xml(name="Language")
 * )
 */
class Language extends Model{
     public $incrementing = false; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id'];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
    
    /**
     * Unique identifier of language
     * @SWG\Property(type="string")
     */
    protected $id;
	
    /**
     * Language type
     * @SWG\Property(type="string")
     */
    protected $lang;
    
    /**
     * Get the atms for the language.
     */
    public function atms(){
		return $this->hasMany('App\Http\Models\Atm', 'language_id', 'type');
	}
}