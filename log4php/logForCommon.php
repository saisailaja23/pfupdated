<?php
/*********************************
 * Added By         : Shino P John
 * Date             : 08/01/2013
 * Activity         : Common file for to write  Log.
 *
 *********************************/
$path["wwwloc"]="http://www.parentfinder.com/";
$path["urlloc"]="http://";
$path["serloc"]="/var/www/html/pf/";
require_once("Logger.php");

$pathfile = $path["serloc"];

/* Common class for log  */
class log4PhpClass {

    private  $module                                            = "";
    private  $submodule                                         = "";
    private  $level                                             = NULL;
    private  $levelLimit                                        = "TRACE";
    private  $agencyID                                          = 'log4php';
    private  $message                                           = "";
    private  $notWrite                                          = 1;
    
    public function __construct($user_id=NULL)
    {        
        //global $path;        
        $this->settingsVar    = "/var/www/html/pf/";
     }
    /* Adding setter Methods */
    public function setModule($module)
    {
        $this->module                                           = $module;
    }
    public function setSubmodule($submodule)
    {
        $this->submodule                                        = $submodule;
    }
    public function setLevel($level)
    {
        $this->level                                            = $level;
    }
    public function setLevelLimit($levelLimit)
    {
        $this->levelLimit                                       = $levelLimit;
    }
    public function setAgencyID($agencyID)
    {
        $this->agencyID                                         = $agencyID;
    }
     public function setMessage($message)
    {
        $this->message                                          = $message;
    }
    
    public function setModuleWrite($notWrite)
    {
        $this->notWrite                                         = $notWrite;
    }
    /* Adding getter Methods */
    public function getModule()
    {
        return $this->module;
    }
     public function getSubmodule()
    {
        return $this->submodule;
    }
     public function getLevel()
    {
        return $this->level;
    }
     public function getLevelLimit()
    {
        return $this->levelLimit;
    }
     public function getAgencyID()
    {
        return $this->agencyID;
    }
     public function getMessage()
    {
        return $this->message;
    }
    public function getModuleWrite()
    {
        return $this->notWrite;
    }
    /* common function to write log */
    function writeCommonLog()
    {

      $locSubModule                                         = $this->getSubmodule();
      $locModule                                            = $this->getModule();
      $locMessage                                           = $this->getMessage();
      $locLevelLimit                                        = $this->getLevelLimit();
      $locLevel                                             = $this->getLevel();
      $locnotWrite                                          = $this->getModuleWrite();
      $locPattern                                           = ($locLevel != "")?"%-5level %message%newline":"%message%newline";

      $locFileName                                          = $this->getFileName();
      //$file                                                 = '23'.'_casenote_'.'.log';

      /* Module Description - First array - Module, Second array - its corresponding Sub Module */
      $moduleDesc = array(
          'common' => array('default1' => 'default1','minimumLevel' => 'TRACE')
          
      );

      /* Get Apperder detials */
      $appenderGet      = 'common';//($moduleDesc[$locModule][$locSubModule] == "")?'common':$moduleDesc[$locModule][$locSubModule];
      $levelLimitSet    = 'TRACE';//($moduleDesc[$locModule]['minimumLevel'] == "")?'TRACE':$moduleDesc[$locModule]['minimumLevel'];
      /* */
      $maxFileSize      = '10MB';
      $maxBackupIndex   = '2';
      Logger::configure(array(
        'rootLogger'                                        => array(
            'appenders'                                     => array($appenderGet), // setting appenders name
            'level'                                         => $levelLimitSet // setting level limit
        ),
        'appenders' => array(
            
            

            /******************************** Config for common ****************************************/
            'common' => array(
                'class'                                     => 'LoggerAppenderRollingFile',
                'layout'                                    => array(
                    'class'                                 => 'LoggerLayoutPattern',
                    'params'                                => array(
                    'conversionPattern'                     => "%-5level %date{d/m/Y H:i:s} %message%newline"
                    )
                ),
                'params'                                    => array(
                    'file'                                  => $locFileName,
                    'maxFileSize'                           => $maxFileSize,
                    'maxBackupIndex'                        => $maxBackupIndex,
                    'append'                                => true
                )
            )
            
        ),
        ));

        /* Setting level in an array   */
        $levelVal = array(
            1                                               => "TRACE",
            2                                               => "DEBUG",
            3                                               => "INFO",
            4                                               => "ERROR",
            5                                               => "FATAL",
            6                                               => "WARN"
        );

        /* Fetch a logger, it will inherit settings from the root logger */
        $log                                                = Logger::getLogger('Pass');

        /* Start logging */
        $locLevelFinal  = trim($this->getLevel())?$this->getLevel():1;
        
        /* Checking for Whether Passed level exsist in Array, Checking for wrong level passing */
        //$levelCheck     = (in_array($this->getLevel(), $levelVal))?$this->getLevel():"DEBUG";
        if (in_array($this->getLevel(), $levelVal)) {
            $levelCheck = $this->getLevel();
        } else {
            if(trim($this->getLevel())) {
                $locMessage = "Wrong level parameter passing - ".$this->getLevel()." ". $locMessage;
                $levelCheck = "DEBUG";
            } else {
                $levelCheck = "DEBUG"; 
            }
        }
        
        if($locnotWrite == 1 ){
            $logLevelFixed  = trim($this->getLevel())?$this->getLevel():"DEBUG";
            if(trim($locSubModule) && trim($locModule) & trim($locMessage)) {
                $log->$levelCheck($locMessage);
            }
        }
    }

    /* Get File name */
    function getFileName() {
        global $pathfile;
        $pathfile   = ($pathfile)?$pathfile:$this->settingsVar;
        
        $locSubModule                                       = $this->getSubmodule();
        $locModule                                          = $this->getModule();
        $locAgencyID                                        = $this->getAgencyID();
        $today                                              = date('Y-m-d');
        $filenamePass                                       = "";
        switch ($locModule) {
            case "cwhome":
                switch ($locSubModule) {
                   /* For casenote page */
                   case "casenote":
                        $filenamePass                       = $pathfile."log/".$locAgencyID."/".$locModule."_".$locSubModule.".log";
                        break;
                    /* For client listing page */
                    case "clientListing":
                        $filenamePass                       = $pathfile."log/".$locAgencyID."/".$locModule."_".$locSubModule.".log";
                        break;
                    case "ConnectionStatus":
                        $filenamePass                       = $pathfile."log/".$locAgencyID."/".$locModule."_".$locSubModule.".log";
                        break;
                    default:
                        $filenamePass                       = $pathfile."log/".$locAgencyID."/".$locModule."_".$locSubModule.".log";
                }
                break;
            default:
                
                $filenamePass                               = (trim($locSubModule) && trim($locModule))?$pathfile."log/".$locAgencyID."/".$locModule."_".$locSubModule.".log": $pathfile."log/".$locAgencyID."/commonlog".".log";
        }
        return $filenamePass;
    }
     /* common sttter method */
    function logSetterMethod($module,$submodule,$agencyID,$levelLimit='TRACE',$level = 1) {
        $this->setModule($module);
        $this->setSubmodule($submodule);
        $this->setLevelLimit($levelLimit);
        $this->setAgencyID($agencyID);
        $this->setLevel($level);
    }
    /* common write log method */
    function commonWriteLogInOne($message,$level='DEBUG') {
        $this->setLevel($level);
        $this->setMessage($message);
        $this->writeCommonLog();
    }

} // End of class

/* Getting agency id */


$logClassObj = new log4PhpClass();
$logClassObj->setAgencyID('log4php');
$logClassObj->setLevelLimit("TRACE");
$logClassObj->setLevel("DEBUG");


//$logClassObj->setSubmodule(1);setAgencyID
//$logClassObj->setModule(1);
//$logClassObj->setModuleWrite(1);
//$logClassObj->setAgencyID(1);
//$logClassObj->setLevel(1);
//$logClassObj->setMessage("another value");
//$logClassObj->writeCommonLog();

?>
