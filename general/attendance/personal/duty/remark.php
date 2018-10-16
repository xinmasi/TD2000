<?
include_once("inc/auth.inc.php");

if($OP=="1")
{
   $query ="update ATTEND_DUTY set REMARK='$CONTENT' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME')";
   exequery(TD::conn(),$query);
?>
 <script>
 	window.close();
 </script>
<?
  exit;
}

$query1="select DUTY_TYPE from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
  $DUTY_TYPE=$ROW["DUTY_TYPE"];
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $temp="DUTY_TYPE".$REGISTER_TYPE;
   $DUTY_TYPE=$ROW[$temp];
}

$query = "SELECT REMARK from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME')";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CONTENT=$ROW["REMARK"];

$HTML_PAGE_TITLE = _("说明情况");
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
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<style>
    .twidth{
      width:375px;
    }
</style>
<body class="bodycolor attendance" onload="document.form1.CONTENT.focus();">
<h5 class="attendance-title"><span class="big3"> <?=_("说明情况")?></span></h5>
  
<br>
 <table class="TableBlock"  width="400" align="center">
  <form action="remark.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableHeader"> <?=_("请说明有关情况（如迟到或早退原因，加班情况等）：")?></td>
    </tr>
    <tr>
      <td class="TableData" >
        <textarea name="CONTENT" cols="50" rows="7" class="twidth"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap>

        <input type="hidden" name="REGISTER_TYPE" value="<?=$REGISTER_TYPE?>">
        <input type="hidden" name="REGISTER_TIME" value="<?=$REGISTER_TIME?>">
        <input type="hidden" name="OP">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="btn" onclick="window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>