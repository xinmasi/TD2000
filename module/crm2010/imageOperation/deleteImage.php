<?
include_once("general/crm/inc/header.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("general/crm/inc/uploadImages.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(preg_match('/^[_0-9a-zA-Z]+$/', $DB_NAME))
{
    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from ".$DB_NAME." where id='$id'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor)) {
        $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
    }

    if($ATTACHMENT_NAME!="") {
        deleteImage($ATTACHMENT_ID,$ATTACHMENT_NAME,$MODULE);
        $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
        $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);

        $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
        for($I=0;$I<$ARRAY_COUNT;$I++) {
            if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
                continue;
            $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
            $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
        }
        $ATTACHMENT_ID_NEW=$ATTACHMENT_ID1;
        $ATTACHMENT_NAME_NEW=$ATTACHMENT_NAME1;

        $query ="update ".$DB_NAME." set ATTACHMENT_ID='$ATTACHMENT_ID_NEW',ATTACHMENT_NAME='$ATTACHMENT_NAME_NEW' where id='$id'";
        exequery(TD::conn(),$query);
    }
}
?>
</body>
</html>
<script>
    parent.window.location.reload();
</script>	
