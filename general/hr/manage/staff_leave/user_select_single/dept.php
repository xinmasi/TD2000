<?
include_once("inc/auth.inc.php");
ob_end_clean();

if($TO_ID=="" || $TO_ID=="undefined")
{
    $TO_ID="TO_ID";
   $TO_NAME="TO_NAME";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";

include_once("inc/header.inc.php");
?>



<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/menu_left.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/hover_tr.js"></script>
<script language="JavaScript">
var CUR_ID="1";
function clickMenu(ID)
{
    var el=document.getElementById("module_"+CUR_ID);
    var link=document.getElementById("link_"+CUR_ID);
    if(ID==CUR_ID)
    {
       if(el.style.display=="none")
       {
           el.style.display='';
           link.className="active";
       }
       else
       {
           el.style.display="none";
           link.className="";
       }
    }
    else
    {
       el.style.display="none";
       link.className="";
       document.getElementById("module_"+ID).style.display="";
       document.getElementById("link_"+ID).className="active";
    }
    CUR_ID=ID;
}
var ctroltime=null,key="";
function CheckSend()
{
	var kword=document.getElementById("kword");
	var search_icon=document.getElementById("search_icon");
	if(kword.value=="<?=_("按用户名或姓名搜索")?>...")
	   kword.value="";
     if(kword.value=="" && search_icon.src.indexOf("/static/images/quicksearch.gif")==-1)
	{
	   search_icon.src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif";
	}
	if(key!=kword.value && kword.value!="")
	{
        key=kword.value;
	   parent.user.location="query.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&FORM_NAME=<?=$FORM_NAME?>&USE_UID=<?=$USE_UID?>&USER_NAME="+kword.value;
	   if(search_icon.src.indexOf("/static/images/quicksearch.gif")>=0)
	   {
	   	   search_icon.src="<?=MYOA_STATIC_SERVER?>/static/images/closesearch.gif";
	   	   search_icon.title="<?=_("清除关键字")?>";
	   	   search_icon.onclick=function(){kword.value='<?=_("按用户名或姓名搜索")?>...';search_icon.src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif";search_icon.title="";search_icon.onclick=null;};
	   }
    }
  ctroltime=setTimeout(CheckSend,100);
}
</script>


<body class="bodycolor">
<div style="border:1px solid #000000;margin-left:2px;background:#FFFFFF;">
  <input type="text" id="kword" name="kword" value="<?=_("按用户名或姓名搜索")?>..." onfocus="ctroltime=setTimeout(CheckSend,100);" onblur="clearTimeout(ctroltime);if(this.value=='')this.value='<?=_("按用户名或姓名搜索")?>...';" class="SmallInput" style="border:0px; color:#A0A0A0;width:145px;"><img id="search_icon" src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif" align=absmiddle style="cursor:pointer;">
</div>
<ul>
<!--============================ 部门 =======================================-->
  <li><a href="javascript:clickMenu('1');" id="link_1" class="active" title="<?=_("点击伸缩列表")?>"><span><?=_("按部门选择")?></span></a></li>
  <div id="module_1" class="moduleContainer treeList">
<?
$PARA_URL="user.php";
$PARA_TARGET="user";
$PARA_ID="TO_ID";
$PARA_VALUE=$TO_ID.".TO_NAME=".$TO_NAME.".FORM_NAME=".$FORM_NAME;
$PRIV_NO_FLAG=0;
$xname="user_select_single";
$showButton=0;
$USER_SELECT_FLAG=1;

include_once("inc/dept_list/index.php");
?>
  </div>
  <li><a href="javascript:clickMenu('2');" id="link_2" title="<?=_("点击伸缩列表")?>"><span><?=_("按角色选择")?></span></a></li>
  <div id="module_2" class="moduleContainer" style="display:none">
    <table class="TableBlock trHover" width="100%" align="center">
<?
 $PRIV_ID_STR = td_trim($PRIV_ID_STR);
 //============================ 显示角色信息 =======================================
 $query = "SELECT * from USER_PRIV";
 if($ROLE_PRIV=="0")
    $query .= " and PRIV_NO>$MY_PRIV_NO";
 else if($ROLE_PRIV=="1")
    $query .= " and PRIV_NO>=$MY_PRIV_NO";
 else if($ROLE_PRIV=="3" && $PRIV_ID_STR != "")
    $query .= " and USER_PRIV in ($PRIV_ID_STR)";
 $query .= " order by PRIV_NO ";
 $cursor= exequery(TD::conn(),$query);
 $PRIV_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRIV_COUNT++;
    $USER_PRIV =$ROW["USER_PRIV"];
    $PRIV_NAME=$ROW["PRIV_NAME"];
?>
<tr class="TableData">
  <td align="center" onclick="javascript:parent.user.location='user.php?MODULE_ID=<?=$MODULE_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&FORM_NAME=<?=$FORM_NAME?>&USER_PRIV=<?=$USER_PRIV?>&POST_PRIV=<?=$POST_PRIV?>&POST_DEPT=<?=$POST_DEPT?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>';" style="cursor:pointer"><?=$PRIV_NAME?></td>
</tr>
<?
 }

if($PRIV_COUNT==0)
   Message("",_("没有定义角色"),"blank");
?>
    </table>
  </div>
</ul>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$EVECTION_ID_STR = $LEAVE_ID_STR = $OUT_ID_STR = "";

$query = "SELECT USER_ID from ATTEND_EVECTION where STATUS='1' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$CUR_TIME') and to_days(EVECTION_DATE2)>=to_days('$CUR_TIME')";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $EVECTION_ID_STR.=$ROW1["USER_ID"].",";
}

$query = "SELECT USER_ID from ATTEND_LEAVE where STATUS='1' and ALLOW='1' and LEAVE_DATE1<='$CUR_TIME' and LEAVE_DATE2>='$CUR_TIME'";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $LEAVE_ID_STR.=$ROW1["USER_ID"].",";
}

$query = "SELECT USER_ID from ATTEND_OUT where STATUS='0' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$CUR_TIME') and OUT_TIME1<='".date("H:i",time())."' and OUT_TIME2>='".date("H:i",time())."'";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $OUT_ID_STR.=$ROW1["USER_ID"].",";
}
?>
<div id="user_evection" style="display:none;"><?=$EVECTION_ID_STR?></div>
<div id="user_leave" style="display:none;"><?=$LEAVE_ID_STR?></div>
<div id="user_out" style="display:none;"><?=$OUT_ID_STR?></div>
</body>
</html>
