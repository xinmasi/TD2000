<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");

if($PAIBAN_ID=="")
{
	$WIN_TITLE=_("值班安排");
	$type="add";
}
else
{
	$WIN_TITLE=_("修改值班");
	$type="edit";
}

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
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
if($ZBSJ_E=="" || !is_date_time($ZBSJ_E))
{
   Message(_("错误"),_("结束时间格式不对"));
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

$query = "SELECT DEPT_ID from USER where USER_ID='$ZHIBANREN'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
   $DEPT_ID=$ROW["DEPT_ID"];
//检验时间是否冲突
if(!checkTimeClash($ZHIBANREN,$ZBSJ_B,$ZBSJ_E)){
    Message(_("错误"),_("排班时间冲突!"));
    Button_Back ();
    exit();
}
if($PAIBAN_ID=="")
   $query="insert into ZBAP_PAIBAN(ZHIBANREN,ZHIBANREN_DEPT,PAIBAN_TYPE,ZHIBAN_TYPE,ZBSJ_B,ZBSJ_E,ZBYQ,BEIZHU,PAIBAN_APR,ANPAI_TIME,REMIND_TYPE,HAS_REMINDED) 
           values ('$ZHIBANREN','$DEPT_ID','$PAIBAN_TYPE','$ZHIBAN_TYPE','$ZBSJ_B','$ZBSJ_E','$ZBYQ','$BEIZHU','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$REMIND_TYPE','0')";
else
   $query="update ZBAP_PAIBAN set ZHIBANREN='$ZHIBANREN',ZHIBANREN_DEPT='$DEPT_ID',PAIBAN_TYPE='$PAIBAN_TYPE',ZHIBAN_TYPE='$ZHIBAN_TYPE',ZBSJ_B='$ZBSJ_B',ZBSJ_E='$ZBSJ_E',ZBYQ='$ZBYQ',BEIZHU='$BEIZHU',REMIND_TYPE='$REMIND_TYPE',HAS_REMINDED='0' where PAIBAN_ID='$PAIBAN_ID'";   
exequery(TD::conn(),$query);
if($type=="add")
	$PAIBAN_ID=mysql_insert_id();
if($SMS_REMIND=="on")
{
   $REMIND_URL="1:attendance/personal/on_duty/note.php?PAIBAN_ID=".$PAIBAN_ID;
   $SMS_CONTENT=_("您有值班安排,请查看");      
   if($ZHIBANREN!="")
      send_sms("",$_SESSION["LOGIN_USER_ID"],$ZHIBANREN,55,$SMS_CONTENT,$REMIND_URL,$PAIBAN_ID);
}   
if($SMS2_REMIND=="on")
{
   $SMS_CONTENT=_("您有值班安排,请查看");
   if($ZHIBANREN!="")
      send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$ZHIBANREN,$SMS_CONTENT,55);
}   
Message("",_("保存成功"));
?>
<script Language="JavaScript">
	if(window.parent.opener.location.href.indexOf("connstatus") < 0 ){
	    var href = window.parent.opener.location.href;
	    if(href.indexOf("?") < 0){
            href = href+"?";
        }
        window.parent.opener.location.href = href+"&connstatus=1";
	}else{
		window.parent.opener.location.reload();
	}
</script>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();"></center>

</body>
</html>
