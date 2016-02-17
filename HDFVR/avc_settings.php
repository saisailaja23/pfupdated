<?php
###################### HDFVR Configuration File ######################

//connectionstring:String
//desc: the rtmp connection string to the avchat2 application on your Flash Media Server server
//values: 'rtmp://localhost/hdfvr/_definst_', 'rtmp://myfmsserver.com/hdfvr/_definst_', etc...
 //$config['connectionstring']='rtmp://www.parentfinder.com/hdfvr/_definst_';
// $config['connectionstring']='rtmp://my.media.server.ip/hdfvr/_definst_';
//$config['connectionstring']='rtmp://localhost/hdfvr/';
$config['connectionstring']='rtmp://208.105.165.48/hdfvr/_definst_';

//languagefile:String
//description: path to the XML file containing words and phrases to be used in the video recorder interface, use this setting to switch between languages while maintainging several language files
//defaut: 'translations/en.xml'
$config['languagefile']='translations/en.xml';

//qualityurl: String
//desc: path to the .xml file describing video and audio quality to use for recording, this variable has higher priority than the qualityurl from flash vars
//values: url paths to the audio/video quality profile files
$config['qualityurl']='';

//maxRecordingTime: Number
//desc: the maximum recording time in seconds
//values: any number greater than 0;
$config['maxRecordingTime']=120;

//userId: String
//desc: the id of the user logged into the website, not mandatory, this var is passed back to the save_video_to_db.php file via GET when the [SAVE] button in the recorder is pressed, this variable can also be passed via flash vars like this: videorecorder.swf?userId=XXX, but the value in this file, if not empty, takes precedence
//values: strings or numbers will do
//by default its empty: ""
$config['userId']='';

//outgoingBuffer: Number
//desc: Specifies how long the buffer for the outgoing audio/video data can grow before Flash Player starts dropping frames. On a high-speed connection, buffer time will not affect anything because data is sent almost as quickly as it is captured and there is no need to buffer it. On a slow connection, however, there might be a significant difference between how fast Flash Player can capture audiovideo data data and how fast it can be sent to the client, thus the surplus needs to b buffered.
//values: 30,60,etc...
//default:60
$config['outgoingBuffer']=60;

//playbackBuffer: Number
//desc: Specifies how much video time to buffer when (after recording a movie) you play it back
//values: 1, 10,20,30,60,etc...
//default:1
$config['playbackBuffer']= 1;

//autoPlay: String
//desc: weather the recorded video should play automatically after recording it or we should  wait for the user to press the PLAY button
//values: false, true
//default: false
$config['autoPlay']='false';

//deleteUnsavedFlv: String
//desc: weather the recorded video should be deleted  from the server if the client does not press save
//values: false, true
//default: false
$config['deleteUnsavedFlv'] = 'true';

//hideSaveButton:Number
//desc: makes the [SAVE] button invisible. In this case you can use the onUploadDone java script hook to get some info about the newly recorded flv file and redirect the user/enable a button on the HTML page/populate some hidden FORM vars/etc...
//values: 1 for hidden, 0 for visible
//default: 0 (visible)
$config['hideSaveButton']=0;


//onSaveSuccessURL:String
//desc: when the [SAVE] button is pressed (if its enabled) save_vide_to_db.php (or .asp) is called. If the save operation succeeds AND if this variable is NOT EMPTY, the user will be taken to the URL
//values: any URL you want the user directed to after he presses the [Save] button
//default: ""
$config["onSaveSuccessURL"]="";

//snapshotSec:Number
//desc: the snapshot is taken when the recording reaches this length/time 
//values: any number  greater or equal to 0,  if 0 then the snap shot is taken when the [REC] button is pressed
$config["snapshotSec"] = 5;

//snapshotEnable:Number
//desc: if set to true the recorder will take a snapshot 
//values: true or false
$config["snapshotEnable"] = "true";

//minRecordTime:Number
//desc: the minimum number of seconds a recording must be in length. The STOP button will be disabled until the recording reaches this length! 
//values: any number lower them maxRecordingTime
//default:5
$config["minRecordTime"] = 5;

//backgroundColor:Hex number
//desc: color of background area inside the video recorder around the video area
//values: any color in hex format
//default:0xffffff (white)
$config["backgroundColor"] = 0xffffff;

//menuColor:Hex number
//desc: the color of the lower area of the recorder containing the buttons and the scrub bar
//values:any color in hex format
//default:0x333333
$config["menuColor"] = 0x333333;

//radiusCorner:Number
//desc: the radius of the 4 corners of the video recorder
//values: 0 for no rounded corners, 4,8,16,etc...
//default: 4
$config["radiusCorner"] = 4;

//showFps:String
//desc: 'false' to hide it 'true' to show it
//values: true or false
//default: true
$config["showFps"] = 'true';

//recordAgain:String
//desc:if set to true the user will be able to record again and again until he is happy with the result. If set to false he only has 1 shot!
//values:'false' for one recording, 'true' for multiple recordings
//default: true
$config["recordAgain"] =  'true';

//useUserId:String
//desc:if set to true the stream name will be {userID}_{ timestamp_random}
//values:'false' not using the userId in the file name, tru for using it
//default: false
$config["useUserId"] =  'false';

//streamPrefix:String
//desc: adds a prefix to the file name eof the reocrding on the media server like this: {prefix}_{userID}_{timestamp_random} or {prefix}_{timestamp_random} if the useUserId option is set to false
//values: a string
//default: ""
$config["streamPrefix"] = "";


//streamName:String
//desc: By default the application generates the name of the recorded stream using a pattern,if you want to generate a certain name for the recorded streams set this and it will ovewite the pattern {prefix}_{userID}_{timestamp_random} 
//values: a string
//default: ""
$config["streamName"] = "";

//disableAudio:String
//desc: By default the application recrds audio and video .if you want to disable te audio signal on the recorded string set this to true
//values: 'false' to record audio 'true' to record without audio
//default: 'false'
$config["disableAudio"] = 'false';

//chmodStreams:String
//desc: By default this is left empty,if you want after you save the recorded streams to set file permisions for them you shoould fill this
//values: "0666"
//default: ""
$config["chmodStreams"] = "";

##################### DO NOT EDIT BELOW ############################

//integration.php most commonly contains code for integrating with 3rd party cms systems or for overwriting values in this file
if (file_exists("integration.php")){ include("integration.php");}

echo "donot=removethis";
foreach ($config as $key => $value){
	echo '&'.$key.'='.$value;
}
?>