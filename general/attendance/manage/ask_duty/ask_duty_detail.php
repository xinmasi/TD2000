<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("查岗质询详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" width="17" height="17"><span class="big3"><?=_("查岗质询详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$ASK_DUTY_ID=intval($ASK_DUTY_ID);
$query = "SELECT * from ATTEND_ASK_DUTY where ASK_DUTY_ID='$ASK_DUTY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
   $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
   $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];

   $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $CHECK_DUTY_REMARK=$ROW["CHECK_DUTY_REMARK"];
   $EXPLANATION=$ROW["EXPLANATION"];

	 $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
   $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);
   if($CHECK_DUTY_TIME=="0000-00-00 00:00:00")
     $CHECK_DUTY_TIME="";
   if($RECORD_TIME=="0000-00-00 00:00:00")
     $RECORD_TIME="";

?>
<table class="TableBlock" width="80%" align="center">
  <tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("缺岗人姓名：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$CHECK_USER_NAME?></td>
 	</tr>
  <tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("查岗人姓名：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$CHECK_MANAGER_NAME?></td>
 	</tr>
 	<tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("查岗人说明：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$CHECK_DUTY_REMARK?></td>
 	</tr>
 	<tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("查岗时间：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$CHECK_DUTY_TIME?></td>
 	</tr>
 	<tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("缺岗人说明时间：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$RECORD_TIME?></td>
 	</tr>
 	<tr>
    <td nowrap align="left" width="15%" class="TableContent"><?=_("缺岗人说明：")?></td>
    <td nowrap align="left"class="TableData" width="50%"><?=$EXPLANATION?></td>
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
