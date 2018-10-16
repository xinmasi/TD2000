<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");



$HTML_PAGE_TITLE = _("编辑招聘信息");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
</script>

<?
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
}

$CUR_TIME=date("Y-m-d H:i:s",time());
?>
<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑招聘信息")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" >
<table class="TableBlock" width="60%" align="center">
   <tr>
   		<td nowrap class="TableData" width="100"><?=_("名称：")?></td>
    	<td class="TableData"  width="180"><?=$PLAN_NAME?></td>
		<td nowrap align="left" class="TableData"><?=_("发起人：")?></td>
      <td nowrap align="left" class="TableData"><?=substr(GetUserNameById($CREATE_USER_ID),0,-1)?></td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("招聘渠道：")?></td>
      <td class="TableData" ><?=$PLAN_DITCH_NAME?></td>
      <td nowrap class="TableData"><?=_("预算费用：")?></td>
      <td class="TableData"><?=$PLAN_BCWS?></td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("招聘人数：")?></td>
      <td class="TableData"><?=$PLAN_RECR_NO?><?=_("人")?></td>
      <td nowrap class="TableData"><?=_("登记时间：")?></td>
      <td class="TableData"><?=$REGISTER_TIME?></td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("开始日期：")?></td>
      <td class="TableData"><?=$START_DATE?></td>
      <td nowrap class="TableData"><?=_("结束日期：")?></td>
      <td class="TableData"><?=$END_DATE?></td>
   <tr>
      <td nowrap class="TableData"><?=_("招聘说明：")?></td>
      <td class="TableData" colspan=3><?=$RECRUIT_DIRECTION?></td>
   </tr> 
   <tr>
      <td nowrap class="TableData"><?=_("招聘备注：")?></td>
      <td class="TableData" colspan=3><?=$RECRUIT_REMARK?></td>
   </tr> 
   <tr>
    	<td nowrap class="TableData"><?=_("审批人：")?></td>
      <td class="TableData" colspan=3><?=$APPROVE_PERSON_NAME?></tr>
   <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap colspan=3>
			<?
			if($ATTACHMENT_ID=="")
			   echo _("无附件");
			else
			   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,1,1,1);
			?>      
      </td>
   </tr>  
<!--   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
			<?=sms_remind(46);?>
      </td>
  <tr>
-->
      <td nowrap class="TableData"><?=_("审批日期：")?></td>
      <td class="TableData" colspan=3>
      	<input type="text" name="APPROVE_DATE" size="10" maxlength="10" class="BigInput" value="<?=$CUR_TIME?>" onClick="WdatePicker()"/>
      </td>
   </tr>
     <tr>
      <td nowrap class="TableData"><?=_("审批意见：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="APPROVE_COMMENT" cols="66" rows="5" class="BigInput" ><?=$APPROVE_COMMENT?></textarea>
      </td>
   </tr> 
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" name="PLAN_ID" size="20" value="<?=$PLAN_ID?>">
      	<input type="hidden" name="PLAN_NAME" size="20" value="<?=$PLAN_NAME?>">
      	<input type="hidden" name="PLAN_STATUS" size="20" value="2">
        <input type="submit" value="<?=_("不批准")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index1.php?connstatus=1';">
      </td>
   </tr>
  </table>
</form>
</body>
</html>