<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($M_ID_STR == "")
{
    $M_ID_STR = 0;
}
else
{
    $M_ID_STR = td_trim($M_ID_STR);
}
   
//删除会议申请连带删除提前事务提醒
$query = "SELECT M_ID,M_START,M_ROOM,M_NAME from MEETING where M_ID in ($M_ID_STR)";
$cursor = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
    $M_START    = $ROW["M_START"];
    $M_ROOM     = $ROW["M_ROOM"];
    $M_NAME     = $ROW["M_NAME"];
    $M_ID       = $ROW["M_ID"];
    
    $query1 = "select MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
    $cursor1 = exequery(TD::conn(),$query1);
    if($ROW1 = mysql_fetch_array($cursor1))
    {
        $MR_NAME = $ROW1["MR_NAME"];
    }
    
    $M_ID = intval($M_ID);
    $query1 = "select BODY_ID from SMS_BODY where REMIND_URL like '1:meeting/query/meeting_detail.php?M_ID=".$M_ID."'";
    $cursor1 = exequery(TD::conn(),$query1);
    while($ROW1 = mysql_fetch_array($cursor1))
    {
        $BODY_ID = $ROW1["BODY_ID"];
        
        $query2 = "delete from SMS_BODY where BODY_ID='$BODY_ID'";
        exequery(TD::conn(),$query2);
        
        $query2 = "delete from SMS where BODY_ID='$BODY_ID'";
        exequery(TD::conn(),$query2);
    }
    
    //删除会议申请连带删除提前事务提醒
    
//     $CAL_TIME = strtotime($M_START);
//     $CALENDER_CONTENT = _("会议").$M_ID;
//     $query2 = "select CAL_ID from CALENDAR where CONTENT like '$CALENDER_CONTENT%'";
//     $cursor2 = exequery(TD::conn(),$query2);
//     while($ROW2 = mysql_fetch_array($cursor2))
//     {
//         $CAL_ID = $ROW2["CAL_ID"];
//         $query3 = "delete from CALENDAR where CAL_ID='$CAL_ID'";
//         exequery(TD::conn(),$query3);
//     }

    //删除关联日程wrj20140320
    $query="delete from CALENDAR where M_ID='$M_ID' and FROM_MODULE='2'";
    exequery(TD::conn(),$query);
}

$query="delete from MEETING where M_ID in ($M_ID_STR)";
exequery(TD::conn(),$query);
if($CYCLE==0)
{
    header("location: manage.php?M_STATUS=$M_STATUS");
}else{
    if($M_STATUS==1)
    {
        header("location: manage.php?M_STATUS=$M_STATUS");
    }
    else
    {   
        $query = "SELECT count(*) as num from MEETING where M_STATUS=0";
        $cursor=exequery(TD::conn(),$query);
        $num=mysql_fetch_array($cursor)['num'];
        if($num == 0){
            header("location: manage1.php?M_STATUS=0");
        }else{
            header("location: manage_cycle.php?M_STATUS=$M_STATUS&CYCLE=".$_GET['CYCLE']."&CYCLE_NO=".$_GET['CYCLE_NO']);
        }
    }
}
?>

</body>

</html>
