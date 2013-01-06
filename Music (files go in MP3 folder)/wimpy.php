<?php
$wimpyVersion = "v4.1";
/////////////////////////////////////////////////////////////////
//                                                             //
//                                                             //
//                                                             //
//                                                             //
//                         wimpy                               //
//                                                             //
//           by Mike Gieson <info@wimpyplayer.com>             //
//          available at http://www.wimpyplayer.com            //
//                     ©2002-2005 plaino                       //
//                                                             //
//                                                             //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
//                       INSTALLATION:                         //
//                                                             //
/////////////////////////////////////////////////////////////////
// 
// Upload wimpy.php and wimpy.swf to the folder that 
// contains your mp3's.
// 
// USE AT YOUR OWN RISK.
// 
// www.wimpyplayer.com
// www.gieson.com
// info@wimpyplayer.com
// 
/////////////////////////////////////////////////////////////////
//                                                             //
//     DIRECTORY AND FILE CONFIGURATION                        //
//                                                             //
//     Use this to prevent specific folders                    //
//     from displaying within Wimpy.                           //
//                                                             //
/////////////////////////////////////////////////////////////////
//
// Wimpy Flash filename
// the filename of the wimpy flash movie:
$wimpySWFfilename = "wimpy.swf";
//
// Hide folders:
// Seperate each folder name with a comma
$hide_folders = "getid3,playlister_output,skins,_private,_private,_vti_bin,_vti_cnf,_vti_pvt,_vti_txt,cgi-bin";
//
// Hide files:
// Seperate each file name with a comma. 
// NOTE: All XML files that contain the word "skin" will automatically be hidden.
$hide_files = "skin.xml,wimpy.swf,playlister.swf";
//
// Media types
// The files with the following extensions will be displayed in the playlist
$media_types = "mp3";
//
// Playlister output directory name
// Specify the name of the folder you would like your XML playlists to be saved into.
$playlisterOutputDirName = "wimpyPlaylister_output";
//
// Default file name for Playlister.
// This s the default name that Playlister will display when you save the XML playlist
$defaultPlaylistFilename = "wimpyPlaylist.xml";
//
/////////////////////////////////////////////////////////////////
//                                                             //
//     OPTIONS                                                 //
//     Use thse configuration options to alter the             //
//     behaviour ONLY IF you do not use a seperate             //
//     HTML page to display Wimpy. These options only          //
//     control the "default" page that is displayed            //
//     when you access this file (wimpy.php).                  //
//                                                             //
/////////////////////////////////////////////////////////////////
//
// Start on track number
// If you want wimpy to start playing a specific track in the playlist, 
// define the playlist item here. The track number is not actually listed 
// in the playlist, so you will have to count down the playlist to detmine 
// the proper number. If there is a sub-directory or XML playlist listed in 
// the playlist, do not use them when counting down the playlist.
// for example, if your playlist looks like:
// > sub-dir 1
// > sub-dir 2
// > sub-dir 3
// || file 1
// || file 2
// || file 3
// And you wanted to start playing on "file 2", 
// then set startOnTrack to "1" - since counting actually starts on "0"
//
//$startOnTrack = "3";
//
// Background Color:
// background color for the HTML page that the default wimpy is loaded into)
//$background_color = "6A7A95";
// IMPORTANT: do not use a # in your HEX color... just put the value!
$background_color = '333333';
// 
// HTML page title 
// The title that appears on the top of a users browser wimdow.
$wimpyHTMLpageTitle = "Dans Music";
//
// Download Button:
// Should wimpy show the download arrow on the right hand side?
$displayDownloadButton = "yes";
//
// Force download
// If this is set to yes, then after the user clicks 
// the download icon a "save as" dialog box will appear. 
// If this is set to "no" then when the user clicks 
// the download icon, the file will open in a new window 
// and the default application will handle the the file. 
// For example, if the user clicks "download", 
// and the user has winamp or another mp3 player on 
// their machine, then the mp3 file will open in 
// winamp (or musicmatch or similiar)
//
// The reason this is set to "no" by default is that not 
// every browser behaves the same, so if this is set to 
// "yes", some people may not get then "save as" dialog  
// window., however most people will not have a problem. 
$forceDownload = "yes";
//
// Info display Speed:
// how fast should the currently playing song switch between the artist and the title of the song:
// (lower numbers will switch faster)
$infoDisplayTime = "3";
//
// Random Selection on Launch:
// upon launch should wimpy select the first song randomly? (remaining songs will play in order).
$defaultPlayRandom = "yes";
//$defaultPlayRandom = "no";
//
// Random Playback
// set the default button position for the random playback button:
//$randomPlayback = "yes";
$randomPlayback = "no";
//
// Random Button State
// If you have decided to randomly select a track when 
// the player starts up, after the first track is complete, 
// the remainder of the songs will playback in the normal, orderly way.
//
// If you want the player to continue selecting tracks randomly, 
// then you can set the random button state to "on" and subsequent 
// tracks will be selected randomly. You can set the state of the 
// player to continue selecting the "next" track randomly. 
//$randomButtonState = "on";
$randomButtonState = "off";
//
// Start Playing Immediately
//$startPlayingOnload = "no";
$startPlayingOnload = "yes";
//
// Show pop up help by default
// $popUpHelp = "no";
$popUpHelp = "yes";
//
// Display embedded id3 info or file name.
// if you want to show id3 info set this to "yes"
// If you just want to show a psuedo filename 
// (same name, but no ".mp3") set this to "no"
//
// In order to present ID3 information you must upload the getid3
// library to your wimpy folder. The files can be found in the
// "goodies" folder or downloaded from the following location:
// http://www.wimpyplayer.com/resources
// Please upload all of the getid3 files to the same location as wimpy.php
// Here is a list of the files:
//    - getid3.php
//    - getid3.lib.php
//    - module.audio.mp3.php
//    - module.tag.id3v1.php
//    - module.tag.id3v2.php
//
// $getMyid3info = "yes";
$getMyid3info = "no";
//
// Startup volume. Set this to the percentage of volume you would like wimpy to start up with.
// Example: 100 would mean full volume
//          50 would mean half volume
//          0 would mean "mute"
$theVolume = "100";
//
// Buffer Audio. Set this to the percentage of volume you would like wimpy to start up with.
// This will cause the player to wait a certain number of seconds before each track plays... 
// allowing low-bandwidth users to have a nicer listening experience. 
// Set this to a number of seconds.
$bufferAudio = 1;
//
/////////////////////////////////////////////////////////////////
//                                                             //
//                     Ecommerce Setup:                        //
//                                                             //
/////////////////////////////////////////////////////////////////
// To use ecommerce, you will need to upload the getid3 library to your wimpy folder.
// The URL to each mp3s shopping cart item should be encoded into the "comments" field 
// of your mp3 file. Or the "comments" tag of the playlist.xml file.
//$ecommerce = "yes";
$ecommerce = "no";
// 
// If you would like to have the ecommerce link go to a 
// page other than the default "_BLANK" then uncomment this 
// variable and enter the name of the window that the link 
// should go to.
// NOTE: Setting this to "replace" will behave like a regular window and 
// replace the page that contains wimpy (and wimpy) with the URL to 
// the ecommerce link.
$ecomWindow = "_BLANK";
// ecomWindow = "replace"
//
/////////////////////////////////////////////////////////////////
//                                                             //
//                        Visual Setup:                        //
//                                                             //
/////////////////////////////////////////////////////////////////
//
// You can use either JPG images or SWF graphics in wimpy.
// 
//
// 1. Using a default graphic for each folder:
// 
// Upload a JPG or SWF named "coverart.jpg/swf" into each folder / subfolder - 
// wimpy will load it up as the "default" image for that folder
//
// By default the standardized "cover art" graphic is set to use the filename of:
// "coverart.jpg" - so in each directory and subdirectory (including your wimpy 
// "root" directory, upload a file named coverart.jpg. Example:
// www.yoursite.com/mp3s/coverart.jpg
// www.yoursite.com/mp3s/subdir1/coverart.jpg
// www.yoursite.com/mp3s/subdir2/coverart.jpg
// www.yoursite.com/mp3s/subdir3/coverart.jpg
// www.yoursite.com/mp3s/subdir3/subsub1/coverart.jpg
// www.yoursite.com/mp3s/subdir3/subsub2/coverart.jpg
// www.yoursite.com/mp3s/subdir3/subsub3/coverart.jpg
//
// This is the standardized filenameing convention... (or "base name" for you graphic)
// each directory and sub-directory should include a file with this name
$defaultVisualName = "coverart";
//
// 2. If you want to have an individual image for each song, 
// then create a jpg/swf image with the same filename then upload the image to 
// the same folder as your mp3 file. So for example if you have a song named 
// "mytune.mp3" then add a "mysong.jpg" to the same folder
//
//
// You may ONLY use: swf OR jpg. All other kinds of graphics will not work
//$defaultVisualExt = "swf";
$defaultVisualExt = "jpg";
//
// If you are using wimpy to display non-wetern characters 
// (such as German, French or any Asian characters), 
// then set this option to "yes"
$useSysCodePage = "no";
//
// Serve MP# this option prevents the MP# from being blocked by a 
// firewall and being cached on the users local machine
$serveMP3 = "no";
//
// Auto Advance
$autoAdvance = "no";
//
// Track Plays
// Track plays will gather information about every song that is 
// played through the player and sublmit the info to wimpy_trackplays.php.
//
// The player will return the URL of the file, the Artist 
// Name and the Title of the track to the variables listed in wimpy_trackplays.php. 
// It is up to you to author the remainder of the wimpy_trackplays.php to conform 
// to the way you would like to track the information.
//$trackPlays = "yes";
$trackPlays = "no";
//
//
/////////////////////////////////////////////////////////////////
//                                                             //
//                     TROUBLE SHOOTING:                       //
//                                                             //
/////////////////////////////////////////////////////////////////
// 
// this one will try and find "argv" from the defined variables
$troubleshoot1 = false;
//
//
/////////////////////////////////////////////////////////////////
//                                                             //
//         Do not edit anything below here unless              //
//          you really know what you are doing!                //
//                                                             //
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
//
$myDataSetup = "filename|artist|album|title|track|comments|genre|seconds|filesize|bitrate|visual|url";
if($ecommerce == "yes"){
$forceDownload = "no";
$displayDownloadButton = "yes";
$getMyid3info = "yes";
}
$ts = array();
$ts[0] = false;
strstr( PHP_OS, "WIN") ? $v77 = "\\" : $v77 = "/";
if(!@getcwd ()){
$v24['path']['physical'] = dirname(__FILE__);
} else {
$v24['path']['physical'] = getcwd ();
}
function f1(&$v26, $id, $var){
$v79 = array($var => $id);
$v26 = array_merge ($v26, $v79);
}
if($_SERVER['PHP_SELF']){
$v46 = FALSE;
$v98 = strtolower ($_SERVER["HTTP_USER_AGENT"]);
} else {
$v46 = TRUE;
if($troubleshoot1){
$_REQUEST = array();
$v74 = get_defined_vars();
$v0 = explode("&", $v74['argv'][0]);
for($i=0;$i<sizeof($v0);$i++){
$v1 = explode("=", $v0[$i]);
f1($_REQUEST, $v1[1], $v1[0]);
}
} else {
$v74 = get_defined_vars();
$_REQUEST = $v74;
}
$v98 = strtolower ($_REQUEST["HTTP_USER_AGENT"]);
}
if($v46){
$v61 = $HTTP_SERVER_VARS['PHP_SELF'];
} else {
$v61 = $_SERVER['PHP_SELF'];
}
$v14 = explode("/", $v61);
$v53 = array_pop($v14);
$v60 = implode("/", $v14);
if($v46){
$v24['path']['www'] = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$v60;
} else {
$v24['path']['www'] = "http://".$_SERVER['HTTP_HOST'].$v60;
}
$v73 = "";
function f7($theFile){
global $defaultVisualName, $defaultVisualExt, $v24, $v77;
$v16 = explode(".", $theFile);
array_pop($v16);
$v92 = urldecode($v24['path']['physical'].$v77.(implode(".", $v16).".".$defaultVisualExt));
if(is_file($v92)){
return (f5($v92));
} else {
return false;
}
}
function f6($v88){
global $getMyid3info,$v24, $v77, $v41;
$v30 = urldecode($v88);
if($getMyid3info == "yes"){
$v45 = $v41->analyze($v24['path']['physical'].$v77.$v30);
getid3_lib::CopyTagsToComments($v45);
} else {
$v45 = array();
}
$v68 = array();
if(sizeof($v45)>0){
$v68[0]=@$v45['comments']['artist'][0];
$v68[1]=@$v45['comments']['album'][0];
$v68[2]=@$v45['comments']['title'][0];
$v68[3]=@$v45['comments']['track'][0];
$v68[4]="/";
if(@strlen(@$v45['comments']['comment'][1])>@strlen(@$v45['comments']['comment'][0])){
if(@substr($v45['comments']['comment'][1],0,4) == "http"){
$v68[4]=@$v45['comments']['comment'][1];
}
} else {
if(@substr($v45['comments']['comment'][0],0,4) == "http"){
$v68[4]=@$v45['comments']['comment'][0];
}
}
//$v68[4]=@$v45['comments']['comment'][0];
$v68[5]=@$v45['comments']['genre'][0];
$v68[6]=@$v45['playtime_seconds'];
$v68[7]=round(@$v45['filesize']/1000000, 2);
$v68[8]=round(@$v45['audio']['bitrate']/1000);
} else {
return 0;
break;
}
return $v68;
}
function f12($v59){
global $v24;
if ( isset($_ENV['OS']) && preg_match('/window/i', $_ENV['OS']) ){
$v59 = $v24['path']['physical']."\\".preg_replace('/\//', '\\', $v59);
echo "$v59<p>";
}
return $v59;
}
function f0($v69, $v78="no"){
global $hide_files,$getMyid3info,$defaultVisualName,$defaultVisualExt,$v24,$v73,$v77,$v60,$ts,$hide_folders,$media_types,$v48,$myDataSetup;
if($v69 == $v24['path']['physical'] || $v78=="yes"){
$v70 = true;
} else {
$v70 = false;
}
$v42=opendir($v69);
$v4 = array ();
$v8 = array ();
$v12 = array ();
$v11 = array();
$v10 = explode(",",$hide_files);
$v11 = explode(",",$hide_folders);
$v13 = explode(",",$media_types);
$v34 = 0;
$v28 = $v24['path']['www'];
while (false !== ($v37 = readdir($v42))){
$v38 = f2($v37);
$ext = explode('.',$v37);
$v50 = strtolower($ext[sizeof($ext)-1]);
if(!in_array($v37,$v10)){
if($v37 != '.' && $v37 != '..' && @sizeof($ext)>1 && in_array($v50,$v13)){
if($ts[0]){
echo "$v37<br>";
}
if($v50 == "xml" && stristr(strtolower($v37), "skin")){
$v35 = "";
} else {
$v8[count($v8)]=f2($v37);
}
} else {
if($v37 != '.' && $v37 != '..'){
if(!in_array($v37,$v11)){
if (false !== ($v29 = @opendir($v69.$v77.$v37))){
if($ts[0]){
echo "$v37<br>";
}
$v4[count($v4)] = f2($v37);
}
@closedir($v69.$v77.$v37);
}
}
}
}
}
closedir($v42); 
natcasesort($v4);
natcasesort($v8);
$v5 = array_values($v4);
$v9 = array_values($v8);
for($i=0;$i<sizeof($v5);$i++){
$v80 = $v5[$i];
if($v70){
$v5[$i]="$v80"."|d|||";
} else {
$v5[$i]="$v69/$v80"."|d|||";
}
$v34++;
}
for($i=0;$i<sizeof($v9);$i++){
$v80 = $v9[$i];
if($v70){
$v9[$i]=$v24['path']['www']."/$v80|".f10 ($v80, "full");
} else {
$v9[$i]=$v24['path']['www']."/$v69/$v80|".f10 ($v69.$v77.$v80, "full");
}
}
if($v48 == "mysql"){
if(sizeof($v9)){
for($i=0;$i<sizeof($v9); $i++){
array_push ($v12, f3($v9[$i]));
}
}
return $v12;
} else {
if(sizeof($v5)){
for($i=0;$i<sizeof($v5); $i++){
array_push ($v12, $v5[$i]);
}
}
if(sizeof($v9)){
for($i=0;$i<sizeof($v9); $i++){
array_push ($v12, $v9[$i]);
}
}
for($i=0;$i<sizeof($v12);$i++){
$v73 .= "&item".$i."=".f3($v12[$i]);
}
$v96 = sizeof ($v12);
$v105 = $v69.$v77.$defaultVisualName.".".$defaultVisualExt;
if (is_file($v105)){
$v104 = "&visualURL=".f5($v105);
} else {
$v104 = "";
}
$v73 .= "&totalitems=$v96".$v104;
$v73 .= "&datasetup=$myDataSetup";
if($ts[0]){
echo "<br><br>$v73";
exit;
}
return $v73;
}
}
function f11($v95){
global $v24, $v77;
$v16 = explode ("/", $v95);
$v86 = array_pop($v16);
$v22 = explode ("/", $v24['path']['www']);
$v18 = array_values (array_diff ($v16, $v22));
if($v18){
$v87 = $v77.implode($v77, $v18).$v77.$v86;
} else {
$v87 = implode($v77, $v18).$v77.$v86;
}
return ($v24['path']['physical'].$v87);
}
function f5($v91){
global $v24, $v77;
$v16 = explode ($v77, $v91);
$v86 = array_pop($v16);
$v21 = explode ($v77, $v24['path']['physical']);
$v18 = array_values (array_diff ($v16, $v21));
if($v18){
$v82 = "/".implode("/", $v18)."/".$v86;
} else {
$v82 = implode("/", $v18)."/".$v86;
}
return ($v24['path']['www']."$v82");
}
function f2($v94){
$v68 = $v94;
$v68 = str_replace(" ", "%20", $v68);
$v68 = str_replace("+", "%2B", $v68);
$v68 = str_replace("'", "%27", $v68);
$v68 = str_replace('"', "%22", $v68);
$v68 = str_replace("", "", $v68);
$v68 = str_replace("ä", "%C3%A4", $v68);
$v68 = str_replace("é", "%C3%A9", $v68);
$v68 = str_replace('^', "%5E", $v68);
$v68 = str_replace('`', "%60", $v68);
$v68 = str_replace('~', "%7E", $v68);
$v68 = str_replace('å', "%C3%A5", $v68);
$v68 = str_replace('ö', "%C3%B6", $v68);
$v68 = preg_replace("/(\r\n|\n|\r)/", "", $v68);
return $v68;
}
function f3($v94){
$v68 = $v94;
$v68 = str_replace("&", "%26", $v68);
$v68 = str_replace(" ", "%20", $v68);
$v68 = str_replace('?', "%3F", $v68);
return $v68;
}
function f10($v88, $v97=""){
global $v24,$v77,$v27,$ts,$getMyid3info,$mp3extension;
$v50 = explode(".", $v88);
if ($getMyid3info=="yes" && strtolower($v50[sizeof($v50)-1]) != "xml"){
$v68 = f6($v88);
} else {
$v68 = array();
$v68[0]="";
$v68[1]="";
$v68[2]="";
$v68[3]="";
$v68[4]="";
$v68[5]="";
$v68[6]="";
$v68[7]="";
$v68[8]="";
}
for($i=0;$i<sizeof($v68);$i++){
	//$v68[$i] = $v68[$i];
	$v68[$i] = utf8_encode($v68[$i]);
}
$v15 = explode($v77,$v88);
$v84 = $v15[sizeof($v15)-1];
$v17=explode('.',$v84);
$v85 = $v17[sizeof($v17)-2];
if($v68[0]=="" || $v68[0]==null){
$v68[0] = $v85;
}
if($v68[2]=="" || $v68[2]==null){
$v68[2] = $v85;
}
$v68[9] = f7($v88);
return (implode ("|", $v68));
}
function f13($v86, $v83, $v58='w+'){
global $v24;
if($v83=="" || is_null($v83)){
$v36 = " ";
} else {
$v36 = $v83;
}
$v68 = TRUE;
if (!$fp = fopen($v86, $v58)) {
$v68 = FALSE;
}
if($v68){
if (!$v40 = fwrite($fp, stripslashes($v36))) {
$v68 = FALSE;
exit;
} else {
$v68 = TRUE;
}
}
@fclose($fp);
return $v68;
@chmod ($v86, 0777);
}
function f4($v57, $v56, $v83){
copy($v57, $v56) && chmod($v56, 0777);
return f13($v56, $v83, $v58='w+');
}
function f8($v39, $v107){
global $v24, $v77, $playlisterOutputDirName;
$wimpySWFfilename = $v24['path']['www']."/".$_REQUEST['wimpySWFfilename'];
$background_color = $_REQUEST['background_color'];
$v63 = $_REQUEST['playerSize_value'];
$v65 = $_REQUEST['playerW'];
$v62 = $_REQUEST['playerH'];
if($v63 == "percent"){
$v65 = $v65."%";
$v62 = $v62."%";
}
$v67 = "";
$v67 .= "wimpyApp=".$v24['path']['www']."/".$v107."&";
$v67 .= "background_color=".$background_color."&";
$v67 .= "wimpyHTMLpageTitle=".$_REQUEST['wimpyHTMLpageTitle']."&";
$v67 .= "displayDownloadButton=".$_REQUEST['displayDownloadButton']."&";
$v67 .= "infoDisplayTime=".$_REQUEST['infoDisplayTime']."&";
$v67 .= "defaultPlayRandom=".$_REQUEST['defaultPlayRandom']."&";
$v67 .= "startPlayingOnload=".$_REQUEST['startPlayingOnload']."&";
$v67 .= "popUpHelp=".$_REQUEST['popUpHelp'];
$v23 = '<HTML>'."\n";
$v23 .= '<HEAD>'."\n";
$v23 .= '<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">'."\n";
$v23 .= '<TITLE>'.$v107.'</TITLE>'."\n";
$v23 .= '</HEAD>'."\n";
$v23 .= '<BODY bgcolor="#'.$background_color.'" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">'."\n";
$v23 .= '<!-- Wimpy Player Code -->'."\n";
$v23 .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0" width="'.$v65.'" height="'.$v62.'">'."\n";
$v23 .= '<param name="movie" value="'.$wimpySWFfilename.'?'.$v67.'">'."\n";
$v23 .= '<param name="quality" value="high">'."\n";
$v23 .= '<param name="bgcolor" value=#'.$background_color.'>'."\n";
$v23 .= '<embed src="'.$wimpySWFfilename.'?'.$v67.'" width="'.$v65.'" height="'.$v62.'" quality="high" bgcolor=#'.$background_color.' pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></object>'."\n";
$v23 .= '<!-- End Wimpy Player Code -->'."\n";
$v23 .= '</BODY>'."\n";
$v23 .= '</HTML>'."\n";
$v72 = f13($v24['path']['physical'].$v77.$v39, $v23, 'w+');
return $v72;
}
$v2 = array(
"action", 
"theFile", 
"filename",
"dir",
"getMyid3info",
"useMysql",
"queryValue",
"queryWhere",
"forceDownload",
"defaultVisualExt"
);
for($i=0;$i<sizeof($v2);$i++){
$var = $v2[$i];
if(!isset($_REQUEST[$var])){
if(!isset($$var)){
$$var = "";
}
} else {
$$var = $_REQUEST[$var];
}
}
if($useMysql=="yes"){
$action = "getmysql";
}
function f9($v39){
global $v24, $v77, $playlisterOutputDirName,$_REQUEST;
$v12 = explode("^", $_REQUEST['items']);
$v3 = explode("|", $_REQUEST['datasetup']);
$v108 = "";
$v7 = array();
array_push($v7, '<'.urldecode("%3F").'xml version="1.0"'.urldecode("%3F").'>');
array_push($v7, '<playlist>');
for ($i=0; $i<sizeof($v12); $i++) {
array_push($v7, "\t".'<item>');
$v6 = explode("|", $v12[$i]);
for ($j=0; $j<sizeof($v3); $j++) {
$v31 = str_replace(" ", "%20", $v6[$j]);
$v31 = str_replace("'", "%27", $v31);
$v31 = str_replace('"', "%22", $v31);
$v31 = str_replace('#', "%23", $v31);
$v31 = str_replace('^', "%5E", $v31);
$v31 = str_replace('`', "%60", $v31);
$v31 = str_replace('~', "%7E", $v31);
array_push($v7, "\t"."\t".'<'.$v3[$j].'>'.$v31.'</'.$v3[$j].'>');
}
array_push($v7, "\t".'</item>');
}
array_push($v7, '</playlist>');
$v71 = implode("\r\n", $v7);
$v72 = f13 ($v24['path']['physical'].$v77.$v39, $v71, 'w+');
return $v72;
}
if($action=="trackPlays"){
require ("wimpy_trackplays.php");
exit;
}if($action=="getVersion"){
print "$wimpyVersion";
exit;
}else if($action=="getmysql"){
require ("wimpy_mysql_get.php");
exit;
}else if ($action=="serveMP3"){
$theFile = $_REQUEST['theFile'];
$v16 = explode ("/", $theFile);
$v86 = array_pop($v16);
$v22 = explode ("/", $v24['path']['www']);
$v18 = array_values (array_diff ($v16, $v22));
if($v18){
$v81 = $v24['path']['physical'].$v77.implode($v77, $v18).$v77.$v86;
} else {
$v81 = $v24['path']['physical'].implode($v77, $v18).$v77.$v86;
}
$fp = fopen($v81, 'rb');
header("Pragma: public");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
header("Content-Length: ".filesize($v81));
fpassthru($fp);
exit;
}else if ($action == "serveSkin"){
$fp = fopen($v24['path']['physical'].$v77.$wimpySkin, 'r');
header("Pragma: public");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: text/xml");
fpassthru($fp);
fclose($fp);
exit;
}else if($action == "updateMySQL"){
$v48 = "mysql";
$getMyid3info = "yes";
if(is_file('getid3.php')){
require_once ('getid3.php');
$v41 = new getid3;
} else if (is_file('getid3'.$v77.'getid3.php')){
require_once ('getid3'.$v77.'getid3.php');
$v41 = new getid3;
} else {
print 'You have elected to use ID3 information in the playlist.<br>'; 
print 'In order to present ID3 information you must upload the getid3<br>';
print 'library to your wimpy folder. The files can be found in the <br>';
print '"goodies" folder or downloaded from wimpyplayer homepage<br>';
print 'Please upload all of the getid3 files to the same location as wimpy.php<br>';
exit;
}
print "<b>Checking the mp3 directory (mySQLmp3dir) specified in wimpy_mysql_conf.php configs</b>";
/*
if($mySQLmp3dir !== ""){
if(is_dir($mySQLmp3dir) !== TRUE){
if(@mkdir($v24['path']['physical'].$v77.$mySQLmp3dir, 0755) !== TRUE){
print "<br>... The following directory does not exist: $mySQLmp3dir";
print "<br>... WARNING: Unable to create directory.";
print "<br>... Set permissions on: ".$v24['path']['physical']. " to 777 (read-write-all)";
print "<br>... Or create the directory manually.<br><br>";
} else {
print "<br>... Created the following directory for your mp3 files: $mySQLmp3dir<BR><BR>";
}
exit;
} else {
print "<br>... $mySQLmp3dir exists: $mySQLmp3dir <br><br>";
}
} else {
print "NOTE: Your mp3 files should go into the same directory as wimpy.php -- set the mySQLmp3dir variable (in wimpy_mysql_conf.php to a directory that exists in this folder.<br><br>";
}
$Asendback = f0($v24['path']['physical'].$v77.$mySQLmp3dir, "yes");
*/
$Asendback = f0($v24['path']['physical'], "yes");
require ("wimpy_mysql_update.php");
exit;
} else if($action == "makeplaylist"){
$v19 = explode(".", urldecode($_REQUEST['destination']));
$v66 = $v24['path']['physical'].$v77.$playlisterOutputDirName;
if (!is_dir($playlisterOutputDirName)){
if (!$v47 = @mkdir($v66, 0755)) {
$v25 = 0;
$v68 = "&retval=error&filename=$v66";
} else {
$v25 = 1;
}
} else {
$v25 = 1;
}
if($v25 == 1){
$v90 = $v19[0].".xml";
$v89 = $v19[0].".html";
$v68 = "ok";
if($v54 = f9($playlisterOutputDirName.$v77.$v90)){
$v68 = "ok";
} else {
$v68 = "error";
}
if($v68 == "ok"){
if($v51 = f8($playlisterOutputDirName.$v77.$v89, $playlisterOutputDirName."/".$v90)){
$v68 = "&retval=ok&filename=".$v24['path']['www']."/".$playlisterOutputDirName."/".$v89;
} else {
$v68 = "&retval=error&filename=$v89";
}
} else {
$v68 = "&retval=error&filename=$v89";
}
}
print $v68;
exit;
} else if ($action=="downloadfile"){
$v16 = explode ("/", $theFile);
$v86 = array_pop($v16);
$v22 = explode ("/", $v24['path']['www']);
$v18 = array_values (array_diff ($v16, $v22));
if($v18){
$v81 = $v24['path']['physical'].$v77.implode($v77, $v18).$v77.$v86;
} else {
$v81 = $v24['path']['physical'].implode($v77, $v18).$v77.$v86;
}
header("Pragma: public");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: private");
header("Content-Type: audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
if ((is_integer (strpos($v98, "msie"))) && (is_integer (strpos($v98, "win")))) {
   header( "Content-Disposition: attachment; filename=".basename($v81).";" );
} else {
   header( "Content-Disposition: attachment; filename=".basename($v81).";" );
}
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($v81));
readfile("$v81");
exit;
} else if($action=="getstartupdirlist"){
if($getMyid3info == "yes"){
if(is_file('getid3.php')){
require_once ('getid3.php');
$v41 = new getid3;
} else if (is_file('getid3'.$v77.'getid3.php')){
require_once ('getid3'.$v77.'getid3.php');
$v41 = new getid3;
} else {
$getMyid3info = "no";
}
}
$v73 = f0($v24['path']['physical']);
echo (f2($v73));
exit;
} else if ($action=="dir"){
if($getMyid3info == "yes"){
if(is_file('getid3.php')){
require_once ('getid3.php');
$v41 = new getid3;
} else if (is_file('getid3'.$v77.'getid3.php')){
require_once ('getid3'.$v77.'getid3.php');
$v41 = new getid3;
} else {
$getMyid3info = "no";
}
}
$v73 = f0(stripslashes($dir));
echo (f2 ($v73));
exit;
} else if ($action == "info"){
$v93 = f10($theFile, "full");
echo (f2($v73));
exit;
} else if ($action=="phpinfo"){
$v68 = phpinfo();
echo "$v68";
exit;
} else if ($action=="podcast"){
$v48 = "mysql";
$podBack = f0($v24['path']['physical'], "yes");
} else {
$v67 = "";
$v67 .= "wimpyApp=".$v53;
$v67 .= "&background_color=".$background_color;
$v67 .= "&infoDisplayTime=".$infoDisplayTime;
$v67 .= "&theVolume=".$theVolume;
$v67 .= "&defaultVisualName=".$defaultVisualName;
$v67 .= "&defaultVisualExt=".$defaultVisualExt;
if($defaultPlayRandom == "yes"){
$v67 .= "&defaultPlayRandom=yes"; 
}
if($startPlayingOnload == "yes"){
$v67 .= "&startPlayingOnload=yes"; 
}
if($popUpHelp == "yes"){
$v67 .= "&popUpHelp=yes"; 
}
if($randomPlayback == "yes"){
$v67 .= "&randomPlayback=yes"; 
}
if($randomButtonState == "on"){
$v67 .= "&randomButtonState=on"; 
}
if($bufferAudio>0){
$v67 .= "&bufferAudio=".$bufferAudio; 
}
if($autoAdvance == "yes"){
$v67 .= "&autoAdvance=yes"; 
}
if(isset($startOnTrack)){
$v67 .= "&startOnTrack=".$startOnTrack; 
}
if($useSysCodePage == "yes"){
$v67 .= "&useSysCodePage=yes"; 
}
if($serveMP3 == "yes"){
$v67 .= "&serveMP3=yes"; 
}
if($trackPlays == "yes"){
$v67 .= "&trackPlays=yes"; 
}
if($ecommerce == "yes"){
$v67 .= "&ecommerce=yes";
$v67 .= "&ecomWindow=$ecomWindow";
$v67 .= "&displayDownloadButton=yes";
$v67 .= "&forceDownload=yes";
$v67 .= "&getMyid3info=yes";
} else {
if($displayDownloadButton == "yes"){
$v67 .= "&displayDownloadButton=yes"; 
}
if($forceDownload == "yes"){
$v67 .= "&forceDownload=yes"; 
}
if($getMyid3info == "yes"){
$v67 .= "&getMyid3info=yes";
}
}
$wimpySWFfilename = $wimpySWFfilename."?".$v67;
$v73 .= '<HTML>'."\n";
$v73 .= '<HEAD>'."\n";
$v73 .= '<meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">'."\n";
$v73 .= '<TITLE>'.$wimpyHTMLpageTitle.'</TITLE>'."\n";
$v73 .= '</HEAD>'."\n";
$v73 .= '<BODY bgcolor="#'.$background_color.'" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">'."\n";
$v73 .= '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
$v73 .= ' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0" ';
$v73 .= ' WIDTH="100%" HEIGHT="100%" id="wimpy" ALIGN="center">'."\n";
$v73 .= ' <PARAM NAME="movie" VALUE="'.$wimpySWFfilename.'" />'."\n";
$v73 .= ' <param name="loop" value="false" />'."\n";
$v73 .= ' <param name="menu" value="false" />'."\n";
$v73 .= ' <param name="quality" value="high" />'."\n";
$v73 .= ' <EMBED src="'.$wimpySWFfilename.'" quality="high" bgcolor="#'.$background_color.'"  WIDTH="100%" HEIGHT="100%" id="wimpy" ALIGN="center" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>'."\n";
$v73 .= '</OBJECT>'."\n";
$v73 .= '</BODY>'."\n";
$v73 .= '</HTML>'."\n";
echo ($v73);
}
?>