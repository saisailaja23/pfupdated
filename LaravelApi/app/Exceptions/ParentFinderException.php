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
            case 'journal_not_found' :
                 $error['status']='Journal Not Found';
                 $error['detail']='The Journal you were looking for was not found';
                 break;
            case 'photo_not_found' :
                 $error['status']='Photo Not Found';
                 $error['detail']='The Photo you were looking for was not found';
                 break;
            case 'album_not_found' :
                 $error['status']='Album Not Found';
                 $error['detail']='The Album you were looking for was not found';
                 break;
            case 'video_not_found' :
                 $error['status']='Video Not Found';
                 $error['detail']='The Video you were looking for was not found';
                 break;
            case 'pdf_not_found' :
                 $error['status']='Pdf Not Found';
                 $error['detail']='The Pdf you were looking for was not found';
                 break;
            case 'flip_not_found' :
                 $error['status']='FlipBook Not Found';
                 $error['detail']='The FlipBook you were looking for was not found';
                 break;
            case 'ethnicity-prefer-not-found' :
                 $error['status']='Ethnicity Prference Not Found';
                 $error['detail']='Ethnicity Prference you were looking for was not found for this user';
                 break;
            case 'age-prefer-not-found' :
                 $error['status']='Age Prference Not Found';
                 $error['detail']='Age Prference you were looking for was not found for this user';
                 break;
            case 'adoption-prefer-not-found' :
                 $error['status']='Adoption Type Prference Not Found';
                 $error['detail']='Adoption Type Prference you were looking for was not found for this user';
                 break;
            case 'child-preference-not-found' :
                 $error['status']='Child Prference Not Found';
                 $error['detail']='Child Prference you were looking for was not found for this user';
                 break;
            case 'age-group-not-found' :
                 $error['status']='Age Group Not Found';
                 $error['detail']='Age Group you were looking for was not found for this user';
                 break;
            case 'adoption-type-not-found' :
                 $error['status']='Adoption Type  Not Found';
                 $error['detail']='Adoption Type you were looking for was not found for this user';
                 break;
            case 'no-profiles-found' :
                 $error['status']='No profiles Found';
                 $error['detail']='Profile list is empty';
                 break;
             case 'contact_not_found' :
                $error['status']='Contact details  Not Found';
                 $error['detail']='Contact details you were looking for was not found for this user';
                 break;
            case 'country-not-found' :
                 $error['status']='Country Not Found';
                 $error['detail']='Country you were looking for was not found for this user';
                 break;
            case 'state-not-found' :
                 $error['status']='State Not Found';
                 $error['detail']='State you were looking for was not found for this user';
                 break;
            default :
                $error['status']='Not Found';
                 $error['detail']='There is an error in your script.';
                 break;
                  

        }
        
           
            return $error;
        
    }
}