<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("职称评定详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("职称评定详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where EVALUATION_ID='$EVALUATION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $POST_NAME=$ROW["POST_NAME"];
   $GET_METHOD=$ROW["GET_METHOD"];
   $REPORT_TIME=$ROW["REPORT_TIME"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_NEXT=$ROW["APPROVE_NEXT"];
   $APPROVE_NEXT_TIME=$ROW["APPROVE_NEXT_TIME"];
   $REMARK=$ROW["REMARK"];
   $EMPLOY_POST=$ROW["EMPLOY_POST"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $EMPLOY_COMPANY=$ROW["EMPLOY_COMPANY"];
   $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
      
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
   if($REPORT_TIME=="0000-00-00")
     $REPORT_TIME="";
   if($RECEIVE_TIME=="0000-00-00")
     $RECEIVE_TIME="";
   if($START_DATE=="0000-00-00")
     $START_DATE="";
   if($END_DATE=="0000-00-00")
     $END_DATE="";
   if($APPROVE_NEXT_TIME=="0000-00-00")
     $APPROVE_NEXT_TIME="";
     
   $GET_METHOD=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");
   $BY_EVALU_NAME=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1);
    if($BY_EVALU_NAME=="")
	  {
	  	 $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_EVALU_STAFFS'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $BY_EVALU_NAME=$ROW1["STAFF_NAME"];
	    $BY_EVALU_NAME=$BY_EVALU_NAME."("."<font color='red'>"._("用户已删除")."</font>".")";
	   }
   $APPROVE_PERSON_NAME=substr(GetUserNameById($ROW["APPROVE_PERSON"]),0,-1);
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$BY_EVALU_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("批准人：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$APPROVE_PERSON_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("获取职称：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$POST_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("获取方式：")?></td>
    <td align="left" class="TableData Content"><?=$GET_METHOD?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("申报时间：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$REPORT_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("获取时间：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$RECEIVE_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下次申报时间：")?></td>
    <td align="left"class="TableData" width="180"><?=$APPROVE_NEXT_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下次申报职称：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$APPROVE_NEXT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("聘用职务：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$EMPLOY_POST?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("聘用单位：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$EMPLOY_COMPANY?></td>
  </tr>      
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("聘用开始时间：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$START_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("聘用结束时间：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$END_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("评定详情：")?></td>
    <td nowrap align="left"class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("附件文档：")?></td>
    <td nowrap align="left"class="TableData" colspan="3">
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
    <td nowrap align="left"class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("最后修改时间：")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
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
