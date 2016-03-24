<?php 
namespace App\Exceptions;
 
use Exception;
use ParentFinderException;
 class NotFoundException extends ParentFinderException
{
    protected $status = '404';
 
    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());
 echo "d";
        parent::__construct($message);
    }
}