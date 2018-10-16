<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("社会关系详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_RELATIVES where RELATIVES_ID ='$RELATIVES_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$MEMBER=$ROW["MEMBER"];	
  $RELATIONSHIP=$ROW["RELATIONSHIP"];   
  $BIRTHDAY=$ROW["BIRTHDAY"];
  $POLITICS=$ROW["POLITICS"];
  $WORK_UNIT=$ROW["WORK_UNIT"];
  $UNIT_ADDRESS=$ROW["UNIT_ADDRESS"];
  $POST_OF_JOB=$ROW["POST_OF_JOB"];
  $OFFICE_TEL=$ROW["OFFICE_TEL"];	
  $HOME_ADDRESS=$ROW["HOME_ADDRESS"];   
  $HOME_TEL=$ROW["HOME_TEL"];
  $JOB_OCCUPATION=$ROW["JOB_OCCUPATION"];
  $STAFF_NAME=$ROW["STAFF_NAME"];
  $PERSONAL_TEL=$ROW["PERSONAL_TEL"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  	
  $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");
  $POLITICS=get_hrms_code_name($POLITICS,"STAFF_POLITICAL_STATUS");
   
  $STAFF_NAME1 = substr(GetUserNameById($STAFF_NAME),0,-1);
?>
<table class="TableBlock" width="82%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("成员姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MEMBER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("与本人关系：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$RELATIONSHIP?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("出生日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$BIRTHDAY?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("政治面貌：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POLITICS?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("职业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$JOB_OCCUPATION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("工作单位：")?></td>
    <td align="left" class="TableData" width="180"><?=$WORK_UNIT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("联系电话（单位）：")?></td>
    <td align="left" class="TableData" width="180"><?=$OFFICE_TEL?></td>
  </tr>  
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("联系电话（个人）：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$PERSONAL_TEL?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("联系电话（家庭）：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$HOME_TEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("单位地址：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$UNIT_ADDRESS?></td>
  </tr>
    <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("家庭地址：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$HOME_ADDRESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
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
