<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$M_ID=intval($M_ID);
$query="select * from MEETING where M_ID='$M_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
}

//----------删除相应会议附件----------
delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);

//----------删除相应日程项----------
$query_r = "select M_START,M_NAME from meeting where M_ID='$M_ID'";
$cursor_r = exequery(TD::conn(),$query_r);
if($ROW=mysql_fetch_array($cursor_r))
{
  $M_START_OLD= $ROW['M_START'];
  $M_NAME=$ROW["M_NAME"];
}
$M_START_OLD=strtotime($M_START_OLD);
// $CALENDER_CONTENT =_("会议").$M_ID;
// $query2="select CAL_ID from CALENDAR where CONTENT like '$CALENDER_CONTENT%'";
// $cursor2=exequery(TD::conn(),$query2);
// while($ROW2=mysql_fetch_array($cursor2))
// {
//    $CAL_ID=$ROW2["CAL_ID"];
//    $query="delete from CALENDAR where CAL_ID='$CAL_ID'";
//    exequery(TD::conn(),$query);
// }

//删除关联日程wrj20140320
$query="delete from CALENDAR where M_ID='$M_ID' and FROM_MODULE='2'";
exequery(TD::conn(),$query);

$query="delete from MEETING where M_ID='$M_ID'";
exequery(TD::conn(),$query);

header("location:query.php?M_STATUS=$M_STATUS");
?>

</body>
</html>
