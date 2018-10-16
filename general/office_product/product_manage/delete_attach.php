<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from OFFICE_PRODUCTS where PRO_ID ='$PRO_ID'";
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
    for($I=0;$I< $ARRAY_COUNT;$I++)
    {
        if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
            continue;
        $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
        $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
    }
    $ATTACHMENT_ID=$ATTACHMENT_ID1;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME1;


    $query="update OFFICE_PRODUCTS set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where PRO_ID='$PRO_ID'";
    exequery(TD::conn(),$query);
}

$query = "select OFFICE_PROTYPE from OFFICE_PRODUCTS where PRO_ID='$PRO_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
    $TYPE = $ROW["OFFICE_PROTYPE"];

$query = "select TYPE_DEPOSITORY from OFFICE_TYPE where ID ='$TYPE'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
    $DEPOSITORY_ID = $ROW["TYPE_DEPOSITORY"];

$query = "select OFFICE_TYPE_ID  from OFFICE_DEPOSITORY where ID ='$DEPOSITORY_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
    $OFFICE_TYPE_ID = $ROW["OFFICE_TYPE_ID"];

header("location: edit.php?DEPOSITORY=$OFFICE_TYPE_ID&DEPOSITORY_ID=$DEPOSITORY_ID&TYPE=$TYPE&PRO_ID=$PRO_ID");
?>

</body>
</html>
