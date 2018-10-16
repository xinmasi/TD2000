<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$s_apply_weekdays = "";
while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);

    if($KEY == 'sun' || $KEY == 'mon' || $KEY == 'tue' || $KEY == 'wed' || $KEY == 'thu' || $KEY == 'fri' || $KEY == 'sat')
    {
        $s_apply_weekdays .= $$KEY.",";
    }
}

$MANAGERS=$COPY_TO_ID;
if($MR_ID=="")
    $query="insert into MEETING_ROOM(MR_NAME,MR_CAPACITY,MR_DEVICE,MR_DESC,MR_PLACE,OPERATOR,TO_ID,SECRET_TO_ID,APPLY_WEEKDAYS,VIDEO_TYPE) values('$MR_NAME','$MR_CAPACITY','$MR_DEVICE','$MR_DESC','$MR_PLACE','$MANAGERS','$TO_ID','$SECRET_TO_ID','$s_apply_weekdays','$VIDEO_TYPE')";
else
{
    $MR_ID=intval($MR_ID);
    $query="update MEETING_ROOM set MR_NAME='$MR_NAME',MR_CAPACITY='$MR_CAPACITY' ,MR_DEVICE='$MR_DEVICE',MR_DESC='$MR_DESC',MR_PLACE='$MR_PLACE',OPERATOR='$MANAGERS',TO_ID='$TO_ID',SECRET_TO_ID='$SECRET_TO_ID',APPLY_WEEKDAYS='$s_apply_weekdays',VIDEO_TYPE='$VIDEO_TYPE' where MR_ID='$MR_ID'";
}
exequery(TD::conn(),$query);
Message(_("提示"),_("保存成功！"));
?>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='index.php';"></center>
</body>

</html>