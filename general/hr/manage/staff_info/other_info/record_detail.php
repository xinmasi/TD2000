<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$query="select * from HR_TRAINING_RECORD where RECORD_ID='$RECORD_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{ 
    $REQUIREMENTS_COUNT++;
   
	$RECORD_ID=$ROW["RECORD_ID"];
	$STAFF_USER_ID=$ROW["STAFF_USER_ID"];
	$T_PLAN_NO=$ROW["T_PLAN_NO"];
	$T_PLAN_NAME=$ROW["T_PLAN_NAME"];
	$T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
	$TRAINNING_COST=$ROW["TRAINNING_COST"];
	$DUTY_SITUATION=$ROW["DUTY_SITUATION"];
	$TRAINNING_SITUATION=$ROW["TRAINNING_SITUATION"];
	$T_EXAM_RESULTS=$ROW["T_EXAM_RESULTS"];
	$T_EXAM_LEVEL=$ROW["T_EXAM_LEVEL"];
	$T_COMMENT=$ROW["T_COMMENT"];
	$REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"]; 	
	

   $query2= "SELECT USER_NAME from  USER WHERE USER_ID='$STAFF_USER_ID'";
	 $cursor2=exequery(TD::conn(),$query2);
	 if($ROW2=mysql_fetch_array($cursor2))
	 {
	 		$STAFF_USER_NAME=$ROW2["USER_NAME"];
	 }
}

$HTML_PAGE_TITLE = _("培训记录详细信息");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" width="17" height="17"><span class="big3"> <?=_("培训记录详细信息")?></span><br></td>
  </tr>
</table>
<table class="TableBlock" width="90%" align="center">
   <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("培训计划编号：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_PLAN_NO?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("培训计划名称：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_PLAN_NAME?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("培训机构：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_INSTITUTION_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("培训费用：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAINNING_COST?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("培训考核成绩：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_EXAM_RESULTS?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("培训考核等级：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$T_EXAM_LEVEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("受训人：")?></td>
    <td class="TableData" colspan=3><?=$STAFF_USER_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("出勤情况：")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$DUTY_SITUATION?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("总结完成情况：")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$TRAINNING_SITUATION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("评论：")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$T_COMMENT?></td>
  </tr> 
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td nowrap align="left" class="TableData" colspan=3><?=$REMARK?></td>
  </tr>
   <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("附件：")?></td>
    <td nowrap align="left" class="TableData" colspan=3>
<?
    if($ATTACHMENT_ID==""){
       echo _("无附件");
     }else{
       echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,1);
	 }
	 
?>
    </td>	
  </tr>
  <tr align="center" class="TableControl">
    <td colspan=4 nowrap>
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
  </table>
</body>
</html>