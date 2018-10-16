<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("人力资源管理员设置");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<?
$DEPT_ID = intval($DEPT_ID);
$query = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DEPT_NAME=$ROW["DEPT_NAME"];

   $query1 = "select DEPT_HR_MANAGER,DEPT_HR_SPECIALIST from HR_MANAGER where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
	   $DEPT_HR_MANAGER    = $ROW1["DEPT_HR_MANAGER"];
	   $DEPT_HR_SPECIALIST = $ROW1["DEPT_HR_SPECIALIST"];
   }
        
      
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$DEPT_HR_MANAGER')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME.=$ROW1["USER_NAME"].","; 
	  
   $query2 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$DEPT_HR_SPECIALIST')";
   $cursor2= exequery(TD::conn(),$query2);
   while($ROW2=mysql_fetch_array($cursor2))
      $USER_NAME2.=$ROW2["USER_NAME"].",";
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("人力资源管理员设置")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="450" align="center" >
  <form action="add.php"  method="post" name="form1">  
   <tr>
    <td nowrap class="TableData"><?=_("部门名称：")?></td>
    <td nowrap class="TableData">
        <?=$DEPT_NAME?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("人力资源管理员：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$DEPT_HR_MANAGER?>">
        <textarea cols="22" name="COPY_TO_NAME" rows="5" class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("人事专员：")?></td>
      <td class="TableData">
        <input type="hidden" name="HR_SPECIALIST" value="<?=$DEPT_HR_SPECIALIST?>">
        <textarea cols="22" name="HR_SPECIALIST_NAME" rows="5" class="BigStatic" wrap="yes" readonly><?=$USER_NAME2?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','HR_SPECIALIST', 'HR_SPECIALIST_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('HR_SPECIALIST', 'HR_SPECIALIST_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$DEPT_ID?>" name="DEPT_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php'">
    </td>
  </form>
</table>

</body>
</html>