<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("招聘需求详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("招聘需求详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_RECRUIT_REQUIREMENTS where REQUIREMENTS_ID='$REQUIREMENTS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $REQU_NO=$ROW["REQU_NO"];   
  $REQU_DEPT=$ROW["REQU_DEPT"];
  $REQU_JOB=$ROW["REQU_JOB"];
  $REQU_NUM=$ROW["REQU_NUM"];
  $REQU_REQUIRES=$ROW["REQU_REQUIRES"];
  $REQU_TIME=$ROW["REQU_TIME"];
  $PETITIONER=$ROW["PETITIONER"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];

  if($REQU_DEPT=="ALL_DEPT")
     $REQU_DEPT_NAME=_("全体部门");
  else  
     $REQU_DEPT_NAME=substr(GetDeptNameById($REQU_DEPT),0,-1);
  
  $PETITIONER_NAME=substr(GetUserNameById($PETITIONER),0,-1);
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("需求编号：")?></td>
    <td nowrap align="left" class="TableData" width="180" width="180"><?=$REQU_NO?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("需求部门：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$REQU_DEPT_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("需求岗位：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$REQU_JOB?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("需求人数：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$REQU_NUM?><?=_("（人）")?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("用工日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$REQU_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("申请人：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$PETITIONER_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("岗位要求：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REQU_REQUIRES?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("附件文档：")?></td>
    <td nowrap align="left" class="TableData" colspan="3">
<?
    if($ATTACHMENT_ID=="")
       echo _("无附件");
    else
       echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);

?>
    </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
