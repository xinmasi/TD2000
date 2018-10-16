<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("删除固定资产");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if($CPTL_ID!="")
{
    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor)){
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    }
    delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
    $query="delete from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
    exequery(TD::conn(),$query);

    //删除自定义数据
    $sql = "delete from field_date where IDENTY_ID = '$CPTL_ID' and TABLENAME = 'CP_CPTL_INFO'";
    exequery(TD::conn(),$sql);
}
if($DELETE_STR!="")
{
    $DELETE_STR=substr($DELETE_STR,0,-1);

    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from CP_CPTL_INFO where CPTL_ID in ($DELETE_STR)";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor)){
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
        delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
    }
    $query="delete from CP_CPTL_INFO where CPTL_ID in ($DELETE_STR)";
    exequery(TD::conn(),$query);

    //删除自定义数据
    $sql = "delete from field_date where IDENTY_ID in ($DELETE_STR) and TABLENAME = 'CP_CPTL_INFO'";
    exequery(TD::conn(),$sql);
}
header("location:index1.php?start=$start");
?>
</body>
</html>
