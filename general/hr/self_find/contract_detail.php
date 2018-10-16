<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<body class="bodycolor">

<?
$query = "SELECT * from HR_STAFF_CONTRACT where CONTRACT_ID='$CONTRACT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $CONTRACT_COUNT++;
  
	$CONTRACT_ID=$ROW["CONTRACT_ID"];
  $USER_ID=$ROW["USER_ID"];
	$DEPT_ID=$ROW["DEPT_ID"];	
  $STAFF_NAME=$ROW["STAFF_NAME"];   
  $STAFF_CONTRACT_NO=$ROW["STAFF_CONTRACT_NO"];
  $CONTRACT_TYPE=$ROW["CONTRACT_TYPE"];
  $CONTRACT_SPECIALIZATION=$ROW["CONTRACT_SPECIALIZATION"];
  $MAKE_CONTRACT=$ROW["MAKE_CONTRACT"];
  $TRAIL_EFFECTIVE_TIME=$ROW["TRAIL_EFFECTIVE_TIME"];
  $PROBATIONARY_PERIOD=$ROW["PROBATIONARY_PERIOD"];
  $TRAIL_OVER_TIME=$ROW["TRAIL_OVER_TIME"];
  $PASS_OR_NOT=$ROW["PASS_OR_NOT"];
  $PROBATION_END_DATE=$ROW["PROBATION_END_DATE"];
  $PROBATION_EFFECTIVE_DATE=$ROW["PROBATION_EFFECTIVE_DATE"];
  $ACTIVE_PERIOD=$ROW["ACTIVE_PERIOD"];
  $CONTRACT_END_TIME=$ROW["CONTRACT_END_TIME"];
  $REMOVE_OR_NOT=$ROW["REMOVE_OR_NOT"];
  $CONTRACT_REMOVE_TIME=$ROW["CONTRACT_REMOVE_TIME"];
  $STATUS=$ROW["STATUS"];
  $SIGN_TIMES=$ROW["SIGN_TIMES"]; 
  $REMARK=$ROW["REMARK"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
  //添加显示合同续签相关信息。 2016.12.01  spz
  $IS_RENEW = $ROW["IS_RENEW"]; //是否续签
  if(isset($IS_RENEW)){
      $IS_RENEW = ($IS_RENEW)?'是':'否';
  }else{
      $IS_RENEW = '';
  }
  $RENEW_TIME = $ROW["RENEW_TIME"]; //续签到期日期
   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   
	 $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
	 $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");
	 
	 if($CONTRACT_SPECIALIZATION==1)
		 $CONTRACT_SPECIALIZATION=_("有固定期限");
	if($CONTRACT_SPECIALIZATION==2)
		 $CONTRACT_SPECIALIZATION=_("无固定期限");
		 
	if($TRAIL_EFFECTIVE_TIME=="0000-00-00")
     $TRAIL_EFFECTIVE_TIME="";
  if($TRAIL_OVER_TIME=="0000-00-00")
     $TRAIL_OVER_TIME="";
  if($PROBATION_END_DATE=="0000-00-00")
     $PROBATION_END_DATE="";
  if($PROBATION_EFFECTIVE_DATE=="0000-00-00")
     $PROBATION_EFFECTIVE_DATE="";
  if($CONTRACT_END_TIME=="0000-00-00")
     $CONTRACT_END_TIME="";
  if($CONTRACT_REMOVE_TIME=="0000-00-00")
     $CONTRACT_REMOVE_TIME="";

  if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
?>
<table class="TableBlock" align="center" width="82%">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同编号：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_CONTRACT_NO?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同类型：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$CONTRACT_TYPE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同属性：")?></td>
    <td align="left" class="TableData" width="180"><?=$CONTRACT_SPECIALIZATION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同签订日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MAKE_CONTRACT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("签约次数：")?></td>
    <td align="left" class="TableData" width="180"><?=$SIGN_TIMES?><?=_("次")?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("试用生效日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAIL_EFFECTIVE_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("试用到期日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$TRAIL_OVER_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("试用期限：")?></td>
    <td align="left" class="TableData" width="180"><?=$PROBATIONARY_PERIOD?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同是否转正：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($PASS_OR_NOT==1) echo _("是");if($PASS_OR_NOT==0) echo _("否");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同转正日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$PROBATION_END_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同生效日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$PROBATION_EFFECTIVE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同到期日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$CONTRACT_END_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同期限：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ACTIVE_PERIOD?></td>
  </tr>
  
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同是否续约：")?></td>
    <td align="left" class="TableData" width="180"><?=$IS_RENEW?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("续签到期日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$RENEW_TIME?></td>
  </tr>
 
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同是否解除：")?></td>
    <td align="left" class="TableData" width="180"><?if($REMOVE_OR_NOT==1) echo _("是");if($REMOVE_OR_NOT==0) echo _("否");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同解除日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$CONTRACT_REMOVE_TIME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("合同状态：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STATUS?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ADD_TIME?></td>
  </tr>
  <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("最后修改时间：")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
  </tr>
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
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
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>"></center>

</body>
</html>
