<?php
namespace App\Http\Models;

/**
 * @SWG\Definition(required={"code", "message"});
 */
class ErrorModel extends Exception{
    /**
     * @SWG\Property(type="integer", format="int32");
     */
    protected $code;
    
    /**
     * @SWG\Property(type="string");
     */
    protected $message;
}