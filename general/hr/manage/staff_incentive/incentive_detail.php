<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("奖惩详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("奖惩详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_INCENTIVE where INCENTIVE_ID='$INCENTIVE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"]=="0000-00-00"?"":$ROW["INCENTIVE_TIME"];
   $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
   $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
   $SALARY_MONTH=$ROW["SALARY_MONTH"];
   $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
   $INCENTIVE_DESCRIPTION=$ROW["INCENTIVE_DESCRIPTION"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"]=="0000-00-00 00:00:00"?"":$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"]=="0000-00-00 00:00:00"?"":$ROW["LAST_UPDATE_TIME"];
   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 if($STAFF_NAME1=="")
	 {
	   $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
       $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
          $STAFF_NAME1=$ROW1["STAFF_NAME"]."("."<font color='red'>"._("用户已删除")."</font>".")";
	}
   $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
   $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");
  
  if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
   

?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("奖惩项目：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$INCENTIVE_ITEM?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("奖惩日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$INCENTIVE_TIME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("工资月份：")?></td>
    <td align="left" class="TableData Content" width="180"><?=$SALARY_MONTH?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("奖惩属性：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$INCENTIVE_TYPE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("奖惩金额：")?></td>
    <td align="left" class="TableData Content" width="180"><?=$INCENTIVE_AMOUNT?><?=_("元")?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("奖惩说明：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$INCENTIVE_DESCRIPTION?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ADD_TIME?></td>
  </tr>
   <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("最后修改时间：")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
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
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData Content" colspan="3"><?=$REMARK?></td>
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
