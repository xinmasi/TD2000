<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("会议修改");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<form enctype="multipart/form-data" action="modify_submit.php" method="post" name="form1">

<?
$query = "SELECT * from MEETING  where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $M_END=$ROW["M_END"];
   $M_ATTENDEE_NOT=$ROW["M_ATTENDEE_NOT"];
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" height="18" width="20"><span class="big3"> <?=_("修改")?> </span>
    </td>
  </tr>
</table><br>
<table align="center" class="TableBlock" width="300">
<tr>
 <td nowrap class="TableContent" width="70"> <?=_("结束时间：")?></td>
  <td class="TableData">
    <input type="text" name="M_END" size="18" maxlength="19" class="BigInput" value="<?=$M_END?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
  
  </td>
</tr>
<tr>
 <td nowrap class="TableContent" width="70"> <?=_("缺席人员：")?></td>
  <td class="TableData">
   <textarea name="M_ATTENDEE_NOT" class="BigInput" cols="32" rows="5"><?=$M_ATTENDEE_NOT?></textarea>
 </td>
</tr>
<tr class="TableControl">
 <td nowrap colspan="4" align="center">
 	<input type="hidden" value="<?=$M_ID?>" name="M_ID">
  <input type="submit" value="<?=_("保存")?>" class="BigButton">
 </td>
  </tr>
</table>
</form>
</body>
</html>