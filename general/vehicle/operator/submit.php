<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$MANAGERS=$COPY_TO_ID;
$query1 = "select OPERATOR_ID from VEHICLE_OPERATOR";
$cursor = exequery(TD::conn(), $query1);
$row = mysql_fetch_array($cursor, true);
if($row)
{
    $query="update VEHICLE_OPERATOR set OPERATOR_NAME='$MANAGERS' where OPERATOR_ID=1";
    exequery(TD::conn(), $query);
}
else
{
    $query = "insert into VEHICLE_OPERATOR(OPERATOR_ID,OPERATOR_NAME) value(1,'$MANAGERS')";
    exequery(TD::conn(), $query);
}

Message(_("提示"),_("保存成功"));
Button_Back();
?>
