<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("会议纪要");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
</script>
<script Language="JavaScript">
function InsertImage(src)
{
   AddImage2Editor('SUMMARY', src);
}

</script>


<body class="bodycolor" onsubmit="return CheckForm();">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" width="22" height="18"><span class="big3"> <?=_("会议纪要")?></span>
    </td>
  </tr>
</table>
<br>

<?
$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $M_NAME=$ROW["M_NAME"];
	 $SUMMARY=$ROW["SUMMARY"];
	 $READ_PEOPLE_ID=$ROW["READ_PEOPLE_ID"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID1"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME1"];
   $M_FACT_ATTENDEE=$ROW["M_FACT_ATTENDEE"];
}
$TOK=strtok($READ_PEOPLE_ID,",");
while($TOK!="")
{
  $query2 = "SELECT * from USER where USER_ID='$TOK'";
  $cursor2= exequery(TD::conn(),$query2);
  if($ROW=mysql_fetch_array($cursor2))
     $USER_NAME2.=$ROW["USER_NAME"].",";
  $TOK=strtok(",");
}

$TOK2=strtok($M_FACT_ATTENDEE,",");
while($TOK2!="")
{
  $query3 = "SELECT * from USER where USER_ID='$TOK2'";
  $cursor3= exequery(TD::conn(),$query3);
  if($ROW=mysql_fetch_array($cursor3))
     $M_FACT_ATTENDEE_NAME.=$ROW["USER_NAME"].",";
  $TOK2=strtok(",");
}
?>

<table class="TableBlock" width="100%" align="center">
<tr>
   <td nowrap class="TableContent" width="100"><?=_("会议名称：")?></td>
   <td class="TableData" colspan="3"><?=$M_NAME?></td>
</tr>
<tr>
   <td nowrap class="TableContent" width="100"><?=_("指定读者：")?></td>
   <td class="TableData" colspan="3"><?=$USER_NAME2?></td>
</tr>
<tr>
   <td nowrap class="TableContent" width="100"><?=_("实际参会人员：")?></td>
   <td class="TableData" colspan="3"><?=$M_FACT_ATTENDEE_NAME?></td>
</tr>
<tr>
   <td valign="top" nowrap class="TableContent" width="100"><?=_("纪要内容：")?></td>
   <td class="TableData" colspan="3"><?=$SUMMARY?></td>
</tr>
<?
if($ATTACHMENT_ID!="")
{
?>
<tr>
   <td class="TableContent"><?=_("附件文件")?>:</td><td class="TableData"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,0,0,0)?></td>
</tr>
<?
}
?>

<tr class="TableControl">
<td align="center" colspan="4">
  <input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close()">
</td>
</tr>
</table>
</body>
</html>
