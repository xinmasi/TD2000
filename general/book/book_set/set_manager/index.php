<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置管理员");
include_once("inc/header.inc.php");
?>

<script type="text/javascript">
function delete_alert(AUTO_ID)
{
    msg='<?=_("确认要删除该管理员吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?AUTO_ID="+AUTO_ID;
        window.location=URL;
    }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("设置管理员")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("设置管理员")?>" class="BigButton" onClick="location='new.php';" title="<?=_("设置管理员")?>">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理管理员")?></span>
    </td>
  </tr>
</table>
<br>
<div align="center">
<?
$query = "SELECT * from BOOK_MANAGER";
$cursor= exequery(TD::conn(),$query);
$MANAGER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	$MANAGER_COUNT++;

  if($MANAGER_COUNT==1) 
  {  
?>
<table class="TableList"  width="600"  align="center" >
 <tr class="TableHeader">
    <td nowrap align="center"><?=_("管理员")?></td>
    <td nowrap align="center"><?=_("所管部门")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
 </tr>
<? 
  }

  $AUTO_ID=$ROW["AUTO_ID"];
  $MANAGER_ID=$ROW["MANAGER_ID"];
  $MANAGE_DEPT_ID=$ROW["MANAGE_DEPT_ID"]; 
    
	$query1="select USER_ID,USER_NAME from USER";
  $cursor1= exequery(TD::conn(),$query1);
  $USER_NAME_STR="";
  while($ROW=mysql_fetch_array($cursor1))
  {
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    if(find_id($MANAGER_ID,$USER_ID))
       $USER_NAME_STR.=$USER_NAME._("，");
  }

	$query1="select DEPT_ID,DEPT_NAME from DEPARTMENT";
  $cursor1= exequery(TD::conn(),$query1);
  $TO_NAME="";
  while($ROW=mysql_fetch_array($cursor1))
  {
    $DEPT_ID=$ROW["DEPT_ID"];
    $DEPT_NAME=$ROW["DEPT_NAME"];
    if(find_id($MANAGE_DEPT_ID,$DEPT_ID))
       $TO_NAME.=$DEPT_NAME._("，");
  }
  if($MANAGE_DEPT_ID=="ALL_DEPT")
     $TO_NAME=_("全体部门");
      
  if($MANAGER_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
    <td align="left" width="50%"><?=$USER_NAME_STR?></td>
    <td align="left" width="50%"><?=$TO_NAME?></td>
    <td align="left" width="50%" nowrap>
    	<a href="new.php?AUTO_ID=<?=$AUTO_ID?>"><?=_("编辑")?></a>&nbsp;&nbsp;<a href="javascript:delete_alert('<?=$AUTO_ID?>');"><?=_("删除")?></a>    	 
    </td>
  </tr>
<?
}
?>
</table>
</div>
<?
if($MANAGER_COUNT==0)
   Message("",_("没有管理员记录"));
?>
</body>
</html>