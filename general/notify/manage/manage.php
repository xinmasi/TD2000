<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_DATE_U=time();
$CUR_DATE=date("Y-m-d",time());
//$END_DATE=date("Y-m-d",time()-24*60*60);
//$END_DATE=time()-24*60*60;
$END_DATE=time();
$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");

if($OPERATION==1)
{ 
   $query="select BEGIN_DATE,SUBJECT,SUMMARY,TO_ID,PRIV_ID,USER_ID from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $BEGIN_DATE=$ROW["BEGIN_DATE"];
      $SUBJECT1=$ROW["SUBJECT"];
      $SUMMARY=$ROW["SUMMARY"];
      $TO_ID1=$ROW["TO_ID"];
      $PRIV_ID=$ROW["PRIV_ID"];
      $USER_ID=$ROW["USER_ID"];
      $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
      $SMS_CONTENT=_("请查看公告通知！")."\n"._("标题：").csubstr($SUBJECT1,0,100);
      if($SUMMARY)
         $SMS_CONTENT .= "\n"._("内容简介：") . $SUMMARY;
      $BEGIN_DATE=$BEGIN_DATE." 08:00:00";
      delete_remind_sms(1, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $BEGIN_DATE);
   }
   $query="update NOTIFY set BEGIN_DATE='$CUR_DATE_U' where NOTIFY_ID='$NOTIFY_ID'";
   exequery(TD::conn(),$query);
   //发送事务提醒
   if($TO_ID1=="ALL_DEPT")
      $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0)";
   else
      $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and (find_in_set(DEPT_ID,'$TO_ID1') or find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$COPY_TO_ID'))";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
      $USER_ID_STR.=$ROW["USER_ID"].",";
   //辅助角色
   $MY_ARRAY=explode(",",$PRIV_ID);
   $ARRAY_COUNT=sizeof($MY_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($MY_ARRAY[$I]=="")
  	     continue;
  	   $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and find_in_set('$MY_ARRAY[$I]',USER_PRIV_OTHER)";
      $cursor=exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))   
      {
        if(!find_id($USER_ID_STR,$ROW["USER_ID"]))
     	     $USER_ID_STR.=$ROW["USER_ID"].",";     	
      }          	 
   }
   //辅助部门
   $MY_ARRAY_DEPT=explode(",",$TO_ID1);
   $ARRAY_COUNT_DEPT=sizeof($MY_ARRAY_DEPT);
   for($I=0;$I<$ARRAY_COUNT_DEPT;$I++)
   {
     if($MY_ARRAY_DEPT[$I]=="")
  	    continue;
  	 $query_d="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and find_in_set('$MY_ARRAY_DEPT[$I]',DEPT_ID_OTHER)";
     $cursor_d=exequery(TD::conn(),$query_d);
     while($ROWD=mysql_fetch_array($cursor_d))   
     {
        if(!find_id($USER_ID_STR,$ROWD["USER_ID"]))
     	     $USER_ID_STR.=$ROWD["USER_ID"].",";     	
     }          	 
   }
   //排除没有公告通知菜单权限的人 
   $USER_ID_STR_ARRAY=explode(",",$USER_ID_STR);
   $USER_ID_STR_ARRAY_COUNT=sizeof($USER_ID_STR_ARRAY);
   for($I=0;$I<$USER_ID_STR_ARRAY_COUNT;$I++)
   {
  	    if($USER_ID_STR_ARRAY[$I]=="")
  	      continue;
  	    $FUNC_ID_STR=GetfunmenuByuserID($USER_ID_STR_ARRAY[$I]);
  	    if(!find_id($FUNC_ID_STR,4))
  	       $USER_ID_STR=str_replace($USER_ID_STR_ARRAY[$I],'',$USER_ID_STR);  	 	
  	 
   }
    $REMIND_URL="1:notify/show/read_notify.php?NOTIFY_ID=".$NOTIFY_ID;
    $SEND_TIME=date("Y-m-d H:i:s");
    if($USER_ID_STR!="")
       send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
   
}
else if($OPERATION==2)
   $query="update NOTIFY set END_DATE='$END_DATE' where NOTIFY_ID='$NOTIFY_ID'";
else
   $query="update NOTIFY set END_DATE='0' where NOTIFY_ID='$NOTIFY_ID'";

exequery(TD::conn(),$query);
$url="search.php?start=$start&SEARCH=1&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&SUBJECT=$SUBJECT&CONTENT=$CONTENT&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&PUBLISH=$PUBLISH&TOP=$TOP&TO_ID=$TO_ID&STAT=$STAT";

if($SEARCH==1)
{
    header("location: search.php?start=$start&SEARCH=1&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&SUBJECT=$SUBJECT&CONTENT=$CONTENT&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&PUBLISH=$PUBLISH&TOP=$TOP&TO_ID=$TO_ID&STAT=$STAT&IS_MAIN=1");
}
else
{

if($FROM!=2)
  header("location: index1.php?start=$start&IS_MAIN=1");
else
 header("location:../auditing/audited.php?start=$start&IS_MAIN=1");
}
?>

</body>
</html>
