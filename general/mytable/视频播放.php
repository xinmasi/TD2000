<?
include_once("inc/utility_file.php");

//$MEDIA_TYPE==1   $MEDIA_REAL_TYPE="rm,rmvb,ram,ra,mpa,mpv,mps,m2v,m1v,mpe,mov,smi,";
//$MEDIA_TYPE==2   $MEDIA_MS_TYPE="wmv,asf,mp3,mpg,mpeg,mp4,avi,wmv,wma,wav,dat,swf,";
//$MEDIA_TYPE==4   $MEDIA_FLASH_TYPE="flv,fla,swf,";

$MODULE_FUNC_ID="";
$MODULE_DESC=_("视频播放");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'notify';

$VIDEO_FILE = '';
$INI_FILE = MYOA_ATTACH_PATH."mytable/video.ini";
if(file_exists($INI_FILE))
{
   $INI_ARRAY = parse_ini_file($INI_FILE);
   if(is_array($INI_ARRAY) && isset($INI_ARRAY['video_file']))
      $VIDEO_FILE = $INI_ARRAY['video_file'];
}

if($VIDEO_FILE != '' && file_exists($VIDEO_FILE))
{
   $MEDIA_NAME = basename($VIDEO_FILE);
   $MEDIA_TYPE=is_media($MEDIA_NAME);
   if($MEDIA_TYPE==2)
   {
      if(substr(strtolower($MEDIA_NAME),-3)=="mp4"||substr(strtolower($MEDIA_NAME),-3)=="mpg"||substr(strtolower($MEDIA_NAME),-4)=="mpeg"||substr(strtolower($MEDIA_NAME),-3)=="avi"||substr(strtolower($MEDIA_NAME),-3)=="wmv"||substr(strtolower($MEDIA_NAME),-3)=="asf"||substr(strtolower($MEDIA_NAME),-3)=="dat")
         $VIDEO=1;
      else
         $VIDEO=0;
      
      if(!$VIDEO)
      {
         $MODULE_BODY.= "
         <object id=\"mplayer\" width=\"100%\" height=\"68\" classid=\"CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95\"
         codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715\"
         align=\"baseline\" border=\"0\" standby=\"Loading Microsoft Windows Media Player components...\"
         type=\"application/x-oleobject\">
           <param name=\"FileName\" value=\"video_file.php\">
           <param name=\"ShowControls\" value=\"1\">
           <param name=\"ShowPositionControls\" value=\"0\">
           <param name=\"ShowAudioControls\" value=\"1\">
           <param name=\"ShowTracker\" value=\"1\">
           <param name=\"ShowDisplay\" value=\"0\">
           <param name=\"ShowStatusBar\" value=\"1\">
           <param name=\"AutoSize\" value=\"0\">
           <param name=\"ShowGotoBar\" value=\"0\">
           <param name=\"ShowCaptioning\" value=\"0\">
           <param name=\"AutoStart\" value=\"1\">
           <param name=\"PlayCount\" value=\"0\">
           <param name=\"AnimationAtStart\" value=\"0\">
           <param name=\"TransparentAtStart\" value=\"0\">
           <param name=\"AllowScan\" value=\"0\">
           <param name=\"EnableContextMenu\" value=\"1\">
           <param name=\"ClickToPlay\" value=\"0\">
           <param name=\"InvokeURLs\" value=\"1\">
           <param name=\"DefaultFrame\" value=\"datawindow\">
         </object>";
      }
      else
      {
         $MODULE_BODY.= "
         <object classid=\"clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6\" id=\"phx\" width=\"100%\" height=\"100%\">
         <param name=\"URL\" value=\"video_file.php\">
         <param name=\"rate\" value=\"1\">
         <param name=\"balance\" value=\"0\">
         <param name=\"currentPosition\" value=\"0\">
         <param name=\"defaultFrame\" value>
         <param name=\"playCount\" value=\"1\">
         <param name=\"autoStart\" value=\"-1\">
         <param name=\"currentMarker\" value=\"0\">
         <param name=\"invokeURLs\" value=\"-1\">
         <param name=\"baseURL\" value>
         <param name=\"volume\" value=\"50\">
         <param name=\"mute\" value=\"0\">
         <param name=\"uiMode\" value=\"full\">
         <param name=\"stretchToFit\" value=\"0\">
         <param name=\"windowlessVideo\" value=\"0\">
         <param name=\"enabled\" value=\"-1\">
         <param name=\"enableContextMenu\" value=\"-1\">
         <param name=\"fullScreen\" value=\"1\">
         <param name=\"SAMIStyle\" value>
         <param name=\"SAMILang\" value>
         <param name=\"SAMIFilename\" value>
         <param name=\"captioningID\" value>
         <param name=\"enableErrorDialogs\" value=\"0\">
         <param name=\"_cx\" value=\"6482\">
         <param name=\"_cy\" value=\"6350\">
         </object>
         ";
      }
   }
   else if($MEDIA_TYPE==4)
   {
      $MODULE_BODY.= "
      <object id='flvplayer' type='application/x-shockwave-flash' width='480' height='360' align='middle'>
      <param name='allowScriptAccess' value='sameDomain' />
      <param name='movie' value='/module/mediaplayer/flvplayer.swf?vcastr_file=http://".$_SERVER["HTTP_HOST"]."/general/mytable/intel_view/video_file.php&IsAutoPlay=true'/>
      <param name='quality' value='best' />
      <param name='bgcolor' value='#ffffff' />
      <param name='scale' value='noScale' />
      <param name='wmode' value='window' />
      <param name='allowFullScreen' value='true' />
      <param name='salign' value='TL' />
      </object>";
   }
   else if($MEDIA_TYPE==1)
   {
      $MODULE_BODY.= "
      <OBJECT ID=video1 CLASSID=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\" HEIGHT=100% WIDTH=100%>
      <param name=\"AUTOSTART\" value=\"0\">
      <param name=\"SHUFFLE\" value=\"0\">
      <param name=\"PREFETCH\" value=\"0\">
      <param name=\"NOLABELS\" value=\"0\">
      <param name=\"SRC\" value=\"video_file.php\">
      <param name=\"CONTROLS\" value=\"ImageWindow,StatusBar,ControlPanel\">
      <param name=\"CONSOLE\" value=\"Clip2\">
      <param name=\"LOOP\" value=\"0\">
      <param name=\"NUMLOOP\" value=\"0\">
      <param name=\"CENTER\" value=\"0\">
      <param name=\"MAINTAINASPECT\" value=\"0\">
      <param name=\"BACKGROUNDCOLOR\" value=\"#000000\">
      </OBJECT>";
   }
}
else
   $MODULE_BODY.= sprintf(_("修改文件：%s 中的视频文件路径，如：%s"), str_replace("/", "\\", MYOA_ATTACH_PATH)."mytable\\video.ini", "video_file=E:\\video\\file.wmv");
?>