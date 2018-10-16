<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("工作经历详细信息");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID='$W_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $WORK_UNIT=$ROW["WORK_UNIT"];
   $MOBILE=$ROW["MOBILE"];
   $WORK_BRANCH=$ROW["WORK_BRANCH"];
   $POST_OF_JOB=$ROW["POST_OF_JOB"];
   $WORK_CONTENT=$ROW["WORK_CONTENT"];
   $KEY_PERFORMANCE=$ROW["KEY_PERFORMANCE"];
   $REASON_FOR_LEAVING=$ROW["REASON_FOR_LEAVING"];
   $WITNESS=$ROW["WITNESS"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   

?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("工作单位：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WORK_UNIT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("开始日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$START_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("结束日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$END_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("行业类别：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MOBILE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所在部门：")?></td>
    <td align="left" class="TableData" width="180"><?=$WORK_BRANCH?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("担任职务：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POST_OF_JOB?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("证明人：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WITNESS?></td>      
  </tr>
    <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("工作内容：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$WORK_CONTENT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("主要业绩：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$KEY_PERFORMANCE?></td>
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
    <td nowrap align="left" width="120" class="TableContent"><?=_("离职原因：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REASON_FOR_LEAVING?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
