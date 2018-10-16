<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

$TOK=strtok($DELETE_STR,",");

$query="select MANAGER from RMS_ROLL where ROLL_ID='$ROLL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $MANAGER=$ROW["MANAGER"];

while($TOK!="")
{
    $query="insert into RMS_LEND (FILE_ID,USER_ID,ADD_TIME,APPROVE) values('$TOK','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$MANAGER')";
    exequery(TD::conn(),$query);

    $TOK=strtok(",");
}


Message("",_("°¸¾í½èÔÄ³É¹¦£¡"));
Button_Back();
?>

</body>
</html>
