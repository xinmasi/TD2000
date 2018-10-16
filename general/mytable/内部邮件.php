<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="1";
$MODULE_DESC=_("电子邮件");
$MODULE_BODY=$MODULE_OP=$MODULE_TYPE="";
$MODULE_HEAD_CLASS = 'email';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
//$MODULE_OP.='<a href="/general/email/new" title="'._("撰写").'" class="pencil"></a>';    
$MODULE_OP.='<a href="#" title="'._("撰写邮件").'" class="email_edit" onclick="view_more(\'email_new\',\''._("新建电子邮件").'\',\'/general/email/new/\');">'._("撰写").'</a>&nbsp;';
$MODULE_OP.='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'email\',\''._("电子邮件").'\',\'/general/email/\');">'._("全部").'</a>&nbsp;';

$MODULE_TYPE .= '<a href="javascript:get_email(\'\');">'._("全部邮件").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_email(\'0\');">'._("未读邮件").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_email(\'1\');">'._("已读邮件").'</a>';

$MODULE_BODY.= "<ul>";

$query = "SELECT EMAIL_ID,READ_FLAG,FROM_ID,SUBJECT,IMPORTANT,USER_NAME,AVATAR,DEPT_ID,RECV_FROM,RECV_FROM_NAME,IS_WEBMAIL,EMAIL_BODY.BODY_ID from EMAIL,EMAIL_BODY LEFT JOIN USER ON USER.USER_ID = EMAIL_BODY.FROM_ID where EMAIL.BODY_ID=EMAIL_BODY.BODY_ID and BOX_ID=0 and TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and (DELETE_FLAG='' or DELETE_FLAG='0' or DELETE_FLAG='2') order by SEND_TIME desc limit 0,$MAX_COUNT";
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
   
   if($_SESSION["LOGIN_THEME"]==10)
   	$HREF="javascript:;\" onclick=\"send_email2('".$FROM_ID."','".$FROM_NAME."')";
   else
   	$HREF="javascript:parent.parent.leftmenu.send_email('".$FROM_ID."','".$FROM_NAME."')";
   
   if($IS_WEBMAIL!="1")
   {
      $MODULE_BODY.='<li><a title="'._("发送内部邮件，部门：").$DEPT_NAME.'" href="'.$HREF.'" >'.$FROM_NAME.'</a>';
      $MODULE_BODY.=$IMPORTANT_DESC.' <a href="javascript:open_emails('.$EMAIL_ID.','.$BODY_ID.',0)";  title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a>';

   }
   else
   {
      $FROM_WEBMAIL_NAME = td_htmlspecialchars($RECV_FROM_NAME!="" ? $RECV_FROM_NAME : $RECV_FROM);
      $MODULE_BODY.='<li><a title="'._("发件人：").$FROM_WEBMAIL_NAME.'"  href="javascript:;" >'._("【外部邮件】").'</a>';
      $MODULE_BODY.=$IMPORTANT_DESC.' <a href="javascript:open_emails('.$EMAIL_ID.','.$BODY_ID.',1)"; title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a>';

   }
   
   if($READ_FLAG=="0")
      $MODULE_BODY.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" alt="'._("未读").'" align="absmiddle">';
   $MODULE_BODY.='</li>';
}

if($COUNT==0)
   $MODULE_BODY.="<li>"._("暂无内部邮件")."</li>";

$MODULE_BODY.= "<ul>";

$MODULE_BODY.='<script>
function get_email(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("email.php", "MAX_COUNT='.$MAX_COUNT.'&READ_FLAG="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'" +req.status);
   }
}
function open_emails(EMAIL_ID,BODY_ID,IS_WEB)
{
if(IS_WEB==1)
  URL="/general/email/inbox/read_email/read_webemail.php?EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&BODY_ID="+BODY_ID+"&BTN_CLOSE=1";
else
  URL="/general/email/inbox/read_email/read_email.php?EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&BODY_ID="+BODY_ID+"&BTN_CLOSE=1"; 

    myleft=0;
    mytop=0
    mywidth=screen.availWidth-10;
    myheight=screen.availHeight-40;
 window.open(URL,"read_email"+EMAIL_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
</script>';
}
?>
<script >
function send_email2(FROM_ID,FROM_NAME)
{
   var top = (screen.availHeight-600)/2;
   var left= (screen.availWidth-800)/2;  
   window.open("../../email/new/?TO_ID="+FROM_ID+"&TO_NAME="+FROM_NAME,"","height=600,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+top+",left="+left+",resizable=yes");
}
</script>