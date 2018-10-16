<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
if(!empty($_POST))
{
    foreach($_POST as $key => $value)
    {
        switch ($key)
        {
            case 'tempViewMode':
                $VIEW_MODE = $value;
                break;
            case 'tempCurrid':
                $CURRID = $value;
                break;
            case 'tempMediaUrl':
                $MEDIA_URL = $value;
                break;
            default:
                break;
        }
    }
}
//$MEDIA_URL = urldecode($MEDIA_URL);
$MEDIA_TYPE=is_media($MEDIA_NAME);
$MEDIA_URL_ARR = explode('@~@', $MEDIA_URL);
if($VIEW_MODE!="")
{
    if(count($MEDIA_URL_ARR) >= 10)
    {
        ?>
        <script>
            document.write('<form action="/inc/image_view.php" method="post" name="tempForm1">');
            document.write('<input type="hidden" name="VIEW_MODE" value="<?=$VIEW_MODE?>">');
            document.write('<input type="hidden" name="CURRID" value="<?=intval($CURRID)?>">');
            document.write('<input type="hidden" name="MEDIA_URL" value="<?=$MEDIA_URL?>">');
            document.write('<input type="hidden" name="DIRECT_VIEW" value="1">');
            document.write('</form>');
            document.tempForm1.submit();
        </script>
        <?
        exit;
    }
    else
    {
        header("location: /inc/image_view.php?VIEW_MODE=".$VIEW_MODE."&CURRID=".intval($CURRID)."&MEDIA_URL=".urlencode($MEDIA_URL)."&DIRECT_VIEW=1");
        exit;
    }
}

if($MEDIA_TYPE==3)
{
    if(substr(strtolower($MEDIA_NAME),-3)=="jpg"||substr(strtolower($MEDIA_NAME),-4)=="jpeg"||substr(strtolower($MEDIA_NAME),-3)=="bmp"||substr(strtolower($MEDIA_NAME),-3)=="gif"||substr(strtolower($MEDIA_NAME),-3)=="png")
    {
        header("location: /inc/image_view.php?MEDIA_URL=".urlencode($MEDIA_URL."&DIRECT_VIEW=1"));
        exit;
    }
    else
    {
        header("location:".$MEDIA_URL."&DIRECT_VIEW=1");
        exit;
    }

}

if(substr(strtolower($MEDIA_NAME),-3)=="mp4"||substr(strtolower($MEDIA_NAME),-3)=="mpg"||substr(strtolower($MEDIA_NAME),-4)=="mpeg"||substr(strtolower($MEDIA_NAME),-3)=="avi"||substr(strtolower($MEDIA_NAME),-3)=="wmv"||substr(strtolower($MEDIA_NAME),-3)=="asf"||substr(strtolower($MEDIA_NAME),-3)=="dat")
    $VIDEO=1;
else
    $VIDEO=0;

$HTML_PAGE_TITLE = _("媒体播放器");
include_once("inc/header.inc.php");
?>
<body topmargin="0" leftmargin="0" rightmargin="0" scroll="no">
<style type="text/css">
    html, body{height: 100%;}
</style>
<table border=0 align="center" class="small" cellspacing="0" cellpadding="3" width="100%" height="100%"  id="content-td">
    <tr class="TableHeader" height=30>
        <td>
            <b><?=_("播放文件：")?><?=td_htmlspecialchars($MEDIA_NAME)?></b>
        </td>
    </tr>
    <tr class="TableContent" height=20>
        <td><b><?=_("下载文件：")?></b><a href="<?=$MEDIA_URL?>"><?=td_htmlspecialchars($MEDIA_NAME)?></a></td>
    </tr>
    <tr>
        <td>
            <?
            if($MEDIA_TYPE==2)
            {
            if(!$VIDEO)
            {
                ?>
                <object id="mplayer" width="100%" height="68" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
                        codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715"
                        align="baseline" border="0" standby="Loading Microsoft Windows Media Player components..."
                        type="application/x-oleobject">
                    <param name="FileName" value="<?=$MEDIA_URL?>">
                    <param name="ShowControls" value="1">
                    <param name="ShowPositionControls" value="0">
                    <param name="ShowAudioControls" value="1">
                    <param name="ShowTracker" value="1">
                    <param name="ShowDisplay" value="0">
                    <param name="ShowStatusBar" value="1">
                    <param name="AutoSize" value="0">
                    <param name="ShowGotoBar" value="0">
                    <param name="ShowCaptioning" value="0">
                    <param name="AutoStart" value="1">
                    <param name="PlayCount" value="0">
                    <param name="AnimationAtStart" value="0">
                    <param name="TransparentAtStart" value="0">
                    <param name="AllowScan" value="0">
                    <param name="EnableContextMenu" value="1">
                    <param name="ClickToPlay" value="0">
                    <param name="InvokeURLs" value="1">
                    <param name="DefaultFrame" value="datawindow">
                </object>
                <script>
                    <?
                    if($FILE_NAME=="")
                        $HEIGHT="205";
                    else
                        $HEIGHT="300";
                    ?>
                    self.resizeTo(600,<?=$HEIGHT?>);
                </script>
            <?
            }
            else
            {
            ?>
                <object classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6" id="phx" width="100%">
                    <param name="URL" value="<?=$MEDIA_URL?>">
                    <param name="rate" value="1">
                    <param name="balance" value="0">
                    <param name="currentPosition" value="0">
                    <param name="defaultFrame" value>
                    <param name="playCount" value="1">
                    <param name="autoStart" value="-1">
                    <param name="currentMarker" value="0">
                    <param name="invokeURLs" value="-1">
                    <param name="baseURL" value>
                    <param name="volume" value="50">
                    <param name="mute" value="0">
                    <param name="uiMode" value="full">
                    <param name="stretchToFit" value="0">
                    <param name="windowlessVideo" value="0">
                    <param name="enabled" value="-1">
                    <param name="enableContextMenu" value="-1">
                    <param name="fullScreen" value="0">
                    <param name="SAMIStyle" value>
                    <param name="SAMILang" value>
                    <param name="SAMIFilename" value>
                    <param name="captioningID" value>
                    <param name="enableErrorDialogs" value="0">
                    <param name="_cx" value="6482">
                    <param name="_cy" value="6350">
                </object>
                <script>
                    self.resizeTo(800,600);
                </script>
            <?
            }
            }
            else if($MEDIA_TYPE==4)
            {
            ?>
                <object id='flvplayer' type='application/x-shockwave-flash' width='480' height='360' align='middle'>
                    <param name='allowScriptAccess' value='sameDomain' />
                    <param name='movie' value='/module/mediaplayer/flvplayer.swf?vcastr_file=<?=urlencode($MEDIA_URL)?>&IsAutoPlay=true'/>
                    <param name='quality' value='best' />
                    <param name='bgcolor' value='#ffffff' />
                    <param name='scale' value='noScale' />
                    <param name='wmode' value='window' />
                    <param name='allowFullScreen' value='true' />
                    <param name='salign' value='TL' />
                </object>
                <?
            }
            else
            {
                ?>
                <OBJECT ID=video1 CLASSID="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" HEIGHT=100% WIDTH=100%>
                    <param name="AUTOSTART" value="1">
                    <param name="SHUFFLE" value="0">
                    <param name="PREFETCH" value="0">
                    <param name="NOLABELS" value="0">
                    <param name="SRC" value="<?=$MEDIA_URL?>">
                    <param name="CONTROLS" value="ImageWindow,StatusBar,ControlPanel">
                    <param name="CONSOLE" value="Clip2">
                    <param name="LOOP" value="0">
                    <param name="NUMLOOP" value="0">
                    <param name="CENTER" value="0">
                    <param name="MAINTAINASPECT" value="0">
                    <param name="BACKGROUNDCOLOR" value="#000000">
                </OBJECT>
                <?
            }
            ?>
        </td>
    </tr>
</table>
<script type="text/javascript">
    function setVideoHieght(){
        var td = document.body.clientHeight;
        document.getElementById("phx").style.height = (td - 50 - 5) + "px";
    }

    window.onresize = window.onload = function(){
        setVideoHieght();
    }

</script>
</BODY>
</HTML>
