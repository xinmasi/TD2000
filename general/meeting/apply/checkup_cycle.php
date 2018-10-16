<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

function check_room($M_ID,$M_ROOM,$M_START,$M_END)
{
    $query="select * from MEETING where M_ID!='$M_ID' and M_ROOM='$M_ROOM' and (M_STATUS=1 or M_STATUS=2)";
    $cursor=exequery(TD::conn(),$query);
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $M_START1=$ROW["M_START"];
        $M_END1=$ROW["M_END"];
        if(($M_START1>=$M_START and $M_END1<=$M_END) or ($M_START1<$M_START and $M_END1>$M_START) or ($M_START1<$M_END and $M_END1>$M_END) or ($M_START1<$M_START and $M_END1>$M_END))
        {
            $COUNT++;
            $M_IDD=$M_IDD.$ROW["M_ID"].",";
        }
    }
    
    $M_ID=$M_IDD;
    if($COUNT>=1)
    {
        return $M_ID;
    }
    else
    {
        return "#";
    }
}

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$M_STATUS=1;
$CHECK_STR= td_trim($CHECK_STR);
$CHECK_ARRAY = explode(",",$CHECK_STR);
$COUNT1=0;
foreach($CHECK_ARRAY as $key=> $value)
{
    $M_ID = $value;
    
    $query="select M_START,M_END,M_STATUS,M_ROOM,CALENDAR from MEETING where M_ID='$M_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_STATUS1  = $ROW["M_STATUS"];
        $M_ROOM     = $ROW["M_ROOM"];
        $M_START    = $ROW["M_START"];
        $M_END      = $ROW["M_END"];
        $CALENDAR   = $ROW["CALENDAR"];
    }

    $SS=substr(check_room($M_ID,$M_ROOM,$M_START,$M_END), 0, 1);
    if(!is_number($SS))
    {
        $COUNT1++;   
        $query="update MEETING set M_STATUS='$M_STATUS' where M_ID='$M_ID'";
        exequery(TD::conn(),$query);
        
        $query="select * from MEETING where M_ID='$M_ID'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $M_PROPOSER=$ROW["M_PROPOSER"];
            $M_ATTENDEE=$ROW["M_ATTENDEE"];
            $SMS_REMIND=$ROW["SMS_REMIND"];
            $SMS2_REMIND=$ROW["SMS2_REMIND"];
            $M_NAME=$ROW["M_NAME"];
            $M_ROOM=$ROW["M_ROOM"];
            $M_START2 = $M_START=$ROW["M_START"];   
            $M_END=$ROW["M_END"];   
            $RESEND_LONG=$ROW["RESEND_LONG"];
            $RESEND_LONG_FEN=$ROW["RESEND_LONG_FEN"];
            $RESEND_SEVERAL=$ROW["RESEND_SEVERAL"]; 
            
            if($RESEND_SEVERAL > 4)
            {
                $RESEND_SEVERAL = 4;
            }
            
            $query="select MR_NAME from MEETING_ROOM where MR_ID='$M_ROOM'";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $MR_NAME=$ROW["MR_NAME"];
            }
            
            $query="select USER_NAME from USER where USER_ID='$M_PROPOSER'";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $M_PROPOSER_NAME=$ROW["USER_NAME"];
            }
        }
        
        $CONTENT=_("会议:").$M_NAME;//.$M_ID._("：")
        $MY_ARRAY=td_trim($M_ATTENDEE);
        $MY_ARRAY=explode(",",$MY_ARRAY);
        $ARRAY_COUNT=sizeof($MY_ARRAY);
        $URL = '/general/meeting/query/meeting_detail.php?M_ID='.$M_ID;
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,FROM_MODULE,URL,M_ID) values ('$MY_ARRAY[$I]','".strtotime($M_START)."','".strtotime($M_END)."','1','1','$CONTENT','0','2','$URL','$M_ID')";
            exequery(TD::conn(),$query);
        }
    }
}

//header("location: query.php?M_STATUS=$M_STATUS");
Message(_("提示"),_("会议申请保存成功！"));
Button_Back();
?>
</body>
</html>
