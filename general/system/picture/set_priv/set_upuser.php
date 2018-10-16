<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("设置上传权限");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<body class="bodycolor">
<?
$query = "select PRIV_STR from PICTURE where PIC_ID='$PIC_ID'";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $PRIV_STR=$ROW["PRIV_STR"];

   $PRIV_ARRAY=explode("|",$PRIV_STR);
   $PRIV_DEPT=$PRIV_ARRAY[0];
   $PRIV_ROLE=$PRIV_ARRAY[1];
   $PRIV_USER=$PRIV_ARRAY[2];
   $PRIV_DEPT=td_trim($PRIV_DEPT);
   if($PRIV_DEPT=="ALL_DEPT")
      $PRIV_DEPT_NAME=_("全体部门");
   else
   {
      
      
	    if($PRIV_DEPT!="")
	    {
	       $query1 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID in ($PRIV_DEPT)";
	       
         $cursor1= exequery(TD::conn(),$query1);
         while($ROW=mysql_fetch_array($cursor1))
            $PRIV_DEPT_NAME.=$ROW["DEPT_NAME"].",";
	    }		   
   }
   if(substr($PRIV_DEPT,-1)!=",")
      $PRIV_DEPT.=",";
   
   $PRIV_ROLE=td_trim($PRIV_ROLE);
  if($PRIV_ROLE!="")
  {
   $query1 = "SELECT PRIV_NAME from USER_PRIV where USER_PRIV in ($PRIV_ROLE)";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $PRIV_ROLE_NAME.=$ROW["PRIV_NAME"].",";
   }
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$PRIV_USER."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $PRIV_USER_NAME.=$ROW["USER_NAME"].",";
}
?>

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="small"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("设置上传权限")?></span>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock" width="80%" align="center" >
  <form action="up_submit.php"  method="post" name="form1">
   <tr>
      <td nowrap class="TableData"" align="center"><?=_("授权范围：")?><br><?=_("（部门）")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$PRIV_DEPT?>">
        <textarea cols=50 name=TO_NAME rows=4 class="BigStatic" wrap="yes" readonly><?=$PRIV_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"" align="center"><?=_("授权范围：")?><br><?=_("（角色）")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ROLE?>">
        <textarea cols=50 name="PRIV_NAME" rows=4 class="BigStatic" wrap="yes" readonly><?=$PRIV_ROLE_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"" align="center"><?=_("授权范围：")?><br><?=_("（人员）")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$PRIV_USER?>">
        <textarea cols=50 name="COPY_TO_NAME" rows=4 class="BigStatic" wrap="yes" readonly><?=$PRIV_USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('116','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      	<input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="parent.location='/general/system/picture/index.php?IS_MAIN=<?=$IS_MAIN?>'">
    </td>
  </form>
</table>
<script>
    var value = document.getElementsByName('PRIV_ID')[0].value;
    var last = value[value.length - 1];
    if(last !== ','){
        value = value + ',';
    }
    document.getElementsByName('PRIV_ID')[0].value = value;
</script>
</body>
</html>
