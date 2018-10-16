<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("会议纪要");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/address.gif" align="absmiddle" width="22" height="18"><span class="big3">  <?=_("会议纪要")?></span>
    </td>
  </tr>
</table>

<?
$M_ID=intval($M_ID);
$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SUMMARY=$ROW["SUMMARY"];
   $M_NAME=$ROW["M_NAME"];
}
?>

<table class="TableBlock" width="80%" align="center">
 <tr>
   <td class="TableHeader" align="center"><?=$M_NAME?> - <?=_("会议纪要")?></td>
   </td>
 </tr>
 <tr>
   <td class="TableData" colspan="2"  height="400" valign="top"><?=$SUMMARY?>
   </td>
 </tr>
 <tr>
   <td class="TableHeader" align="center">
    <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>">&nbsp;&nbsp;
   	<input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close()"></td>
   </td>
 </tr>
</table>
</body>
</html>