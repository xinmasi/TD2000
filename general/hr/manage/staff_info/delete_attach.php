<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
ob_start();

$CUR_TIME=date("Y-m-d H:i:s",time());

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$ATTACHMENT_NAME_OLD="";
$query="select * from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
}

if($ATTACHMENT_NAME!="")
{
    delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
    
    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);
    
    $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
            continue;
        $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
        $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
    }
    $ATTACHMENT_ID=$ATTACHMENT_ID1;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME1;
    
    $query="update HR_STAFF_INFO set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='$USER_ID'";
    exequery(TD::conn(),$query);
}
?>
<script>
    location="staff_info.php?USER_ID=<?=$USER_ID?>";
</script>

</body>
</html>
