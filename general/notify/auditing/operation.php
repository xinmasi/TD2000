<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("公告通知审批");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
if ($OP=="0")
{
   $query="update NOTIFY set PUBLISH='3',AUDITER='".$_SESSION["LOGIN_USER_ID"]."',AUDIT_DATE='$CUR_DATE',REASON='$REASON' where NOTIFY_ID='$NOTIFY_ID'";
   exequery(TD::conn(),$query);
   
  $query="select FROM_ID,SUBJECT,BEGIN_DATE from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $FROM_ID=$ROW["FROM_ID"];
    $SUBJECT=$ROW["SUBJECT"];
	  $BEGIN_DATE=$ROW["BEGIN_DATE"];
  }
  $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
  if(compare_date($BEGIN_DATE,$CUR_DATE)==1)
     $SEND_TIME=$BEGIN_DATE;
  
  $SUBJECT=str_replace("'","\'",$SUBJECT);
  $REMIND_URL="1:notify/manage/index.php";  
  $SMS_CONTENT1=sprintf(_("您提交的公告通知，标题：%s审批未通过，原因是：%s"),csubstr($SUBJECT,0,100),$REASON);
  send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$FROM_ID,1,$SMS_CONTENT1,$REMIND_URL); 
  if($FROM!=1)
  header("location: unaudited.php?IS_MAIN=1");
  else
  {
  	?>
  	   <script>
  	   	  window.opener.location.reload();
          window.close();	
  	   </script>
  <?
 }
}
else
{
  //----------- 合法性校验 ---------
 
  if($BEGIN_DATE=="")
     $BEGIN_DATE=date("Y-m-d",time());
  
  if($END_DATE=="")
  {
    $END_DATE="0000-00-00";
  }  
  
  if($TOP=="on")
     $TOP='1';
  else
  {
     $TOP='0';
     $TOP_DAYS="";
  }

  $BEGIN_DATE=strtotime($BEGIN_DATE);
  
  $END_DATE=strtotime($END_DATE);
  
  $query="update NOTIFY set PUBLISH='1',AUDITER='".$_SESSION["LOGIN_USER_ID"]."',AUDIT_DATE='$CUR_DATE',TOP='$TOP',TOP_DAYS='$TOP_DAYS',SEND_TIME='$SEND_TIME',END_DATE='$END_DATE',BEGIN_DATE='$BEGIN_DATE' where NOTIFY_ID='$NOTIFY_ID'";
  exequery(TD::conn(),$query);
    
if($SMS_REMIND=="on" || $SMS2_REMIND=="on" || $SNS_REMIND=="on")
{
    $query="select * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $FROM_ID=$ROW["FROM_ID"];
        $TO_ID=$ROW["TO_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $SUMMARY=$ROW["SUMMARY"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $PRIV_ID=$ROW["PRIV_ID"];
        $TO_USER_ID=$ROW["USER_ID"];
    }
    
    if($SMS_REMIND=="on" || $SMS2_REMIND=="on") 
    {
        $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
        $USER_NAME=td_trim(GetUserNameById($FROM_ID));  
        $SUBJECT=str_replace("'","\'",$SUBJECT);
        $SMS_CONTENT=_("请查看公告通知！")."\n"._("标题：").csubstr($SUBJECT,0,100);
        if($SUMMARY)
            $SMS_CONTENT .= "\n"._("内容简介：") . $SUMMARY;
        if(compare_date($BEGIN_DATE,$CUR_DATE)==1)
            $SEND_TIME=$BEGIN_DATE." 08:00:00";
        if($TO_ID=="ALL_DEPT")
            $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0)";
        else
            $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and (find_in_set(DEPT_ID,'$TO_ID') or find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$TO_USER_ID'))";
        
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $USER_ID_STR.=$ROW["USER_ID"].",";
        
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
        //.............
        $REMIND_URL="1:notify/show/read_notify.php?NOTIFY_ID=".$NOTIFY_ID;
        $SMS_CONTENT1=sprintf(_("您提交的公告通知，标题：%s审批已通过。"),csubstr($SUBJECT,0,100));
        send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$FROM_ID,1,$SMS_CONTENT1,"");
        if($SMS_REMIND=="on")
        {
            if($USER_ID_STR!="")   
                send_sms($SEND_TIME,$FROM_ID,$USER_ID_STR,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
        }
        if($SMS2_REMIND=="on")
        {
            $SMS_CONTENT=sprintf(_("OA公告,来自%s，标题:%s"),$USER_NAME,$SUBJECT);
            if($USER_ID_STR!="")  
                send_mobile_sms_user($SEND_TIME,$FROM_ID,$USER_ID_STR,$SMS_CONTENT,1);
        }
        include_once("inc/itask/itask.php");
        mobile_push_notification(UserId2Uid($USER_ID_STR), GetUserNameById($FROM_ID)._("：")._("请查看公告通知！")._("标题：").csubstr($SUBJECT,0,20), "notify",true); 
         //微信公告
         /*$WX_OPTIONS = array(
             "module" => "news",
             "module_action" => "notify.create",
             "user" => $USER_ID_STR,
             "content" => $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看公告！")._("标题：").csubstr($SUBJECT,0,20),
             "params" => array(
                  "NOTIFY_ID" => $NOTIFY_ID
              )
          );
          WXQY_NOTIFY($WX_OPTIONS);*/
    }
    //分享到企业社区
    if($SNS_REMIND == 'on')
    {
        $push_arr = array(
            'module' => 'notify',
            'mid' => $NOTIFY_ID,
            'user_id' => $TO_USER_ID,
            'priv_id' => $PRIV_ID,
            'dept_id' => $TO_ID,
            'subject' => $SUBJECT
        );
        push_to_wxshare($push_arr);
    }
}
    
if($FROM!=1)
{
header("location: unaudited.php?IS_MAIN=1");
}
else
{
  ?>
  <script>
  	     window.opener.location.reload();
         window.close();	
  	
  </script>
   
<?	
}
}
?>
<body>