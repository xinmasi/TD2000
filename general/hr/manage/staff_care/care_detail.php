<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工关怀详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("员工关怀详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_CARE where CARE_ID='$CARE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $BY_CARE_STAFFS=$ROW["BY_CARE_STAFFS"];	
  $CARE_DATE=$ROW["CARE_DATE"];   
  $CARE_CONTENT=$ROW["CARE_CONTENT"];
  $PARTICIPANTS=$ROW["PARTICIPANTS"];
  $CARE_EFFECTS=$ROW["CARE_EFFECTS"];
  $CARE_FEES=$ROW["CARE_FEES"];
  $CARE_TYPE=$ROW["CARE_TYPE"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
   if($CARE_DATE=="0000-00-00")
     $CARE_DATE="";
  	
  $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");  
  $BY_CARE_STAFFS_NAME = substr(GetUserNameById($BY_CARE_STAFFS),0,-1);
   if($BY_CARE_STAFFS_NAME=="")
   {
     $query = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_CARE_STAFFS'"; 
     $cursor= exequery(TD::conn(),$query); 
     if($ROW=mysql_fetch_array($cursor)) 
        $BY_CARE_STAFFS_NAME=$ROW["STAFF_NAME"]."("."<font color='red'>"._("用户已删除")."</font>".")"; 
   }
  $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$BY_CARE_STAFFS_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("关怀类型：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$TYPE_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("关怀开支费用/人：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$CARE_FEES?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("关怀日期：")?></td>
    <td nowrap align="left"class="TableData" width="180"><?=$CARE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("参与人：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$PARTICIPANTS_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("关怀内容：")?></td>
    <td nowrap align="left"class="TableData" colspan="3"><?=$CARE_CONTENT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("关怀效果：")?></td>
    <td align="left"class="TableData" colspan="3"><?=$CARE_EFFECTS?></td>
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
