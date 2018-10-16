<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("值班安排");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//检验时间是否冲突
function checkTimeClash($ZHIBANREN,$ZBSJ_B,$ZBSJ_E){
    $query  = "select PAIBAN_ID from ZBAP_PAIBAN where ZHIBANREN='$ZHIBANREN' and ((ZBSJ_B<'$ZBSJ_B' and ZBSJ_E>'$ZBSJ_B') or (ZBSJ_B<'$ZBSJ_E' and ZBSJ_E>'$ZBSJ_E'))";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor)){
        return false;
    }else{
        return true;
    }
}
$CUR_TIME=date("Y-m-d H:i:s",time());
//------------------- 保存 -----------------------
if($ZBSJ_B=="" || !is_date_time($ZBSJ_B))
{
   Message(_("错误"),_("时间格式不对"));
   Button_Back();
   exit; 
}
$REMIND_TYPE=0;
if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
	$REMIND_TYPE=3;
else if($SMS_REMIND=="on" && $SMS2_REMIND!="on")
	$REMIND_TYPE=1;
else if($SMS2_REMIND=="on" && $SMS_REMIND!="on")
	$REMIND_TYPE=2;
$ZHIBANREN_ARRAY=explode(",",$ZHIBANREN);
$ZHIBANREN_ARRAY_NUM=sizeof($ZHIBANREN_ARRAY);
if($ZHIBANREN_ARRAY[$ZHIBANREN_ARRAY_NUM-1]=="")$ZHIBANREN_ARRAY_NUM--;
$status = 0;
$count  = 0;
for($I=0;$I < $ZHIBANREN_ARRAY_NUM;$I++)
{
    $query1 = "SELECT DEPT_ID from USER where USER_ID='$ZHIBANREN_ARRAY[$I]'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $DEPT_ID=$ROW["DEPT_ID"];
    $add_hour1 = "+".($I * $TIME_LONG)." hour";
    $add_hour2 = "+".(($I+1) * $TIME_LONG)." hour";
    $ZBSJ_B_TMP = date('Y-m-d H:i:s',strtotime("$add_hour1",strtotime($ZBSJ_B)));
    $ZBSJ_E_TMP = date('Y-m-d H:i:s',strtotime("$add_hour2",strtotime($ZBSJ_B)));
    if(!checkTimeClash($ZHIBANREN_ARRAY[$I],$ZBSJ_B_TMP,$ZBSJ_E_TMP)){
        $ZHIBANREN_NAME = td_trim(GetUserNameByUserId($ZHIBANREN_ARRAY[$I]),",");
        Message(_("错误"),_($ZHIBANREN_NAME."的排班时间冲突!"));
        $status++;
        continue;
    }else{
        $count++;
        $query="insert into ZBAP_PAIBAN(ZHIBANREN,ZHIBANREN_DEPT,PAIBAN_TYPE,ZHIBAN_TYPE,ZBSJ_B,ZBSJ_E,ZBYQ,BEIZHU,PAIBAN_APR,ANPAI_TIME,REMIND_TYPE,HAS_REMINDED) values ('$ZHIBANREN_ARRAY[$I]','$DEPT_ID','$PAIBAN_TYPE','$ZHIBAN_TYPE','$ZBSJ_B_TMP','$ZBSJ_E_TMP','$ZBYQ','$BEIZHU','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$REMIND_TYPE','$HAS_REMINDED')";
        exequery(TD::conn(),$query);
    }
    $PAIBAN_ID=mysql_insert_id();
    if($SMS_REMIND=="on")
    {
        $REMIND_URL="1:attendance/personal/on_duty/note.php?PAIBAN_ID=".$PAIBAN_ID;
        $SMS_CONTENT=_("您有值班安排,请查看");
        if($ZHIBANREN_ARRAY[$I]!="")
            send_sms("",$_SESSION["LOGIN_USER_ID"],$ZHIBANREN_ARRAY[$I],55,$SMS_CONTENT,$REMIND_URL,$PAIBAN_ID);
    }
    if($SMS2_REMIND=="on")
    {
        $SMS_CONTENT=_("您有值班安排,请查看");
        if($ZHIBANREN_ARRAY[$I]!="")
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$ZHIBANREN_ARRAY[$I],$SMS_CONTENT,55);
    }
}
if($status == 0){
    Message("",_("保存成功"));
}else{
    if($count){
        Message("",_("有".$count."名人员的排班保存成功"));
    }
}
?>
<script Language="JavaScript">window.parent.opener.location.reload();</script>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="parent.close();"></center>

</body>
</html>
