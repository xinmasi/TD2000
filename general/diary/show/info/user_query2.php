<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("员工日志查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">

<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();" >
<?
	$BEGIN_DATE=date("Y-m-01",time());
	$CUR_DATE=date("Y-m-d",time());
?>
<div class="PageHeader"></div>
<form action="search.php" name="form1">
<table class="TableTop" width="80%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("日志查询")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="80%">
      <tr class="TableData">
      <td nowrap class="TableData"><?=_("工作日志表：")?></td>
      <td>
         <input type="radio" name="DIARY_COPY_TIME" value="" id="DIARY_COPY_TIME" checked><label for="DIARY_COPY_TIME"><?=_("当前日志表")?></label>&nbsp;
<?
$query = "show tables from ".TD::$_arr_db_master['db_archive']." like 'DIARY_COMMENT_REPLY_%'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $TABLE_NAME=substr($ROW[0], 20);
?>
        <input type="radio" name="DIARY_COPY_TIME" value="_<?=$TABLE_NAME?>" id="DIARY_COPY_TIME_<?=$TABLE_NAME?>"><label for="DIARY_COPY_TIME_<?=$TABLE_NAME?>"><?=$TABLE_NAME?></label>&nbsp;
<?
}
?>
      </td>
    </tr>
  <tr>
    <td nowrap class="TableData"><?=_("起始日期：")?></td>
    <td class="TableData"><input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
        
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("截止日期：")?></td>
    <td class="TableData"><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">

    </td>
  </tr>
    <tr>
      <td nowrap class="TableData"><?=_("标题：")?></td>
      <td class="TableData"><input type="text" name="SUBJECT" class="BigInput" size="40"></td>
    </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词1：")?></td>
    <td class="TableData"><input type="text" name="KEY1" class="BigInput" size="40"></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词2：")?></td>
    <td class="TableData"><input type="text" name="KEY2" class="BigInput" size="40"></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词3：")?></td>
    <td class="TableData"><input type="text" name="KEY3" class="BigInput" size="40"></td>
  </tr>
  <tr >
    <td nowrap class="TableControl" colspan="2" align="center">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
        <input type="hidden" name="USER_NAME" value="<?=$USER_NAME?>">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button">
        <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='user_diary.php?USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>';">
    </td>
  </tr>
</table>
</form>

</body>
</html>