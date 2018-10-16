<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$query="select * from ATTEND_ASK_DUTY where CHECK_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by CHECK_DUTY_TIME";
$cursor= exequery(TD::conn(),$query);
$tag=0;
while($ROW=mysql_fetch_array($cursor))
{
	$ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
  $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
  $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];
  $CHECK_DUTY_REMARK=$ROW['CHECK_DUTY_REMARK'];
  $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
  $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);

  $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
	$EXPLANATION=$ROW["EXPLANATION"];
	if($EXPLANATION=='')
		 $tag=1;
}
if($tag==1)
{
$MSG = sprintf(_("你在%s，没有在岗，请做解释：（一次填写，不能修改）"), $CHECK_DUTY_TIME);
$MSG2 = sprintf(_("%s的说明："), $CHECK_MANAGER_NAME);

$HTML_PAGE_TITLE = _("个人考勤");
include_once("inc/header.inc.php");
?>
		<script Language="JavaScript">
			function CheckForm()
			{
				if(document.form1.EXPLANATION.value=="")
   			{ 
      		alert("<?=_("请填写解释！")?>");
      		return (false);
   			}
   		}
		</script>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" width="17" height="17"><span class="big3"><?=_("查岗质询")?></span><br>
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="ask_duty.php"  method="post" name="form1" onsubmit="return CheckForm();">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><span style="color=red;"><?=$MSG?></span></td>

    </tr>
    <tr>
	<td nowrap class="TableData"><?=$MSG2?><?=$CHECK_DUTY_REMARK?></td>

    </tr>
    <tr>
    	<td class="TableData">
      	<textarea name="EXPLANATION" rows="2" cols="60" class="BigInput"></textarea>
      	<input type="hidden" value="<?=$ASK_DUTY_ID?>" name="ASK_DUTY_ID" />
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap="nowrap">
        <input type="submit" value="<?=_("提交")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
</body>
</html>

<?
}
?>