<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
$CUR_TIME=date("Y-m-d H:i:s",time());


if($SIGNED_YN=="on")
   $SIGNED_YN=1;
else
   $SIGNED_YN=0;   
$NICK_NAME=trim($NICK_NAME);
if($AUTHOR_NAME==2 && $NICK_NAME!="" && $NICK_NAME!=$_SESSION["LOGIN_USER_NAME"])
{
   $query = "SELECT USER_NAME from USER where USER_NAME = '$NICK_NAME' LIMIT 0 , 1";
   $cursor = exequery(TD::conn(), $query);
   if($ROW=mysql_fetch_array($cursor))
   {
   		$MSG=sprintf(_("不能用“%s”昵称!"),$NICK_NAME);
      Message("",$MSG,"forbidden");
      Button_Back();  
      exit;	
   }
}
$COMMENT_ID=intval($COMMENT_ID);
//------ 回复文章，增加被回复文章的回复计数 ------
if($REPLY=="1")
{
    $query="update BBS_COMMENT set REPLY_CONT=REPLY_CONT+1,SUBMIT_TIME='$CUR_TIME' where COMMENT_ID='$COMMENT_ID'";
    exequery(TD::conn(), $query);
}

$AUTHOR_NAME_TMEP=$AUTHOR_NAME;
if($AUTHOR_NAME_TMEP=="1")
   $FROM_USER = $_SESSION["LOGIN_USER_ID"];
else
   $FROM_USER = "admin"; //匿名短消息显示为admin发送的
if($AUTHOR_NAME=="1")
   $AUTHOR_NAME = $USER_NAME;
else
   $AUTHOR_NAME = $NICK_NAME;

//--------- 用户积分++ ----------
//$query="update USER set BBS_COUNTER=BBS_COUNTER+1 where UID='".$_SESSION["LOGIN_UID"]."'";
$query="update USER_EXT set BBS_COUNTER=BBS_COUNTER+1 where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(), $query);

//--------- 上传附件 ----------
$ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
$ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

   $ATTACHMENT_ID.=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME.=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CONTENT);
$CONTENT = replace_attach_url($CONTENT);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}
//--------- 保存文章 ----------
if($REPLY=="1")
   $PARENT=$COMMENT_ID;
else
   $PARENT=0;

if($SUBJECT=="")
   $SUBJECT=_("无标题");
   
if($NEED_CHECK==0)
	$IS_CHECK=9;
else
	$IS_CHECK=0;

if($BOARD_ID==-1)
	$IS_CHECK=9;
	
//echo $IS_CHECK;exit;	
	
//qpp by 2012-06-15
if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
    $SUBJECT = stripslashes($SUBJECT);
}
$query="insert into BBS_COMMENT(BOARD_ID, USER_ID, AUTHOR_NAME, TYPE, SUBJECT,CONTENT, ATTACHMENT_ID, ATTACHMENT_NAME, SUBMIT_TIME, REPLY_CONT, READ_CONT,PARENT,OLD_SUBMIT_TIME,TOP,JING,READEDER,SIGNED_YN,LOCK_YN,UPDATE_PERSON,UPDATE_TIME,SHOW_YN,IS_CHECK,FROM_USER,NICK_NAME,AUTHOR_NAME_TMEP) values ( '$BOARD_ID', '".$_SESSION["LOGIN_USER_ID"]."', '$AUTHOR_NAME', '$TYPE','$SUBJECT', '$CONTENT', '$ATTACHMENT_ID', '$ATTACHMENT_NAME', '$CUR_TIME', 0, 0,'$PARENT','$CUR_TIME','','','','$SIGNED_YN','','','$CUR_TIME','','$IS_CHECK','$FROM_USER','$NICK_NAME','$AUTHOR_NAME_TMEP')";
exequery(TD::conn(), $query);
$COMMENT_ID_NEW=mysql_insert_id();

$SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];

$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);

$REMIND_URL="bbs/comment.php?BOARD_ID=".$BOARD_ID."&COMMENT_ID=".$COMMENT_ID."&PAGE_START=1";


if($REPLY=="1"||$FAST_REPLY=="1")
{
   $query2="select SUBJECT from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
   $cursor2 = exequery(TD::conn(), $query2);
   if($ROW2=mysql_fetch_array($cursor2))
   {
      $SUBJECT=$ROW2["SUBJECT"];
   }

   if($SMS_SELECT_REMIND=="2" || $SMS2_SELECT_REMIND=="2")//提醒本帖人员
   {
   	  $query2 = "SELECT USER_ID from BBS_COMMENT where COMMENT_ID='$COMMENT_ID' OR PARENT='$COMMENT_ID'";
      $cursor2 = exequery(TD::conn(),$query2);
      $SELF_COMMENT_USER="";
      while($ROW2=mysql_fetch_array($cursor2))
      {
         if(!find_id($SELF_COMMENT_USER,$ROW2["USER_ID"])) 
            $SELF_COMMENT_USER .= $ROW2["USER_ID"].",";       	
      }
      if($SMS_SELECT_REMIND=="2")
   	     $TO_ID_STR1 = $SELF_COMMENT_USER;
   	  if($SMS2_SELECT_REMIND=="2")   
   	     $TO_ID_STR2 = $SELF_COMMENT_USER;
   }
   else //手动选择或全部有权限人员
   {
      $query = "SELECT DEPT_ID,PRIV_ID,USER_ID,BOARD_HOSTER from BBS_BOARD where BOARD_ID='$BOARD_ID'";
      $cursor = exequery(TD::conn(), $query);
      if($ROW=mysql_fetch_array($cursor))
      {
         $TENP_DEPT_ID=$ROW["DEPT_ID"];
         $TENP_PRIV_ID=$ROW["PRIV_ID"];
         $TENP_USER_ID=$ROW["USER_ID"]; 
         $BOARD_HOSTER=$ROW["BOARD_HOSTER"];
         
         if($TENP_DEPT_ID=="ALL_DEPT")
            $query1="select USER_ID from USER where NOT_LOGIN!='1'";
         else
            $query1="select USER_ID from USER where NOT_LOGIN!='1' and (find_in_set(DEPT_ID,'$TENP_DEPT_ID') or find_in_set(USER_PRIV,'$TENP_PRIV_ID') or find_in_set(USER_ID,'$TENP_USER_ID') or find_in_set(USER_ID,'$BOARD_HOSTER'))";
         $cursor1=exequery(TD::conn(),$query1);
         while($ROW1=mysql_fetch_array($cursor1))
            $USER_ID_STR.=$ROW1["USER_ID"].",";                 
      }   	
  	
      $TO_ID_STR=$USER_ID_STR;
      $TO_ID_STR1 = $SMS_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS_SELECT_REMIND_TO_ID, true);
      $TO_ID_STR2 = $SMS2_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS2_SELECT_REMIND_TO_ID, true);
   }
   //$sql="UPDATE BBS_COMMENT SET TO_ID_STR1='$TO_ID_STR1',TO_ID_STR2='$TO_ID_STR2' WHERE COMMENT_ID='$COMMENT_ID_NEW'";
  // exequery(TD::conn(), $sql);
   
	  $REMIND_URL2="1:bbs/check_comment.php?COMMENT_ID=".$COMMENT_ID_NEW."&BOARD_ID=".$BOARD_ID."";
	  
   if($AUTHOR_NAME_TMEP=="1")
   {
      $MSG_CONTENT =sprintf(_("《%s》得到回复，请查看！"),$SUBJECT);
      $MSG_CONTENT2 =sprintf(_("《%s》得到回复，请审核！"),$SUBJECT);
    }
   else
   {
      $MSG_CONTENT = sprintf(_("昵称：%s回复了《%s》，请查看！"),$NICK_NAME,$SUBJECT);
      $MSG_CONTENT2 = sprintf(_("昵称：%s回复了《%s》，请审核！"),$NICK_NAME,$SUBJECT);
    }
      
   if($NEED_CHECK==0){
	   if($TO_ID_STR1!="")
	      send_sms("",$FROM_USER,$TO_ID_STR1,18,$MSG_CONTENT,$REMIND_URL,$BOARD_ID);
	   if($TO_ID_STR2!="")
	      send_mobile_sms_user("",$FROM_USER,$TO_ID_STR2,$MSG_CONTENT,18);
    }
    else{
    	if ($BOARD_HOSTER=="")
    	   $BOARD_HOSTER="admin";
    	send_sms("",$FROM_USER,$BOARD_HOSTER,18,$MSG_CONTENT2,$REMIND_URL2,$BOARD_ID);
    	}
}

else if($SEND_TITLE_FLAG==1 )
{
	$query = "SELECT DEPT_ID,PRIV_ID,USER_ID,BOARD_HOSTER from BBS_BOARD where BOARD_ID='$BOARD_ID'";
   $cursor = exequery(TD::conn(), $query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $TENP_DEPT_ID=$ROW["DEPT_ID"];
      $TENP_PRIV_ID=$ROW["PRIV_ID"];
      $TENP_USER_ID=$ROW["USER_ID"]; 
      $BOARD_HOSTER=$ROW["BOARD_HOSTER"];
      
      if($TENP_DEPT_ID=="ALL_DEPT")
         $query1="select USER_ID from USER where NOT_LOGIN!='1'";
      else
         $query1="select USER_ID from USER where NOT_LOGIN!='1' and (find_in_set(DEPT_ID,'$TENP_DEPT_ID') or find_in_set(USER_PRIV,'$TENP_PRIV_ID') or find_in_set(USER_ID,'$TENP_USER_ID') or find_in_set(USER_ID,'$BOARD_HOSTER'))";
      $cursor1=exequery(TD::conn(),$query1);
      while($ROW1=mysql_fetch_array($cursor1))
         $USER_ID_STR.=$ROW1["USER_ID"].","; 
   }
   
   $TO_ID_STR=$USER_ID_STR;
   $TO_ID_STR1 = $SMS_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS_SELECT_REMIND_TO_ID, true);
   $TO_ID_STR2 = $SMS2_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS2_SELECT_REMIND_TO_ID, true);
   
   //$sql="update BBS_COMMENT set TO_ID_STR1='$TO_ID_STR1',TO_ID_STR2='$TO_ID_STR2' where COMMENT_ID='$COMMENT_ID_NEW'";
   //exequery(TD::conn(), $sql);
   
   $REMIND_URL="bbs/comment.php?BOARD_ID=".$BOARD_ID."&COMMENT_ID=".$COMMENT_ID_NEW."&PAGE_START=1";
    if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SUBJECT = gbk_stripslashes($SUBJECT);
    }
   if($AUTHOR_NAME_TMEP=="1")
   {
      $MSG_CONTENT = sprintf(_("%s发了新贴，主题为《%s》"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT);
      $MSG_CONTENT2 = sprintf(_("%s发了新贴，主题为《%s》，请审核！"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT);
    }
   else
   {
      $MSG_CONTENT = sprintf(_("昵称：%s发了新贴，主题为《%s》"),$NICK_NAME,$SUBJECT);
      $MSG_CONTENT2 = sprintf(_("昵称：%s发了新贴，主题为《%s》，请审核！"),$NICK_NAME,$SUBJECT);
    }
    
	  $REMIND_URL2="1:bbs/check_comment.php?COMMENT_ID=".$COMMENT_ID_NEW."&BOARD_ID=".$BOARD_ID."";
    
   if($NEED_CHECK==0){
	   if($TO_ID_STR1!="")
	      send_sms("",$FROM_USER,$TO_ID_STR1,18,$MSG_CONTENT,$REMIND_URL,$BOARD_ID);
	   if($TO_ID_STR2!="")
	      send_mobile_sms_user("",$FROM_USER,$TO_ID_STR2,$MSG_CONTENT,18);
    }
    else{
    	if ($BOARD_HOSTER=="")
    	   $BOARD_HOSTER="admin";
    	send_sms("",$FROM_USER,$BOARD_HOSTER,18,$MSG_CONTENT2,$REMIND_URL2,$BOARD_ID);
    	}
}
// qpp  by  2012-06-15
$query="update BBS_COMMENT set TO_ID_STR1='$TO_ID_STR1',TO_ID_STR2='$TO_ID_STR2' where COMMENT_ID='$COMMENT_ID_NEW'";
exequery(TD::conn(), $query);

if($OP=="0")
   header("location: edit.php?BOARD_ID=$BOARD_ID&PAGE_START=$PAGE_START&COMMENT_ID=$COMMENT_ID_NEW&IS_MAIN=1");
elseif($REPLY=="1")
   header("location: comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START&IS_MAIN=1");
elseif($BOARD_ID==-1)
{
	 Message("",_("发布成功"));
	 echo '<div align="center"><input type="button" value="'._("关闭").'" class="BigButton" onclick="javascript: window.close();" /></div>'; 
}
else
   header("location: comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID_NEW&IS_MAIN=1");
?>