<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$query="select * from HR_RECRUIT_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $PLAN_ID=$ROW["PLAN_ID"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $PLAN_DITCH=$ROW["PLAN_DITCH"];
   $PLAN_BCWS=$ROW["PLAN_BCWS"];
   $PLAN_RECR_NO=$ROW["PLAN_RECR_NO"];
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $RECRUIT_DIRECTION=$ROW["RECRUIT_DIRECTION"]; 
   $RECRUIT_REMARK=$ROW["RECRUIT_REMARK"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_DATE=$ROW["APPROVE_DATE"];
   $APPROVE_COMMENT=$ROW["APPROVE_COMMENT"];
   $APPROVE_RESULT=$ROW["APPROVE_RESULT"];
   $PLAN_STATUS=$ROW["PLAN_STATUS"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   
   $PLAN_DITCH_NAME=get_hrms_code_name($PLAN_DITCH,"PLAN_DITCH");
   $APPROVE_PERSON_NAME=substr(GetUserNameById($APPROVE_PERSON),0,-1);


   $RECRUIT_REMARK = str_replace("\n","<br>",$RECRUIT_REMARK);
   $RECRUIT_REMARK = str_replace(" ","&nbsp;",$RECRUIT_REMARK);
   $RECRUIT_DIRECTION = str_replace("\n","<br>",$RECRUIT_DIRECTION);
   $RECRUIT_DIRECTION = str_replace(" ","&nbsp;",$RECRUIT_DIRECTION); 
}

$HTML_PAGE_TITLE = _("招聘详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" width="17" height="17"><span class="big3"> <?=_("招聘详细信息")?></span><br></td>
  </tr>
</table>
<table class="TableBlock" width="90%" align="center">
   <tr>
   	  <td nowrap align="left" width="120" class="TableContent"><?=_("名称：")?></td>
		<td nowrap align="left" class="TableData"><?=$PLAN_NAME?></td>
		<td nowrap align="left" width="120" class="TableContent"><?=_("发起人：")?></td>
      <td nowrap align="left" class="TableData"><?=substr(GetUserNameById($CREATE_USER_ID),0,-1)?></td>
   </tr>
   <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("招聘渠道：")?></td>
      <td nowrap align="left" class="TableData"><?=$PLAN_DITCH_NAME?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("预算费用：")?></td>
      <td nowrap align="left" class="TableData"><?=$PLAN_BCWS?></td>
   </tr>
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("招聘人数：")?></td>
      <td nowrap align="left" class="TableData"><?=$PLAN_RECR_NO?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
      <td nowrap align="left" class="TableData"><?=$REGISTER_TIME?></td>
    </tr>
    <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("开始日期：")?></td>
      <td nowrap align="left" class="TableData"><?=$START_DATE=="0000-00-00"?"":$START_DATE;?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("结束日期：")?></td>
      <td nowrap align="left" class="TableData"><?=$END_DATE=="0000-00-00"?"":$END_DATE;?></td>
    <tr>
      <td nowrap align="left" width="120" class="TableContent" ><?=_("招聘说明：")?></td>
      <td align="left" class="TableData" colspan="3" style="word-break:break-all;"><?=$RECRUIT_DIRECTION?></td>
    </tr> 
    <tr>
      <td nowrap align="left" width="120" class="TableContent"><?=_("招聘备注：")?></td>
      <td align="left" class="TableData" colspan="3" style="word-break:break-all;"><?=$RECRUIT_REMARK?></td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap align="left" width="120" class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap colspan=3>
			<?
			if($ATTACHMENT_ID=="")
			   echo _("无附件");
			else
			   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1);//echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,1,0);
			?>      
      </td>
   </tr>  
   <tr>
    	<td nowrap align="left" width="120" class="TableContent"><?=_("审批人：")?></td>
      <td nowrap align="left" class="TableData"><?=$APPROVE_PERSON_NAME?></td>
      <td nowrap align="left" width="120" class="TableContent"><?=_("审批日期：")?></td>
      <td nowrap align="left" class="TableData"><?=$APPROVE_DATE=="0000-00-00"?"":$APPROVE_DATE;?></td>
    </tr>
     <tr>
      <td nowrap align="left" width="120" class="TableContent" ><?=_("审批意见：")?></td>
      <td nowrap align="left" class="TableData" colspan="3"><?=$APPROVE_COMMENT?></td>
    </tr> 
    <tr>
       <td nowrap align="left" width="120" class="TableContent"><?=_("计划状态：")?></td>
      <td nowrap align="left" class="TableData" colspan=3><?if($PLAN_STATUS==0) echo _("待审批");?><?if($PLAN_STATUS==1) echo _("已批准");?><?if($PLAN_STATUS==2) echo _("未批准");?></td>
   </tr>
   <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();" title="<?=_("关闭窗口")?>">
      </td>
   </tr>
  </table>
</body>
</html>