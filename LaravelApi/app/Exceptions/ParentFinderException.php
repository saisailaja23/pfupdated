<?php 
namespace App\Exceptions;
 
use Exception;
 
 class ParentFinderException extends Exception
{
    /**
     * @var string
     */
    protected $id;
 
    /**
     * @var string
     */
    protected $status;
 
    /**
     * @var string
     */
    protected $title;
 
    /**
     * @var string
     */
    protected $detail;
 
    /**
     * @param @string $message
     * @return void
     */
    public function __construct($message)
    {
       $message = $this->build(func_get_args());
 
        parent::__construct($message);
    }

    /**
     * Get the status
     *
     * @return int
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * Return the Exception as an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'     => $this->id,
            'status'  => $this->status,
            'detail' => $this->detail
        ];
    }

    /**
     * Build the Exception
     *
     * @param array $args
     * @return string
     */
    protected function build(array $args)
    {
        
        $this->id = array_shift($args);
        $errorMessage=$this->errorDisplay($this->id);      
        $this->status  = $errorMessage['status'];
        $this->detail =  $errorMessage['detail'];
     
        return $this;
    }

    function errorDisplay($errorMessage){
        switch($errorMessage){
            case 'not_found' :
                 $error['status']='The server cannot or will not process the request due to something that is perceived to be a client error.';
                 $error['detail']='The resource you were looking for was not found';
                 break;
            case 'forbidden' :
                 $error['status']='Forbidden';
                 $error['detail']='Your request was valid, but you are not authorised to perform that action.';
                 break;
            case 'user_not_found' :
                 $error['status']='User Not Found';
                 $error['detail']='The User you were looking for was not found';
                 break;
            case 'letter_not_found' :
                 $error['status']='Letter Not Found';
                 $error['detail']='The Letter you were looking for was not found';
                 break;

        }
        
           
            return $error;
        
    }
}