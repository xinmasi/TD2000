<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人事调动详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("人事调动详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_ID='$TRANSFER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
   $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
   $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
   $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
   $TRAN_COMPANY_BEFORE=$ROW["TRAN_COMPANY_BEFORE"];
   $TRAN_DEPT_BEFORE=$ROW["TRAN_DEPT_BEFORE"];
   $TRAN_POSITION_BEFORE=$ROW["TRAN_POSITION_BEFORE"];
   $TRAN_COMPANY_AFTER=$ROW["TRAN_COMPANY_AFTER"];
   $TRAN_DEPT_AFTER=$ROW["TRAN_DEPT_AFTER"];
   $TRAN_POSITION_AFTER=$ROW["TRAN_POSITION_AFTER"];
   $TRAN_REASON=$ROW["TRAN_REASON"];
   $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $REMARK=$ROW["REMARK"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];

   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
   if($TRANSFER_DATE=="0000-00-00")
     $TRANSFER_DATE="";
   if($TRANSFER_EFFECTIVE_DATE=="0000-00-00")
     $TRANSFER_EFFECTIVE_DATE="";

   $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");
   $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
   if($TRANSFER_PERSON_NAME=="")
   {
      $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$TRANSFER_PERSON'";
      $cursor2= exequery(TD::conn(),$query2);
      if($ROW2=mysql_fetch_array($cursor2))
         $TRANSFER_PERSON_NAME=$ROW2["STAFF_NAME"];
      $TRANSFER_PERSON_NAME=$TRANSFER_PERSON_NAME."(<font color='red'>"._("用户已删除")."</font>)";
   }

	 $TRAN_DEPT_BEFORE_NAME=substr(GetDeptNameById($TRAN_DEPT_BEFORE),0,-1);
	 $TRAN_DEPT_AFTER_NAME=substr(GetDeptNameById($TRAN_DEPT_AFTER),0,-1);
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_PERSON_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动类型：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_TYPE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动生效日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRANSFER_EFFECTIVE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动前单位：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_COMPANY_BEFORE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动后单位：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_COMPANY_AFTER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动前部门：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_DEPT_BEFORE_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动后部门：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_DEPT_AFTER_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动前职务：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_POSITION_BEFORE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动后职务：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$TRAN_POSITION_AFTER?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动手续办理：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$MATERIALS_CONDITION?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("调动原因：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$TRAN_REASON?></td>
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
