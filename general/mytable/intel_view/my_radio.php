<?
include_once("inc/auth.inc.php");
if($IS_TV==1)
   $height="349";   
else
   $height="69";

$HTML_PAGE_TITLE = _("¹ã²¥µçÊÓ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$DESC_STR?></span><br>
    </td>
    </tr>
</table>

<table width="99%" class=small border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td colspan=4>
    <OBJECT id="RadioMediaPlayer" classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" height="<?=$height?>" width="100%" STANDBY="Loading Windows Media Player components..."
    CODEBASE="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" type="application/x-oleobject">
     <param name="AutoRewind" value=1>
     <param name="FileName" value="<?=$MY_URL?>">
     <param name="ShowControls" value="1">
     <param name="ShowPositionControls" value="0">
     <param name="ShowAudioControls" value="1">
     <param name="ShowTracker" value="1">
     <param name="ShowDisplay" value="0">
     <param name="ShowStatusBar" value="1">
     <param name="ShowGotoBar" value="0">
     <param name="ShowCaptioning" value="0">
     <param name="AutoStart" value=1>
     <param name="Volume" value="-2500">
     <param name="AnimationAtStart" value="0">
     <param name="TransparentAtStart" value="0">
     <param name="AllowChangeDisplaySize" value="0">
     <param name="AllowScan" value="0">
     <param name="EnableContextMenu" value="1">
     <param name="ClickToPlay" value="0">
   </OBJECT>
  </td>
  </tr>
</table>
</body>
</html>
