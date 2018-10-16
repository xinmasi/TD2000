<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("sql_inc.php");

$HTML_PAGE_TITLE = _("发布公告通知");
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script language="javascript">
  function close_this()
  {
  	var url_ole=window.opener.location.href;
  	var url_search=window.opener.location.search;
  	if(url_ole.indexOf("?IS_MAIN=1")>0 || url_search.indexOf("&IS_MAIN=1")>0)
  	   window.opener.location.reload();
  	else
  	{
  		 if(url_search=="")
  		   window.opener.location.href=url_ole+"?IS_MAIN=1";
  		 esle
  		   window.opener.location.href=url_ole+"&IS_MAIN=1";      
  		   
  	}   
    //window.opener.location.reload();
     TJF_window_close();

  }
 </script>  

<body class="bodycolor">
<?
//----------- 合法性校验 ---------
$CUR_DATE=date("Y-m-d",time());
$BEGIN_DATE1=$BEGIN_DATE;
if($BEGIN_DATE=="")
{
   $BEGIN_DATE=$CUR_DATE;
   $BEGIN_DATE1=$CUR_DATE;
}
if($END_DATE=="")
  $END_DATE="0000-00-00";
  
$BEGIN_DATE=strtotime($BEGIN_DATE);
$END_DATE=strtotime($END_DATE);
  
$SUBJECT1=$SUBJECT;
$CONTENT=strip_unsafe_tags($CONTENT);
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
//$ATTACHMENT_ID=copy_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

if($FORMAT=="1")
   $CONTENT="";
$CONTENT=str_replace("http://".$HTTP_HOST."/inc/attach.php?","/inc/attach.php?",$CONTENT);
$CONTENT=str_replace("http://".$HTTP_HOST."/module/editor/plugins/smiley/images/","/module/editor/plugins/smiley/images/",$CONTENT);    
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CONTENT);
if($C==1)
{
    $CONTENT = replace_attach_url($CONTENT);
    //$CONTENT = replace_attach_url($CONTENT);
    //$CONTENT = preg_replace();
    $ATTACHMENT_ID = move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","").",";
}
if($PRINT=="on")
   $PRINT='1';
else
   $PRINT='0';
if($DOWNLOAD=="on")
   $DOWNLOAD='1';
else
   $DOWNLOAD='0'; 
if($TOP=="on")
{
   $TOP='1';
}
else
{
   $TOP='0';
   $TOP_DAYS="";
}

if ($IS_AU==0)
{
	$AUDITER="";
}

if($SEND_TIME=="" )
    $SEND_TIME=date("Y-m-d H:i:s",time());
//-------发布公告-------
if($FORMAT!=2)
{
   $CONTENT=strip_unsafe_tags($CONTENT);
   $CONTENT = stripslashes($CONTENT);
   $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
   $CONTENT = addslashes(strip_tags($CONTENT));
   $DATA="FROM_DEPT,FROM_ID,TO_ID,SUBJECT,SUMMARY,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,ATTACHMENT_ID,ATTACHMENT_NAME,PRINT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,AUDITER,COMPRESS_CONTENT,DOWNLOAD,SUBJECT_COLOR,KEYWORD";
   $DATA_VALUE="'".$_SESSION["LOGIN_DEPT_ID"]."','".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$SUBJECT','$SUMMARY','$CONTENT','$SEND_TIME','$BEGIN_DATE','$END_DATE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$PRINT','$FORMAT','$TOP','$TOP_DAYS','$PRIV_ID','$COPY_TO_ID','$TYPE_ID','$PUBLISH','$AUDITER',0x$COMPRESS_CONTENT,'$DOWNLOAD','$SUBJECT_COLOR','$KEYWORD'";
   $NOTIFY_ID=insert_notify($DATA,$DATA_VALUE);
}
else
{  
   $DATA="FROM_DEPT,FROM_ID,TO_ID,SUBJECT,SUMMARY,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,ATTACHMENT_ID,ATTACHMENT_NAME,PRINT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,AUDITER,DOWNLOAD,SUBJECT_COLOR,KEYWORD";
   $DATA_VALUE="'".$_SESSION["LOGIN_DEPT_ID"]."','".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$SUBJECT','$SUMMARY','$URL_ADD','$SEND_TIME','$BEGIN_DATE','$END_DATE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$PRINT','$FORMAT','$TOP','$TOP_DAYS','$PRIV_ID','$COPY_TO_ID','$TYPE_ID','$PUBLISH','$AUDITER',',$DOWNLOAD','$SUBJECT_COLOR','$KEYWORD'";
   $NOTIFY_ID=insert_notify($DATA,$DATA_VALUE);
}
//-------事务提醒-------
if($PUBLISH=="1" && $OP!="0" &&($SMS_REMIND1=="on" || $SMS2_REMIND1=="on"))
{
   $SMS_CONTENT=_("请查看公告通知！")."\n"._("标题：").csubstr($SUBJECT1,0,100);
    if(mb_detect_encoding($SMS_CONTENT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SMS_CONTENT = stripslashes($SMS_CONTENT);
        $SUMMARY     = stripslashes($SUMMARY);
    }
   if($SUMMARY)
      $SMS_CONTENT .= "\n"._("内容简介：") . $SUMMARY;
   if(compare_date($BEGIN_DATE1,$CUR_DATE)==1)//生效日期大于当前日期
      $SEND_TIME=$BEGIN_DATE1. "08:00:00";   
   if($TO_ID=="ALL_DEPT")
      $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) ";
   else
      $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and (find_in_set(DEPT_ID,'$TO_ID') or find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$COPY_TO_ID'))";
   $cursor=exequery(TD::conn(),$query);
   
   while($ROW=mysql_fetch_array($cursor))
      $USER_ID_STR.=$ROW["USER_ID"].",";
//-------辅助角色-------
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
//-------辅助部门-------
   if($TO_ID!="ALL_DEPT")
   {
	   $MY_ARRAY_DEPT=explode(",",$TO_ID);
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
	}
//-------排除没有公告通知菜单权限的人------- 
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
   if($SMS_REMIND1=="on")
   {    
      if($USER_ID_STR!="")
         send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
   }
   if($SMS2_REMIND1=="on")
   {
      $SMS_CONTENT=sprintf(_("OA公告,来自%s，标题:%s"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT1);
      if($SUMMARY)
         $SMS_CONTENT .= _("内容简介:").$SUMMARY;
        
      if($USER_ID_STR!="")
         send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,1);
  }
  include_once("inc/itask/itask.php");
  mobile_push_notification(UserId2Uid($USER_ID_STR), $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看公告通知！")._("标题：").csubstr($SUBJECT1,0,20), "notify"); 
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
//-------分享到企业社区
if($PUBLISH=="1" && $OP!="0" && $SNS_REMIND=="on")
{
    if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SUBJECT = stripslashes($SUBJECT);
    }
    $push_arr = array(
        'module' => 'notify',
        'mid' => $NOTIFY_ID,
        'user_id' => $COPY_TO_ID,
        'priv_id' => $PRIV_ID,
        'dept_id' => $TO_ID,
        'subject' => $SUBJECT
    );
    push_to_wxshare($push_arr);
}
//-------提交审批事务提醒--------
if($PUBLISH=="2")
{
    include_once("inc/flow_hook.php");
    $notify_hooked = get_sys_para("NOTIFY_HOOKED");
    $notify_hooked = $notify_hooked["NOTIFY_HOOKED"];
    $is_hook_notify = 1;
    
    if($notify_hooked)
    {
        $params = array(
            'NOTIFY_ID' => $NOTIFY_ID,
            'NOTIFY_AUDITER' => $AUDITER
        );
        $is_hook_notify = bmp_hook_notify($params);
    }
    
    if(($SMS_REMIND=="on" || $SMS2_REMIND=="on") && $is_hook_notify)//在公告通知设置里，选择了‘开启业务流程平台对通知公告进行审批’选项后，新建通知公告却没有事务提醒。去掉了if (!$is_hook_notify &&（ ... ）){ ... }的if判断条件.2016.10.17。闫希鹏
    {
        $SMS_CONTENT=_("请审批公告通知！")."\n"._("标题：").csubstr($SUBJECT1,0,100);
        $REMIND_URL="1:notify/auditing/unaudited.php";
        if($SMS_REMIND=="on")
        {
            if($AUDITER!="")
                send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$AUDITER,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
        }
        if($SMS2_REMIND=="on")
        {
            $SMS_CONTENT=sprintf(_("请审批OA公告,来自%s，标题:%s"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT1);
            if($SUMMARY)
                $SMS_CONTENT.= _("内容简介:").$SUMMARY;
            if($AUDITER!="")
                send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$AUDITER,$SMS_CONTENT,1);
        }
    }
}
if($OP==0 )
{  
   if($OP1==1)	
      header("location: modify.php?NOTIFY_ID=$NOTIFY_ID&FROM=1");
   else
      Message("",_("公告保存成功！"));
 ?>
 <br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='modify.php?NOTIFY_ID=<?=$NOTIFY_ID?>&FROM=1';"><!--<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this();">--></center>   
 <?
}
else
{
   if($PUBLISH=="2")
      Message("",_("公告已提交审批！"));
   else
      Message("",_("公告发布成功！"));
   ?>
   
   <br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='index1.php?start=<?=$start?>&IS_MAIN=1'"></center> 
   
   <!--<br><center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this()"></center> -->
	 
<? 	 
}
?>
</body>
</html>
