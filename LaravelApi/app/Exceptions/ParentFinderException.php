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
            case 'not_found' :
                $error['status']=404;
                $error['title']='The server cannot or will not process the request due to something that is perceived to be a client error.';
                $error['detail']='The resource you were looking for was not found';
                break;
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
            case 'letter_not_found' :
                 $error['status']=204;
                 $error['title']='Letter Not Found';
                 $error['detail']='The Letter you were looking for was not found';
                 break;
            case 'journal_not_found' :
                $error['status']=204;
                 $error['title']='Journal Not Found';
                 $error['detail']='The Journal you were looking for was not found';
                 break;
            case 'photo_not_found' :
                $error['status']=204;
                 $error['title']='Photo Not Found';
                 $error['detail']='The Photo you were looking for was not found';
                 break;
            case 'album_not_found' :
                $error['status']=204;
                 $error['title']='Album Not Found';
                 $error['detail']='The Album you were looking for was not found';
                 break;
            case 'video_not_found' :
                $error['status']=204;
                 $error['title']='Video Not Found';
                 $error['detail']='The Video you were looking for was not found';
                 break;
            case 'pdf_not_found' :
                $error['status']=204;
                 $error['title']='Pdf Not Found';
                 $error['detail']='The Pdf you were looking for was not found';
                 break;
            case 'flip_not_found' :
                $error['status']=204;
                 $error['title']='FlipBook Not Found';
                 $error['detail']='The FlipBook you were looking for was not found';
                 break;
            case 'ethnicity-prefer-not-found' :
                $error['status']=204;
                 $error['title']='Ethnicity Prference Not Found';
                 $error['detail']='Ethnicity Prference you were looking for was not found for this user';
                 break;
            case 'age-prefer-not-found' :
                $error['status']=204;
                 $error['title']='Age Prference Not Found';
                 $error['detail']='Age Prference you were looking for was not found for this user';
                 break;
            case 'adoption-prefer-not-found' :
                $error['status']=204;
                 $error['title']='Adoption Type Prference Not Found';
                 $error['detail']='Adoption Type Prference you were looking for was not found for this user';
                 break;
            case 'child-preference-not-found' :
                $error['status']=204;
                 $error['title']='Child Prference Not Found';
                 $error['detail']='Child Prference you were looking for was not found for this user';
                 break;
            case 'age-group-not-found' :
                $error['status']=204;
                 $error['title']='Age Group Not Found';
                 $error['detail']='Age Group you were looking for was not found for this user';
                 break;
            case 'adoption-type-not-found' :
                $error['status']=204;
                 $error['title']='Adoption Type  Not Found';
                 $error['detail']='Adoption Type you were looking for was not found for this user';
                 break;

             case 'agency-not-found' :
                $error['status']=204;
                 $error['title']='Agency  Not Found';
                 $error['detail']='Agency you were looking for was not found for this user';

            case 'no-profiles-found' :
                $error['status']=204;
                 $error['title']='No profiles Found';
                 $error['detail']='Profile list is empty'; 

                 break;
             case 'contact_not_found' :
                $error['status']=204;
                $error['title']='Contact details  Not Found';
                 $error['detail']='Contact details you were looking for was not found for this user';
                 break;
            case 'country-not-found' :
                $error['status']=204;
                 $error['message']='Country Not Found';
                 $error['detail']='Country you were looking for was not found for this user';
                 break;
            case 'state-not-found' :
                $error['status']=204;
                 $error['title']='State Not Found';
                 $error['detail']='State you were looking for was not found for this user';
                 break;
            default :
                $error['status']=204;
                $error['title']='Not Found';
                $error['detail']='There is an error in your script.';
                break;
                  

        }
        
           
            return $error;
        
    }
}