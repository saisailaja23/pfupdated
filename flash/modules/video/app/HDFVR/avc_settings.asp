<%
''###################### HDFVR Configuration File #####################

''connectionstring:String
''desc: the rtmp connection string to the avchat2 application on your Flash Media Server server
''values: 'rtmp://localhost/hdfvr/_definst_', 'rtmp://myfmsserver.com/hdfvr/_definst_', etc...
Dim connectionstring
connectionstring=""

''languagefile:String
''description: path to the XML file containing words and phrases to be used in the video recorder interface, use this setting to switch between languages while maintainging several language files
''defaut: "translations/en.xml"
Dim languagefile
languagefile="translations/en.xml"

''qualityurl: String
''desc: path to the .xml file describing video and audio quality to use for recording, this variable has higher priority than the qualityurl from flash vars
''values: url paths to the audio/video quality profile files
Dim qualityurl
qualityurl=""

''maxRecordingTime: Number
''desc: the maximum recording time in secdonds
''values: any number greater than 0
Dim maxRecordingTime
maxRecordingTime=120

''userId: String
''desc: the id of the user logged into the website, not mandatory, this var is passed back to the save_video_to_db.php file via GET when the [SAVE] button in the recorder is pressed. This variable can also be passed via flash vars like this: videorecorder.swf?userId=XXX, but the value in this file, if not empty, takes precedence.
''values: strings or numbers will do
''by default its empty: ""
Dim userId
userId = ""

''outgoingBuffer: Number
''desc: Specifies how long the buffer for the outgoing data can grow before Flash Player starts dropping frames. On a high-speed connection, buffer time shouldn't be a concern data is sent almost as quickly as Flash Player can buffer it. On a slow connection, however, there might be a significant difference between how fast Flash Player buffers the data and how fast it can be sent to the client. Only affects the recording process of the recorder!
''values: 30,60,etc...
''default:60
Dim outgoingBuffer
outgoingBuffer=60

''playbackBuffer: Number
''desc: Specifies how much video time to buffer when (after recording a movie) you play it back
''values: 1, 10,20,30,60,etc...
''default:1
Dim playbackBuffer
playbackBuffer=1

''autoPlay: String
''desc: weather the recorded video should play automatically after recording it or we should  wait for the user to press the PLAY button
''values: false, true
Dim autoPlay
autoPlay="false"

''deleteUnsavedFlv: String
''desc: weather the recorded video should be deleted  from the server if the client does not press save
''values: false, true
''default: false
Dim deleteUnsavedFlv
deleteUnsavedFlv = "false"

''hideSaveButton:Number
''desc: makes the [SAVE] button invisible. In this case you can use the onUploadDone java script hook to get some info about the newly recorded flv file and redirect the user/enable a button on the HTML page/populate some hidden FORM vars/etc...
''values: 1 for hidden, 0 for visible
''default: 0 (visible)
Dim hideSaveButton
hideSaveButton=0

''onSaveSuccessURL:String
''desc: when the [SAVE] button is pressed (if its enabled) save_vide_to_db.php is called. If the save operation succeeds AND if this variable is NOT EMPTY, the user will be taken to the url described in this URL
''desc: when the [SAVE] button is pressed (if its enabled) save_vide_to_db.php is called. If the save operation succeeds the onSaveOk java script hook is also called with some variables <- you can also use js here to make a redirect or show a button on the HTML page
''values: 
Dim onSaveSuccessURL
onSaveSuccessURL=""

''snapshotSec:Number
''desc: desc: the snapshot is taken when the recording reaches this length/time
''values: any number  greater or equal to 0,  if 0 then the snap shot is taken when the [REC] button is pressed
''default:5
Dim snapshotSec
snapshotSec = 5

''snapshotEnable:Number
''desc: if set to true the recorder will take a snapshot 
''values: true or false
Dim snapshotEnable
snapshotEnable = "true"

''minRecordTime:Number
''desc: the minimum number of seconds a recording must be in length. The STOP button will be disabled until the recording reaches this length!
''values: any number lower them maxRecordingTime
''default:5
Dim minRecordTime
minRecordTime = 5

''backgroundColor:Hex number
''desc: color of background area inside the video recorder around the video area
''values: any color in hex format
''default:0xffffff (white)
Dim backgroundColor
backgroundColor = "0xffffff"


''menuColor:Hex number
''desc: the color of the lower area of the recorder containing the buttons and the scrub bar
''values: any color in hex format
''default:0x333333
Dim menuColor
menuColor = "0x333333"

''radiusCorner:Number
''Desc: the radius of the 4 corners of the video recorder
''values: 0 for no rounded corners, 4,8,16,etc...
''default: 4
Dim radiusCorner
radiusCorner = 4

''showFps:String
''desc: 'false' to hide it 'true' to show it
''values: true or false
''default: true
Dim showFps
showFps = "true"

''recordAgain:String
''desc:if set to true the user will be able to record again and again until he is happy with the result. If set to false he only has 1 shot!
''values:'false' for one recording, 'true' for multiple recordings
''default: true
Dim recordAgain
recordAgain =  "true"

''useUserId:String
''desc:if set to true the stream name will be {userID}_{ timestamp_random}
''values:'false' not using the userId in the file name, tru for using it
''default: false
Dim useUserId
useUserId =  "false"

''streamPrefix:String
''desc: adds a prefix to the file name eof the reocrding on the media server like this: {prefix}_{userID}_{timestamp_random} or {prefix}_{timestamp_random} if the useUserId option is set to false
''values: a string
''default: ""
Dim streamPrefix
streamPrefix = ""


''streamName:String
''desc: By default the application generates the name of the recorded stream using a pattern,if you want to generate a certain name for the recorded streams set this and it will ovewite the pattern {prefix}_{userID}_{timestamp_random} 
''values: a string
''default: ""
Dim streamName
streamName = ""

''disableAudio:String
''desc: By default the application recrds audio and video .if you want to disable te audio signal on the recorded string set this to true
''values: "false" to record audio "true" to record without audio
''default: "false"
Dim disableAudio
disableAudio = "false"

''chmodStreams:String
''desc: By default this is left empty,if you want after you save the recorded streams to set file permisions for them you shoould fill this
''values: "0666"
''default: ""
Dim chmodStreams
chmodStreams = "";

''##################### DO NOT EDIT BELOW ############################
Response.write("connectionstring=")
Response.write(connectionstring)
Response.write("&languagefile=")
Response.write(languagefile)
Response.write("&qualityurl=")
Response.write(qualityurl)
Response.write("&maxRecordingTime=")
Response.write(maxRecordingTime)
Response.write("&userId=")
Response.write(userId)
Response.write("&outgoingBuffer=") 
Response.write(outgoingBuffer)
Response.write("&playbackBuffer=") 
Response.write(playbackBuffer)
Response.write("&autoPlay=")
Response.write(autoPlay)
Response.write("&deleteUnsavedFlv=")
Response.write(deleteUnsavedFlv)
Response.write("&hideSaveButton=")
Response.write(hideSaveButton)
Response.write("&onSaveSuccessURL=")
Response.write(onSaveSuccessURL)
Response.write("&snapshotSec=")
Response.write(snapshotSec)
Response.write("&snapshotEnable=")
Response.write(snapshotEnable)
Response.write("&minRecordTime=")
Response.write(minRecordTime)
Response.write("&backgroundColor=")
Response.write(backgroundColor)
Response.write("&menuColor=")
Response.write(menuColor)
Response.write("&radiusCorner=")
Response.write(radiusCorner)
Response.write("&showFps=")
Response.write(showFps)
Response.write("&recordAgain=")
Response.write(recordAgain)
Response.write("&useUserId=")
Response.write(useUserId)
Response.write("&streamPrefix=")
Response.write(streamPrefix)
Response.write("&streamName=")
Response.write(streamName)
Response.write("&disableAudio=")
Response.write(disableAudio)
Response.write("&chmodStreams=")
Response.write(chmodStreams)
%>