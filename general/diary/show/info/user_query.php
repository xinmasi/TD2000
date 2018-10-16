<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("员工日志查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function export_word()
{
	document.form1.action="export2.php";
	document.form1.submit();
	document.form1.action="user_search.php";
}
</script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">

<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();" >
<div class="PageHeader"></div>
<table class="TableTop" width="80%">
   <tr>
      <td class="left"></td>
      <td class="center subject"> <?=_("员工日志查询")?></td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="80%">
<form action="user_search.php" name="form1" method="post">
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
    <td nowrap class="TableData"><?=_("日志类型：")?></td>
    <td class="TableData">
      <select name="DIA_TYPE" class="BigSelect">
      	<option value=""><?=_("所有类型")?></option>
<?
   $query="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='DIARY_TYPE' and CODE_NO!='2' order by CODE_ORDER";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
       $CODE_NO=$ROW["CODE_NO"];
       $CODE_NAME=$ROW["CODE_NAME"];
       $CODE_EXT=unserialize($ROW["CODE_EXT"]);
			 if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
				  $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
          	
?>  
        <option value="<?=$CODE_NO?>"><?=$CODE_NAME?></option>
<?
  }
?>    	

      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("日志标题：")?></td>
    <td class="TableData"><input type="text" name="SUBJECT" class="BigInput" size="40"></td>
  </tr> 
  <tr>
  	<td nowrap class="TableData"><?=_("日志作者：")?></td>
    <td class="TableData">
        <input type="hidden" name="TO_ID1" value="">
        <textarea cols=30 name="TO_NAME1" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('9', '4','TO_ID1', 'TO_NAME1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID1', 'TO_NAME1')"><?=_("清空")?></a>
    </td>
  </tr>
    <tr>
      <td nowrap class="TableData"><?=_("查询范围（部门）：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=30 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('4')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("查询范围（角色）：")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="">
        <textarea cols=30 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('4','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("查询范围（人员）：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPYS_TO_ID" value="">
        <textarea cols=30 name="COPYS_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('9', '4','COPYS_TO_ID', 'COPYS_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPYS_TO_ID', 'COPYS_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>  
  <tr >
    <td nowrap class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="export_word()" title="<?=_("导出word文件")?>">&nbsp;&nbsp;
    </td>
  </tr>
  </form>
</table>
</body>
</html>