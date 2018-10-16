<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("互动会议");
include_once("inc/header.inc.php");
?>
<BODY class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/netchat.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("互动会议")?></span><br>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="500" align="center">
<tr>
   <td width="50%" class="TableData">
     <iframe name="userframe" scrolling="yes" noresize src="user.php" width="100%" frameborder="0"></iframe>
   </td>
   <td width="50%" class="TableData" >
     <OBJECT id=NetMeeting codeBase=xmsconf.tgz classid=CLSID:3E9BAF2D-7A79-11d2-9334-0000F875AE17></OBJECT>
   </td>
</tr>
</table>

</BODY>
</HTML>