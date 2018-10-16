<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
//---- 资源参数 ----
$CUR_DATE = date("Y-m-d", time());

$query = "select * from OA_SOURCE where SOURCEID='$SOURCEID'";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor))
{
    $SOURCENAME = $ROW["SOURCENAME"];
    $DAY_LIMIT = $ROW["DAY_LIMIT"];
    $WEEKDAY_SET = $ROW["WEEKDAY_SET"];
    $TIME_TITLE = $ROW["TIME_TITLE"];
    $MANAGE_USER = $ROW["MANAGE_USER"];
}

$TIME_ARRAY = explode(",", $TIME_TITLE);
$ARRAY_COUNT = sizeof($TIME_ARRAY);
if ($TIME_ARRAY[$ARRAY_COUNT - 1] == "")
    $ARRAY_COUNT--;
$SMS_CHEXIAO_TIME="";
$SMS_TIME="";
$SMS_CHEXIAO_USER="";
for ($I = 0; $I < $DAY_LIMIT; $I++)
{ //循还XX天
    $APPLY_DATE = time() + $I * 24 * 3600;
    if (!find_id($WEEKDAY_SET, date("w", $APPLY_DATE)))
    {
        $DAY_LIMIT++;
        continue;
    }

    $APPLY_DATE = date("Y-m-d", $APPLY_DATE);

    $USER_ID = "";
    $COLOR_STR="";
    for ($J = 0; $J < $ARRAY_COUNT; $J++)
    {
        $STR = $APPLY_DATE . "_" . $J;
        $STR_VALUE = $$STR;
        if ($STR_VALUE < 0)
            $STR_VALUE = "0";
        elseif ($STR_VALUE == 1)
            $STR_VALUE = $_SESSION["LOGIN_USER_ID"];

        $USER_ID.=$STR_VALUE . ",";
        //撤销颜色标识
        $COLOR=$APPLY_DATE . "_" . $J."_COLOR";

        $COLOR_VALUE=$$COLOR;
        $COLOR_STR.=$COLOR_VALUE.",";
    }


    $COLOR_STR=substr($COLOR_STR, 0, -1);
    $COLOR_STR_ARR=explode(",",$COLOR_STR);
    $USER_ID_STR = substr($USER_ID, 0, -1);
    $USER_ID_ARR = explode(",", $USER_ID_STR);
    $NEW_USER_ID_STR="";
    //查询该日的用户
    $query = "select * from OA_SOURCE_USED where SOURCEID='$SOURCEID' and APPLY_DATE='$APPLY_DATE'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $OLD_USER_ID = $ROW["USER_ID"];
        $OLD_USER_ID_STR = substr($OLD_USER_ID, 0, -1);
        $OLD_USER_ID_ARR = explode(",", $OLD_USER_ID_STR);
        for ($i = 0; $i < count($USER_ID_ARR); $i++)
        {

            if(($OLD_USER_ID_ARR[$i] != "0") && ($OLD_USER_ID_ARR[$i] != $USER_ID_ARR[$i]) &&($USER_ID_ARR[$i] != "0"))
            {

                // Message("",_("资源已经被占用"));
                //echo _("<center><input type='button' class='SmallButton' value='返回' onclick='location=\"apply.php?SOURCEID=$SOURCEID\"' /></center>");
                //exit;
                $USER_ID_ARR[$i]=$OLD_USER_ID_ARR[$i]; //资源如果有人使用的时候不能申请成功
            }
            // 拼新的user_id串
            if($OLD_USER_ID_ARR[$i]!="0" && $USER_ID_ARR[$i]=="0") // 本身有值，而新的为空
            {
                $USER_ID_ARR[$i]=$OLD_USER_ID_ARR[$i];
            }
            //撤销的情况
            if($COLOR_STR_ARR[$i]=="#ff33ff" && $USER_ID_ARR[$i]==$OLD_USER_ID_ARR[$i]) //点击撤销 背景颜色为粉红色
            {
                $USER_ID_ARR[$i]="0";
                $SMS_CHEXIAO_TIMEN="(".$APPLY_DATE.")[".$TIME_ARRAY[$i]."]"; //撤销时间段
                $SMS_CHEXIAO_USERN=$OLD_USER_ID_ARR[$i];//使用人
            }
            //事务提醒时间段
            if($USER_ID_ARR[$i]!=$OLD_USER_ID_ARR[$i] && $USER_ID_ARR[$i]!="0")
            {
                $SMS_TIME.="(".$APPLY_DATE.")"."[".$TIME_ARRAY[$i]."],";	//新增时间段
            }
            $NEW_USER_ID_STR.=$USER_ID_ARR[$i].",";
            if($SMS_CHEXIAO_TIMEN!="")
            {
                $SMS_CHEXIAO_TIME.=$SMS_CHEXIAO_TIMEN.",";
                $SMS_CHEXIAO_USER.=$SMS_CHEXIAO_USERN.",";
            }
            $SMS_CHEXIAO_TIMEN="";
            $SMS_CHEXIAO_USERN="";
        }

        $query = "update OA_SOURCE_USED set USER_ID='$NEW_USER_ID_STR' where SOURCEID='$SOURCEID' and APPLY_DATE='$APPLY_DATE'";
        exequery(TD::conn(), $query);
    } else
    {
        $NEW_USER_ID_STR=$USER_ID;
        $query = "insert into OA_SOURCE_USED values('$SOURCEID','$APPLY_DATE','$NEW_USER_ID_STR')";
        exequery(TD::conn(), $query);
    }




//echo $query;
    /*
    //事务提醒
       $SMS_CONTENT = _("资源申请：") . $SOURCENAME;
       $CUR_DATE_READ = 0;
       if ($APPLY_DATE == $CUR_DATE)
       {
          $query = "select SMS2.SMS_ID from SMS2,SMS where SMS2.FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and SMS2.CONTENT='$SMS_CONTENT' and to_days(SMS2.SEND_TIME)=to_days('$APPLY_DATE') and SMS.REMIND_FLAG=0 and SMS.SMS_ID=SMS2.SMS_ID";
          $cursor = exequery(TD::conn(), $query);
          if ($ROW = mysql_fetch_array($cursor))
             $CUR_DATE_READ = 1;
       }

       delete_remind_sms(0, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $APPLY_DATE);

       if (find_id($USER_ID, $_SESSION["LOGIN_USER_ID"]) && $MANAGE_USER != "")
        {
          if ($APPLY_DATE == $CUR_DATE)
          {
             if (!$CUR_DATE_READ)
                send_sms("", $_SESSION["LOGIN_USER_ID"], $MANAGE_USER,76, $SMS_CONTENT,$REMIND_URL);
          }
          else

             send_sms($APPLY_DATE, $_SESSION["LOGIN_USER_ID"], $MANAGE_USER,76, $SMS_CONTENT,$REMIND_URL);
       }
      */
}
//事务提醒
$SMS_TIME=substr($SMS_TIME, 0, -1);

if($SMS_TIME!="" && $MANAGE_USER != "")
{
    $MSG_APPLY = sprintf(_("资源申请：%s，申请日期时间段:"),$SOURCENAME);
    $SMS_CONTENT = $MSG_APPLY.$SMS_TIME;
    $REMIND_URL="1:source/manage/apply.php?SOURCEID=$SOURCEID";
    send_sms("",$_SESSION["LOGIN_USER_ID"], $MANAGE_USER,76, $SMS_CONTENT,$REMIND_URL,$SOURCEID);

}
if($SMS_CHEXIAO_TIME!="")
{
    //撤销提醒
    $SMS_CHEXIAO_TIME=substr($SMS_CHEXIAO_TIME, 0, -1);
    $SMS_CHEXIAO_USER=substr($SMS_CHEXIAO_USER, 0, -1);
    $SMS_CHEXIAO_USER_ARRAY=explode(",",$SMS_CHEXIAO_USER);
    $SMS_CHEXIAO_TIME_ARRAY=explode(",",$SMS_CHEXIAO_TIME);
    for($S=0;$S<count($SMS_CHEXIAO_TIME_ARRAY);$S++)
    {
        if($SMS_CHEXIAO_USER_ARRAY[$S]!=$_SESSION["LOGIN_USER_ID"] && find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"]))
        {

            $MSG_RETURN = sprintf(_("资源申请撤销：%s，撤销申请日期时间段:"),$SOURCENAME);
            $SMS_CONTENT = $MSG_RETURN.$SMS_CHEXIAO_TIME_ARRAY[$S];
            send_sms("",$_SESSION["LOGIN_USER_ID"], $SMS_CHEXIAO_USER_ARRAY[$S],76, $SMS_CONTENT,$REMIND_URL,$SOURCEID);
        }

    }
}

Header("location:apply.php?SOURCEID=$SOURCEID");
?>