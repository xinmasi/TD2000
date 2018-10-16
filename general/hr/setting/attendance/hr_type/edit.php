<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("设置考勤排班类型");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript">
function delete_dep(MANAGER_ID)
{
 msg='<?=_("确认要删除该项吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?MANAGER_ID=" + MANAGER_ID;
  window.location=URL;
 }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑考勤排班类型")?></span>
    </td>
  </tr>
</table>
<br><br>
<table class="TableBlock" width="70%" align="center" >
  <form action="update.php"  method="post" name="form1">  
   <tr>
      <td nowrap class="TableData">
<?
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_NAME=$ROW["DUTY_NAME"];
if($DUTY_TYPE=="99")
   $DUTY_NAME=_("轮班制");
echo $DUTY_NAME._("人员：");   
?> 	
   	 </td>
      <td class="TableData"> 
<?
$query1 = "select USER_ID from USER_EXT where DUTY_TYPE='$DUTY_TYPE'";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{
	$USER_ID.=$ROW1["USER_ID"].",";
}
$USER_NAME=td_trim(GetUserNameById($USER_ID));
echo $USER_NAME;
?>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=sprintf(_("增加").$DUTY_NAME._("人员："))?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
      <textarea cols="50" name="USER_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','USER_ID', 'USER_NAME')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a> 
    </td>
   </tr>
  <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$DUTY_TYPE?>" name="DUTY_TYPE">
      <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index.php'">
      </td>
    </td>
  </tr>
</form>
</table>
</body>
</html>