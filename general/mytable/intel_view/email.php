<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
$NEW_READ_FLAG=$READ_FLAG;
$EMAIL_MODULE_BODY.= "<ul>";

if($READ_FLAG=="")
   $query = "SELECT EMAIL_ID,READ_FLAG,FROM_ID,SUBJECT,IMPORTANT,USER_NAME,AVATAR,DEPT_ID,RECV_FROM,RECV_FROM_NAME,IS_WEBMAIL,EMAIL_BODY.BODY_ID from EMAIL,EMAIL_BODY LEFT JOIN USER ON USER.USER_ID = EMAIL_BODY.FROM_ID where EMAIL.BODY_ID=EMAIL_BODY.BODY_ID and BOX_ID=0 and TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and (DELETE_FLAG='' or DELETE_FLAG='0' or DELETE_FLAG='2') order by SEND_TIME desc limit 0,$MAX_COUNT";
else
   $query = "SELECT EMAIL_ID,READ_FLAG,FROM_ID,SUBJECT,IMPORTANT,USER_NAME,AVATAR,DEPT_ID,RECV_FROM,RECV_FROM_NAME,IS_WEBMAIL,EMAIL_BODY.BODY_ID from EMAIL,EMAIL_BODY LEFT JOIN USER ON USER.USER_ID = EMAIL_BODY.FROM_ID where EMAIL.BODY_ID=EMAIL_BODY.BODY_ID and BOX_ID=0 and READ_FLAG='$READ_FLAG' and TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and (DELETE_FLAG='' or DELETE_FLAG='0' or DELETE_FLAG='2') order by SEND_TIME desc limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $EMAIL_ID=$ROW["EMAIL_ID"];
   $FROM_ID=$ROW["FROM_ID"];
   $READ_FLAG=$ROW["READ_FLAG"];
   $IMPORTANT=$ROW["IMPORTANT"];
   $SUBJECT=$ROW["SUBJECT"];
   $IS_WEBMAIL = $ROW["IS_WEBMAIL"];

   $FROM_NAME=$ROW["USER_NAME"];
   $AVATAR=$ROW["AVATAR"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $DEPT_NAME=$DEPT_ID!=""?dept_long_name($DEPT_ID):""; 
   $RECV_FROM = $ROW["RECV_FROM"];
	 $RECV_FROM_NAME = $ROW["RECV_FROM_NAME"];
   $BODY_ID = $ROW["BODY_ID"];

   if($IMPORTANT=='0' || $IMPORTANT=="")
      $IMPORTANT_DESC="";
   else if($IMPORTANT=='1')
      $IMPORTANT_DESC="<span class=\"TextColor1\">"._("重要")."</span>";
   else if($IMPORTANT=='2')
      $IMPORTANT_DESC="<span class=\"TextColor2\">"._("非常重要")."</span>";

   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }
   $SUBJECT=td_htmlspecialchars($SUBJECT);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

   if($FROM_NAME=="")
   {
      $FROM_NAME=$FROM_ID;
      $AVATAR="";
      $DEPT_NAME=_("用户已删除");
   }
   //

   if($_SESSION["LOGIN_THEME"]==10)
      $HREF="#\" onclick=\"send_email2('".$FROM_ID."','".$FROM_NAME."')";
   else
      $HREF="javascript:parent.parent.leftmenu.send_email('".$FROM_ID."','".$FROM_NAME."')";
      
   if($IS_WEBMAIL!="1")
   {
      $EMAIL_MODULE_BODY.='<li><a title="'._("发送内部邮件，部门：").$DEPT_NAME.'" href="'.$HREF.'" >'.$FROM_NAME.'</a>';
      $EMAIL_MODULE_BODY.=$IMPORTANT_DESC.' <a href="javascript:open_emails('.$EMAIL_ID.','.$BODY_ID.',0)"; title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a>';
   }   
   else
   {
   	  $FROM_WEBMAIL_NAME = td_htmlspecialchars($RECV_FROM_NAME!="" ? $RECV_FROM_NAME : $RECV_FROM);
   	  $EMAIL_MODULE_BODY.='<li><a title="'._("发件人：").$FROM_WEBMAIL_NAME.'" href="javascript:;">'._("【外部邮件】").'</a>';
      $EMAIL_MODULE_BODY.=$IMPORTANT_DESC.' <a href="javascript:open_emails('.$EMAIL_ID.','.$BODY_ID.',1)"; title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a>'; 	  
   }
   
   if($READ_FLAG=="0")
      $EMAIL_MODULE_BODY.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" alt="'._("未读").'" align="absmiddle">';
   $EMAIL_MODULE_BODY.='</li>';
}

if($COUNT==0)
{
   if($NEW_READ_FLAG=="")
      $EMAIL_MODULE_BODY.="<li>"._("暂无邮件")."</li>";
   if($NEW_READ_FLAG=="0")
      $EMAIL_MODULE_BODY.="<li>"._("暂无未读邮件")."</li>";
   if($NEW_READ_FLAG=="1")
      $EMAIL_MODULE_BODY.="<li>"._("暂无已读邮件")."</li>";
}
$EMAIL_MODULE_BODY.= "</ul>";

if($MODULE_SCROLL=="true" && stristr($EMAIL_MODULE_BODY, "href"))
{
   $EMAIL_MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$EMAIL_MODULE_BODY.'</marquee>';
}
echo $EMAIL_MODULE_BODY;
?>
<script >
function send_email2(FROM_ID,FROM_NAME)
{
   var top = (screen.availHeight-600)/2;
   var left= (screen.availWidth-800)/2;  
   window.open("../../email/new/?TO_ID="+FROM_ID+"&TO_NAME="+FROM_NAME,"","height=600,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+top+",left="+left+",resizable=yes");
}
</script>