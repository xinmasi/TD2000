<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("设置维护权限");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<?
$query = "SELECT SUPPORT_DEPT,SUPPORT_USER from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $SUPPORT_DEPT=$ROW["SUPPORT_DEPT"];
  $SUPPORT_USER=$ROW["SUPPORT_USER"];  
}

$TO_NAME=GetDeptNameById($SUPPORT_DEPT);
if($SUPPORT_DEPT=="ALL_DEPT")
   $TO_NAME=_("全体部门");

$TOK=strtok($SUPPORT_USER,",");
while($TOK!="")
{
   $query1 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME.=$ROW["USER_NAME"].",";
   $TOK=strtok(",");
}
?>

<body class="bodycolor">
<form action="submit.php"  method="post" name="form1">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("设置维护权限")?></span></td>
  </tr>
</table>
<br>
<table class="TableBlock" width="450" align="center" >
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("分组名称：")?></td>
    <td class="TableData">
      <input type="text" name="GROUP_NAME" size="27" readonly maxlength="100" class="BigStatic" value="<?=$GROUP_NAME?>">
   </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("维护部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$SUPPORT_DEPT?>">
        <textarea cols=30 name=TO_NAME rows=4 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("维护人员：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$SUPPORT_USER?>">
        <textarea cols=30 name="COPY_TO_NAME" rows=4 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('107','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>   
</table><br>
<div align="center">
	<input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
  <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;	
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
</div>
  </form>
</body>
</html>