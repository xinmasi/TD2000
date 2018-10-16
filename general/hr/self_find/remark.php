<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;
if($OP=="1")
{
   $query ="update ATTEND_DUTY set REMARK='$CONTENT' where USER_ID='$USER_ID' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME')";
   exequery(TD::conn(),$query);
?>
 <script>
 	if(window.opener.location.href.indexOf("connstatus") < 0 ){
		window.opener.location.href = window.opener.location.href+"?connstatus=1";
	}else{
		window.opener.location.reload();
	}
 	window.close();
 </script>
<?
  exit;
}

$query1="SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
   $DUTY_TYPE=$ROW["DUTY_TYPE"];
$DUTY_TYPE=intval($DUTY_TYPE);
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $temp="DUTY_TYPE".$REGISTER_TYPE;
   $DUTY_TYPE=$ROW[$temp];
}

$query = "SELECT REMARK from ATTEND_DUTY where USER_ID='$USER_ID' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME')";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
   $CONTENT=$ROW["REMARK"];

$HTML_PAGE_TITLE = _("情况说明");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   document.form1.OP.value="1"
   return (true);
}

</script>



<?
 $CUR_HOUR="09";
 $CUR_MIN="00";
?>

<body class="bodycolor" onload="document.form1.CONTENT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("情况说明")?></span>
    </td>
  </tr>
</table>

<br>
 <table class="TableBlock" width="400"  align="center">
  <form action="remark.php?connstatus=1"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableHeader"> <?=_("考勤情况说明：")?></td>
    </tr>
    <tr>
      <td class="TableData">
        <textarea name="CONTENT" cols="50" rows="5" class="BigInput"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>

        <input type="hidden" name="REGISTER_TYPE" value="<?=$REGISTER_TYPE?>">
        <input type="hidden" name="REGISTER_TIME" value="<?=$REGISTER_TIME?>">
        <input type="hidden" name="OP">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>