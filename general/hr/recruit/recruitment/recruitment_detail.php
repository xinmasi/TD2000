<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$query="select * from HR_RECRUIT_RECRUITMENT where RECRUITMENT_ID='$RECRUITMENT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   
   $REQUIREMENTS_COUNT++;

   $RECRUITMENT_ID=$ROW["RECRUITMENT_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $APPLYER_NAME=$ROW["APPLYER_NAME"];
   $JOB_STATUS=$ROW["JOB_STATUS"];
   $ASSESSING_OFFICER=$ROW["ASSESSING_OFFICER"];
   $ASS_PASS_TIME=$ROW["ASS_PASS_TIME"];
   $DEPARTMENT=$ROW["DEPARTMENT"];
   $TYPE=$ROW["TYPE"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $JOB_POSITION=$ROW["JOB_POSITION"]; 
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $ON_BOARDING_TIME=$ROW["ON_BOARDING_TIME"];
   $STARTING_SALARY_TIME=$ROW["STARTING_SALARY_TIME"];
   $REMARK=$ROW["REMARK"];
   $OA_NAME=$ROW["OA_NAME"];
   
   $ASSESSING_OFFICER_NAME=substr(GetUserNameById($ASSESSING_OFFICER),0,-1);
   $DEPARTMENT_NAME=substr(GetDeptNameById($DEPARTMENT),0,-1);
   $TYPE_NAME=get_hrms_code_name($TYPE,"STAFF_OCCUPATION");
   $PRESENT_POSITION_NAME=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
}

$HTML_PAGE_TITLE = _("招聘录用详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" width="17" height="17"><span class="big3"> <?=_("招聘录用详细信息")?></span><br></td>
  </tr>
</table>
<table class="TableBlock" width="90%" align="center">
   <tr>
   		<td nowrap align="left" width="120" class="TableContent"><?=_("计划编号：")?></td>
			<td nowrap align="left" class="TableData" ><?=$PLAN_NO?></td>
			<td nowrap align="left" width="120" class="TableContent"><?=_("应聘人姓名：")?></td>
			<td nowrap align="left" class="TableData" ><?=$APPLYER_NAME?></td>
   </tr>
   <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("招聘岗位：")?></td>
      <td nowrap align="left" class="TableData" ><?=$JOB_STATUS?></td>
      <td nowrap align="left" width="120" class="TableContent">OA<?=_("中用户名：")?></td>
      <td nowrap align="left" class="TableData" ><?=$OA_NAME?></td>
   </tr>
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("录用负责人：")?></td>
      <td nowrap align="left" class="TableData"><?=$ASSESSING_OFFICER_NAME?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("录入日期：")?></td>
      <td nowrap align="left" class="TableData"><?=$ASS_PASS_TIME?></td>
    </tr>
    <tr>
      <td nowrap align="left" width="120" class="TableContent" ><?=_("招聘部门：")?></td>
      <td nowrap align="left" class="TableData" colspan="3"><?=$DEPARTMENT_NAME?></td>
    </tr> 
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("员工类型：")?></td>
      <td nowrap align="left" class="TableData"><?=$TYPE_NAME?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("行政登记：")?></td>
      <td nowrap align="left" class="TableData"><?=$ADMINISTRATION_LEVEL?></td>
    </tr>  
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("职务：")?></td>
      <td nowrap align="left" class="TableData"><?=$JOB_POSITION?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("职称：")?></td>
      <td nowrap align="left" class="TableData"><?=$PRESENT_POSITION_NAME?></td>
    </tr>  
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("正式入职时间：")?></td>
      <td nowrap align="left" class="TableData"><?=$ON_BOARDING_TIME?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("正式起薪时间：")?></td>
      <td nowrap align="left" class="TableData"><?=$STARTING_SALARY_TIME?></td>
    </tr>  
    <tr>
      <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
      <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
    </tr> 
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();" title="<?=_("关闭窗口")?>">
      </td>
    </tr>
  </table>
</body>
</html>