<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

if($CAL_ID=="")
   $WIN_TITLE=_("新建事务");
else
   $WIN_TITLE=_("编辑事务");
$CAL_OLD_ID=$CAL_ID;

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}

$CAL_TIME = date("Y-m-d H:i:s",strtotime($CAL_TIME." ".$CAL_TIME_HOUR));
$END_TIME = date("Y-m-d H:i:s",strtotime($END_TIME." ".$END_TIME_HOUR));

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//------------------- 保存 -----------------------
//if($CAL_TIME=="" || !is_date_time($CAL_TIME))
//{
//   $MSG1 = sprintf(_("起始时间格式不对，应形如%s"), $CUR_TIME);
//   Message(_("错误"),$MSG1);
//   Button_Back();
//   exit; 
//}
//if($END_TIME=="" || !is_date_time($END_TIME))
//{
//   $MSG2 = sprintf(_("结束时间格式不对，应形如%s"), $CUR_TIME);
//   Message(_("错误"),$MSG2);
//   Button_Back();
//   exit; 
//}

if($CAL_TIME >= $END_TIME)
{
   Message(_("错误"),_("开始时间晚于结束时间！"));
   Button_Back();
   exit;
}

$ADD_TIME=date("Y-m-d H:i:s");
if($CAL_ID!="")
{
   $query="select * from CALENDAR where CAL_ID='$CAL_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $CAL_TIME1=$ROW["CAL_TIME"];
      $CAL_TIME1=date("Y-m-d H:i:s",$CAL_TIME1);
      $CONTENT1=$ROW["CONTENT"];
      $SMS_CONTENT1=csubstr($CONTENT1,0,100);
      $SMS_CONTENT2=$CONTENT1;
      
      $MSG3 = sprintf(_("OA日程安排:%s为您安排新的工作，内容：%s"), $_SESSION["LOGIN_USER_NAME"],$CONTENT1);
      $SMS_CONTENT3=$MSG3;
   }

   delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT1, $CAL_TIME1);
   DelSMS2byContent($_SESSION["LOGIN_USER_ID"], $SMS_CONTENT3);
   
   $query="delete from SMS2 where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and SEND_TIME='$CAL_TIME1' and CONTENT like '%$SMS_CONTENT2%'";
   exequery(TD::conn(),$query);
}
$CAL_TIME_C=strtotime($CAL_TIME);
$END_TIME_C=strtotime($END_TIME);
if($CAL_ID=="")
{
   $USER_ARRAY=explode(",",$USER_ID);
   foreach($USER_ARRAY as $USER_ID)
   {
      if($USER_ID=="")
         continue;
      $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,MANAGER_ID,OVER_STATUS,ADD_TIME) values ('$USER_ID','$CAL_TIME_C','$END_TIME_C','1','$CAL_LEVEL','$CONTENT','".$_SESSION["LOGIN_USER_ID"]."','0','$ADD_TIME')";
      exequery(TD::conn(),$query);
         
      $CAL_ID=mysql_insert_id();
      if($SMS_REMIND=="on")
      {
         $REMIND_URL="1:calendar/arrange/note.php?CAL_ID=".$CAL_ID;
         $SMS_CONTENT=sprintf(_("%s为您安排新的工作。"), $_SESSION["LOGIN_USER_NAME"])."\n"._("内容：").csubstr($CONTENT,0,100);
         send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
         if($CAL_TIME>$CUR_TIME)
            send_sms($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
        
      }
      $SMS_CONTENT1=sprintf(_("%s为您安排新的工作。"), $_SESSION["LOGIN_USER_NAME"])."\n"._("内容：").csubstr($CONTENT,0,10);
      include_once("inc/itask/itask.php");
      mobile_push_notification(UserId2Uid($USER_ID), $SMS_CONTENT1, "calendar");
      //------- 手机短信提醒 --------
      if($SMS2_REMIND=="on")
      {
				 $MSG4 = sprintf(_("OA日程安排:%s为您安排新的工作，内容：%s"), $_SESSION["LOGIN_USER_NAME"],$CONTENT);
         $SMS_CONTENT=$MSG4;
         send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
         if($CAL_TIME>$CUR_TIME)
            send_mobile_sms_user($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
      }
   }
}
else
{
	 $CAL_ID_ARRAY=explode(",",$CAL_ID_STR);
	 foreach($CAL_ID_ARRAY as $CAL_ID_STR)
   {
      if($CAL_ID_STR=="")
         continue;
      $query="update CALENDAR set CAL_TIME='$CAL_TIME_C',END_TIME='$END_TIME_C',CAL_LEVEL='$CAL_LEVEL',CONTENT='$CONTENT',ADD_TIME='$ADD_TIME' where CAL_ID='$CAL_ID_STR'";
      exequery(TD::conn(),$query);
      
      $querys="select USER_ID from CALENDAR where CAL_ID='$CAL_ID_STR'";
      $curson=exequery(TD::conn(),$querys);
      if($ROWS=mysql_fetch_array($curson))
         $USER_ID=$ROWS['USER_ID'];
      
   if($SMS_REMIND=="on")
   {
   	
      $REMIND_URL="1:calendar/arrange/note.php?CAL_ID=".$CAL_ID_STR;
      $SMS_CONTENT=sprintf(_("%s为您安排新的工作。"), $_SESSION["LOGIN_USER_NAME"])."\n"._("内容：").csubstr($CONTENT,0,100);
      send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
      if($CAL_TIME>$CUR_TIME)
         send_sms($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
      
   }
    $SMS_CONTENT1=sprintf(_("%s为您安排新的工作。"), $_SESSION["LOGIN_USER_NAME"])."\n"._("内容：").csubstr($CONTENT,0,10);
    include_once("inc/itask/itask.php");
    mobile_push_notification(UserId2Uid($USER_ID), $SMS_CONTENT1, "calendar");
  
   //------- 手机短信提醒 --------
   if($SMS2_REMIND=="on")
   {   
      $MSG5 = sprintf(_("OA日程安排:%s为您安排新的工作，内容：%s"), $_SESSION["LOGIN_USER_NAME"],$CONTENT);
      $SMS_CONTENT=$MSG5;
      send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
      if($CAL_TIME>$CUR_TIME)
         send_mobile_sms_user($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
     
   }
  }
}
Message("",_("保存成功"));

//Button_Back();
?>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();"></center>
<script Language="JavaScript">
<? 
//if($CAL_OLD_ID=="")
  // echo "window.parent.opener.location.reload();";  
//else
  // echo "window.opener.location.reload();"; 
  
  if($CAL_OLD_ID=="")
 {
 ?>
  	var url_ole=window.parent.opener.location.href;
  	var url_search=window.parent.opener.location.search;
	if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
		window.parent.opener.location.reload();
	else
	{
		if(url_search=="")
			window.parent.opener.location.href=url_ole+"?IS_MAIN=1";
		else
			window.parent.opener.location.href=url_ole+"&IS_MAIN=1"; 
	}
<? 	
 }else
 {
?>
	var url_ole=window.opener.location.href;
	var url_search=window.opener.location.search;
	if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
		window.opener.location.reload();
	else
	{
		if(url_search=="")
			window.opener.location.href=url_ole+"?IS_MAIN=1";
		else
			window.opener.location.href=url_ole+"&IS_MAIN=1";
		
	}
 
<?}?>
</script>
</body>
</html>
