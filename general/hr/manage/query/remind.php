<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("离职人员");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<?
//------退休人员-----
$query = "SELECT HR_STAFF_INFO.USER_ID,USER.USER_NAME,HR_STAFF_INFO.STAFF_SEX,STAFF_BIRTH,HR_STAFF_INFO.DEPT_ID FROM HR_STAFF_INFO,USER WHERE USER.USER_ID=HR_STAFF_INFO.USER_ID AND USER.DEPT_ID=0";
$cursor= exequery(TD::conn(),$query);
$COUNT=mysql_num_rows($cursor);
if($COUNT>0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" HEIGHT="20"><span class="big3"> <?=_("离职人员")?></span><br>
    </td>
    <td valign="bottom" align="right"><span class="small1"><?=sprintf(_("共%s条信息"), '<span class="big4">'.$COUNT.'</span>')?></span>
    </td>
  </tr>
</table>
<table width="100%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("原属部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("出生日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
  while($ROW=mysql_fetch_array($cursor))
  {
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
	$DEPT_ID=$ROW["DEPT_ID"];
	$REASON=$ROW["REASON"];
    if($ROW["STAFF_SEX"]==0)
      $SEX_DESC=_("男");
    if($ROW["STAFF_SEX"]==1)
      $SEX_DESC=_("女");
    $HR_BIRTHDAY=$ROW["STAFF_BIRTH"];
    if($HR_BIRTHDAY=="0000-00-00")
       $HR_BIRTHDAY="";
    
    $DEPT_NAME=dept_long_name($DEPT_ID);
    if($DEPT_NAME=="")
	    $DEPT_NAME=_("未记录");
	
?>
 <tr class="TableData">
      <td><?=$DEPT_NAME?></td>
      <td align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$SEX_DESC?></td>
      <td align="center"><?=$HR_BIRTHDAY?></td>
      <td align="center">
        <a href="show_staff_info.php?USER_ID=<?=$USER_ID?>"> <?=_("详情")?></a>
      </td>
    </tr>
<?
  }//end while
?>
</table>
<?
}
else //$COUNT=0
{
?>
<div align="center">
<?
   Message("",_("本月无退休人员!"));
?>
</div>
<?
}
?>
</body>
</html>
