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
<?
$query = "SELECT * from HR_STAFF_INCENTIVE where INCENTIVE_ID='$INCENTIVE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $INCENTIVE_COUNT++;

   $INCENTIVE_ID=$ROW["INCENTIVE_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"];
   $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
   $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
   $SALARY_MONTH=$ROW["SALARY_MONTH"];
   $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
   $INCENTIVE_DESCRIPTION=$ROW["INCENTIVE_DESCRIPTION"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   
   $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
   $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");   

?>
<table class="TableBlock" align="center" width="82%">
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
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
