<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("时钟");
include_once("inc/header.inc.php");
?>



<script language=javascript>
var parent_window = window.dialogArguments;
function check_form()
{
    var cur_time=form1.hour.value+":"+form1.minite.value;
    parent_window.<?=$FIELDNAME?>.value=cur_time;       
    window.close();
}
</script>

<body class="bodycolor">
<?
$CUR_HOUR = date('H');
$CUR_MINITE = date('i');
?>

<div class="big1" align="center">
<b>
<form name=form1>
<?=_("时间：")?><select name=hour class=BigSelect style="width:50px">
<?
for($I=0;$I<24;$I++)
{
   if($I<10)
      $HOUR="0".$I;
   else
      $HOUR=$I;
   if($I==$CUR_HOUR)
      echo "<option value=$HOUR selected>$HOUR</option>";
   else
      echo "<option value=$HOUR>$HOUR</option>";
}
?>
</select>
<?=_("：")?><select name=minite class=BigSelect style="width:50px">
<?
for($I=0;$I<60;$I++)
{
   if($I<10)
      $MINITE="0".$I;
   else
      $MINITE=$I;
   if($I==$CUR_MINITE)
      echo "<option value=$MINITE selected>$MINITE</option>";
   else
      echo "<option value=$MINITE>$MINITE</option>";
}      
?>
</select>

<br>
<br>
<input type=button  onClick="check_form();" value=" <?=_("确定")?> " class="BigButton">
</form>
</b>
</div>

</body>
</html>
