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
     * Return the Exception as an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'     => $this->id,
            'status'  => $this->status,
            'title'  => $this->title,
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
        $this->title  = $errorMessage['title'];
        $this->detail =  $errorMessage['detail'];
     
        return $this;
    }

    function errorDisplay($errorMessage){
        switch($errorMessage){           
            case 'forbidden' :
                 $error['status']=403;
                 $error['title']='Forbidden';
                 $error['detail']='Your request was valid, but you are not authorised to perform that action.';
                 break;
            case 'user_not_found' :
                $error['status']=404;
                $error['title']='User Not Found';
                $error['detail']='The User you were looking for was not found';
                 break;
            
            default :
                $error['status']=500;
                $error['title']='Not Found';
                $error['detail']='There is an error in your script.';
                break;
                  

        }
        
           
            return $error;
        
    }
}