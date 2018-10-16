<?
//URL:webroot\general\chatroom\index.php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("聊天室");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
	window.setTimeout('this.location.reload();',60000);
</script>

<body class="bodycolor">
<?
$IMG_SRC=avatar_path($_SESSION["LOGIN_AVATAR"]);
?>
<script language="JavaScript">
<!--
if (document.all){
	document.write('<div id="C" style="position:absolute;top:0px;left:0px">');
	document.write('<div style="position:relative">');
	document.write('<img  id="pic" src="<?=$IMG_SRC?>" style="position:absolute;top:0px;left:0px">');
	document.write('</div></div>');
}
S=null,fadeStep=4,fade=80,currentStep=0,step=2,RY=0,RX=0,Yarea=0,Xarea=0;
function Expand()
{
	if (document.all)
	{
		pic.width=currentStep*2;
		pic.height=currentStep*2;
		pic.style.top= -currentStep;
		pic.style.left= -currentStep;
		pic.style.filter='alpha(opacity='+fade+')';
		currentStep+=step;
		if (currentStep > 20) fade-=fadeStep;
		if (fade < -50)
		{
			currentStep=0;
			fade=80;
			yarea=window.document.body.clientHeight-80;
			xarea=window.document.body.clientWidth-80;
			RY=Math.round(50+Math.random()*Yarea);
         RX=Math.round(50+Math.random()*Xarea);
			C.style.top=RY+document.body.scrollTop;
			C.style.left=RX+document.body.scrollLeft;
		}
	S=setTimeout('Expand()',40);
	}
}
if (document.all)window.onload=Expand;
if (document.layers)
{
	alert("Your Browser is not capable of displaying this effect.\nPick another.");
	opener.gO();window.close();
}
// -->
</script>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/chatroom.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("聊天室")?></span><br>
    </td>
    </tr>
</table>
<br>
<?
//============================ 昵称 =======================================
//$query = "SELECT NICK_NAME from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$query = "SELECT NICK_NAME from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
$USER_IP=get_client_ip();
if($ROW=mysql_fetch_array($cursor))
	$NICK_NAME=$ROW["NICK_NAME"];

//============================ 显示已创建聊天室 =======================================
$query = "SELECT * from CHATROOM where stop='0'";
$cursor= exequery(TD::conn(),$query);
$CHAT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	$CHAT_COUNT++;
	$CHAT_ID=$ROW["CHAT_ID"];
	$SUBJECT=$ROW["SUBJECT"];
	$SUBJECT=str_replace("<","&lt",$SUBJECT);
	$SUBJECT=str_replace(">","&gt",$SUBJECT);
	$SUBJECT=stripslashes($SUBJECT);
	$STOP=$ROW["STOP"];
   if($CHAT_COUNT==1)
   {
?>
<table class="TableList" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("聊天室列表")?></td>
    </tr>

<?
    }

//-------------- 统计在线人数 ----------------
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_HOUR=date("H",time());
$CUR_MIN=date("i",time());
$USR_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".usr";
$USER_FOUND=0;
if(file_exists($USR_FILE))
{

   $LINES=file($USR_FILE);
   $LINES_COUNT=count($LINES);

   for($I=0;$I<$LINES_COUNT;$I++)
   {
       if($I%4==0)
       {
            $POS=strpos($LINES[$I+2],chr(10));
            $REFRESH_TIME=substr($LINES[$I+2],0,$POS);

            $STR=strtok($REFRESH_TIME," ");
            $DATE=$STR;

            if(compare_date($CUR_DATE,$DATE)==0)
            {
               $STR=strstr($REFRESH_TIME," ");
               $STR=strtok($STR,":");
               $HOUR=$STR;
               $STR=strtok(":");
               $MIN=$STR;

               if((($CUR_HOUR*60+$CUR_MIN)-($HOUR*60+$MIN))<2)
                  $USER_FOUND++;
            }
       }
   }
}
if($NICK_NAME=="")
	$URL="login.php?CHAT_ID=$CHAT_ID&SUBJECT=$SUBJECT";
else
	$URL="chat/index.php?CHAT_ID=$CHAT_ID&SUBJECT=$SUBJECT&USER_NAME=$NICK_NAME&USER_IP=$USER_IP";

$MSG = sprintf(_("%d人"), $USER_FOUND);
?>
    <tr class="TableData">
      <td align="center"><a href="<?=$URL?>"> <?=$SUBJECT?></a> (<?=$MSG?>)</td>
    </tr>
<?
 }

 if($CHAT_COUNT>0)
 {
?>
  </table>
<?
 }
 else
    Message("","<br>"._("暂无开放的聊天室"));
?>

</body>
</html>