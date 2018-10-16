<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("还书");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

if($OP_FLAG==11)
{
    $query="update BOOK_MANAGE set BOOK_STATUS='1',STATUS='1',REAL_RETURN_TIME='$CUR_DATE' where BORROW_ID='$BORROW_ID'";
    exequery(TD::conn(),$query);

    $query="update BOOK_INFO set LEND='0' where BOOK_NO='$BOOK_NO'";
    exequery(TD::conn(),$query);
}

if($OP_FLAG==21) // 借书同意
{
    $query = "SELECT * from BOOK_MANAGE where BORROW_ID='$BORROW_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $RETURN_DATE=$ROW["RETURN_DATE"];
        $BUSER_ID=$ROW["BUSER_ID"];
    }

    $REMIND_DATE = date("Y-m-d",dateadd5("d",-2,$RETURN_DATE))." 08:30:00";

    $query = "SELECT AMT from BOOK_INFO where BOOK_NO='$BOOK_NO'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $AMT=$ROW["AMT"];

    $query="update BOOK_MANAGE set BOOK_STATUS='0',STATUS='1',RUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' where BORROW_ID='$BORROW_ID'";
    exequery(TD::conn(),$query);

    $query = "SELECT count(*) from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and ((BOOK_STATUS='1' and STATUS='0') or (BOOK_STATUS='0' and STATUS='1'))";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $LEND_COUNT=$ROW[0];

    if($LEND_COUNT >= $AMT)
    {
        $query="update BOOK_INFO set LEND='1' where BOOK_NO='$BOOK_NO'";
        exequery(TD::conn(),$query);
    }
    $MSG = sprintf(_("您借的图书(编号：%s)于%s到期,请按时归还。"), $BOOK_NO,$RETURN_DATE);
    $MSG2 = sprintf(_("%s同意了您的借书(编号：%s)申请。"), $_SESSION["LOGIN_USER_NAME"],$BOOK_NO);
    send_sms($REMIND_DATE,$_SESSION["LOGIN_USER_ID"],$BUSER_ID,73,$MSG,"book/query/query.php?STATUS=1&B_ID=$BORROW_ID",$BORROW_ID);
    send_sms("",$_SESSION["LOGIN_USER_ID"],$BUSER_ID,73,$MSG2,"book/query/query.php?STATUS=1");
}

if($OP_FLAG==22)// 借书不同意
{
   $query="update BOOK_MANAGE set STATUS='2' where BORROW_ID='$BORROW_ID'";
   exequery(TD::conn(),$query);
}

if($OP_FLAG==31) // 还书同意
{
   $query = "SELECT * from BOOK_MANAGE where BORROW_ID='$BORROW_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $RETURN_DATE=$ROW["RETURN_DATE"];
      $BUSER_ID=$ROW["BUSER_ID"];
   }
   $query="update BOOK_MANAGE set BOOK_STATUS='1',STATUS='1',REAL_RETURN_TIME='$CUR_DATE' where BORROW_ID='$BORROW_ID'";
   exequery(TD::conn(),$query);

   $query="update BOOK_INFO set LEND='0' where BOOK_NO='$BOOK_NO'";
   exequery(TD::conn(),$query);

   $SMS_CONTENT = sprintf(_("您借的图书(编号：%s)于%s到期,请按时归还。"), $BOOK_NO,$RETURN_DATE);
   $MSG3 = sprintf(_("您归还的图书(编号：%s)已被管理员批准。"), $BOOK_NO);
   send_sms("",$_SESSION["LOGIN_USER_ID"],$BUSER_ID,73,$MSG3,"book/query/query.php?STATUS=1");
   delete_remind_sms(73,$_SESSION["LOGIN_USER_ID"],$SMS_CONTENT,"","book/query/query.php?STATUS=1&B_ID=$BORROW_ID");
}

if($OP_FLAG==32)// 还书不同意
{
    $query = "SELECT BUSER_ID from BOOK_MANAGE where BORROW_ID='$BORROW_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $BUSER_ID=$ROW["BUSER_ID"];
    }
    $query="update BOOK_MANAGE set STATUS='2' where BORROW_ID='$BORROW_ID'";
    exequery(TD::conn(),$query);
    $MSG4 = sprintf(_("您归还的图书(编号：%s)未被管理员批准。"), $BOOK_NO);
    send_sms("",$_SESSION["LOGIN_USER_ID"],$BUSER_ID,73,$MSG4,$REMIND_URL);
}
header("location: index.php");
?>
</body>
</html>

<?
/**
* 转换为unix时间戳
*/
function gettime5($d)
{
    if(is_numeric($d))
        return $d;
    else
    {
        if(!is_string($d))
            return 0;
        if(strpos($d, ":") > 0)
        {
            $buf = explode(" ",$d);
            $year = explode("-",$buf[0]);
            $hour = explode(":",$buf[1]);
            if(stripos($buf[2], "pm") > 0)
                $hour[0] += 12;

            return mktime($hour[0],$hour[1],$hour[2],$year[1],$year[2],$year[0]);
        }
        else
        {
            $year = explode("-",$d);
            return mktime(0,0,0,$year[1],$year[2],$year[0]);
        }
    }
}

function dateadd5($interval, $number, $date)
{
    $date = gettime5($date);
    $date_time_array = getdate($date);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    switch ($interval)
    {
        case "yyyy": $year +=$number; break;
        case "q": $month +=($number*3); break;
        case "m": $month +=$number; break;
        case "y":
        case "d":
        case "w": $day+=$number; break;
        case "ww": $day+=($number*7); break;
        case "h": $hours+=$number; break;
        case "n": $minutes+=$number; break;
        case "s": $seconds+=$number; break;
    }
    $timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year);
    return $timestamp;
}
?>