<?
include_once("inc/auth.inc.php");
ob_end_clean();

include_once("inc/header.inc.php");
?>



<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/menu_left.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/hover_tr.js"></script>
<script language="JavaScript">
var $ = function(id) {return document.getElementById(id);};
var CUR_ID="1";
function clickMenu(ID)
{
    var el=$("module_"+CUR_ID);
    var link=$("link_"+CUR_ID);
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
       $("module_"+ID).style.display="";
       $("link_"+ID).className="active";
    }

    CUR_ID=ID;
}
var ctroltime=null,key="";
function CheckSend()
{
	var kword=$("kword");
	var kword_value='<?=_("按个人信息搜索...")?>';
	if(kword.value==kword_value)
	   kword.value="";
  if(kword.value=="" && $('search_icon').src.indexOf("/static/images/quicksearch.gif")==-1)
	{
	   $('search_icon').src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif";
	}
	if(key!=kword.value && kword.value!="")
	{
     key=kword.value;
	   parent.user.location="query.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>&GROUP_ID_STR="+$("GROUP_ID_STR").value+"&KWORD="+kword.value;
	   if($('search_icon').src.indexOf("/static/images/quicksearch.gif")>=0)
	   {
	   	   $('search_icon').src="<?=MYOA_STATIC_SERVER?>/static/images/closesearch.gif";
	   	   $('search_icon').title='<?=_("清除关键字")?>';
	   	   $('search_icon').onclick=function(){kword.value=kword_value;$('search_icon').src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif";$('search_icon').title="";$('search_icon').onclick=null;};
	   }
  }
  ctroltime=setTimeout(CheckSend,100);
}
</script>
<style>
html,
body,
.moduleContainer {
    overflow: hidden;
    overflow-y: auto;
}
</style>

<body class="bodycolor">
<div style="border:1px solid #000000;margin-left:2px;background:#FFFFFF;">
  <input type="text" id="kword" name="kword" value='<?=_("按个人信息搜索...")?>' onfocus="ctroltime=setTimeout(CheckSend,100);" onblur="clearTimeout(ctroltime);if(this.value=='')this.value='<?=_("按个人信息搜索...")?>';" class="SmallInput" style="border:0px; color:#A0A0A0;width:80%;"><img id="search_icon" src="<?=MYOA_STATIC_SERVER?>/static/images/quicksearch.gif" align=absmiddle style="cursor:pointer;">
</div>
<ul>
<?
$GROUP_ID_STR="";
if(find_id($_SESSION["LOGIN_FUNC_STR"],"10"))
{
?>
  <li><a href="javascript:clickMenu('1');" id="link_1" class="active" title="<?=_("点击伸缩列表")?>"><span><?=_("个人通讯簿")?></span></a></li>
  <div id="module_1" class="moduleContainer">
    <table class="TableBlock trHover" width="100%" align="center">
      <tr class="TableData">
        <td align="center" onclick="parent.user.location='user.php?GROUP_ID=0&USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>'" style="cursor:pointer"><?=_("默认")?></td>
      </tr>
<?
 $query = "SELECT * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by GROUP_NAME";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID =$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $GROUP_ID_STR .= $GROUP_ID.",";
?>
      <tr class="TableData">
        <td align="center"  title="<?=$GROUP_NAME?>" onclick="parent.user.location='user.php?GROUP_ID=<?=$GROUP_ID?>&USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>'" style="cursor:pointer"><?=$GROUP_NAME?></td>
      </tr>
<?
 }
?>
    </table>
  </div>
 <!--增加共享通讯簿信息 ljc 2012-12-25-->
  <li><a href="javascript:clickMenu('3');" id="link_1" class="active" title="<?=_("点击伸缩列表")?>"><span><?=_("共享通讯簿")?></span></a></li>
  <div id="module_3" class="moduleContainer">
    <table class="TableBlock trHover" width="100%" align="center">
      <tr class="TableData">
        <td align="center" onclick="parent.user.location='user.php?GROUP_ID=0&SHARE_TYPE=1&USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>'" style="cursor:pointer"><?=_("默认")?></td>
      </tr>
<?
 $query = "select * from ADDRESS_GROUP where SHARE_GROUP_ID!='' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER_ID) order by USER_ID desc,ORDER_NO asc,GROUP_NAME asc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID =$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $GROUP_ID_STR .= $GROUP_ID.",";
?>
      <tr class="TableData">
        <td align="center" title="<?=$GROUP_NAME?>" onclick="parent.user.location='user.php?GROUP_ID=<?=$GROUP_ID?>&USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>&SHARE_TYPE=1'" style="cursor:pointer"><?=$GROUP_NAME?></td>
      </tr>
<?
 }
?>
    </table>
  </div>
<?
}

//if(find_id($_SESSION["LOGIN_FUNC_STR"],"106"))
//{
?>
  <li><a href="javascript:clickMenu('2');" id="link_2" class="" title="<?=_("点击伸缩列表")?>"><span><?=_("公共通讯簿")?></span></a></li>
  <div id="module_2" class="moduleContainer" style="display:none;">
    <table class="TableBlock trHover" width="100%" align="center">
      <tr class="TableData">
    <td align="center" onclick="parent.user.location='user.php?GROUP_ID=0&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>'" style="cursor:pointer"><?=_("默认")?></td>
  </tr>
<?
 //============================ 个人自定义组 =======================================
 $query = "SELECT * from ADDRESS_GROUP where USER_ID='' order by GROUP_NAME";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
   $GROUP_ID =$ROW["GROUP_ID"];
   $GROUP_NAME=$ROW["GROUP_NAME"];
   $PRIV_DEPT=$ROW["PRIV_DEPT"];
	 $PRIV_ROLE=$ROW["PRIV_ROLE"];
	 $PRIV_USER=$ROW["PRIV_USER"];
    
   if($PRIV_DEPT!="ALL_DEPT" && !find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]))
	   continue;
   $GROUP_ID_STR .= $GROUP_ID.",";
?>
      <tr class="TableData">
        <td align="center" title="<?=$GROUP_NAME?>" onclick="parent.user.location='user.php?GROUP_ID=<?=$GROUP_ID?>&FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>'" style="cursor:pointer"><?=$GROUP_NAME?></td>
      </tr>
<?
 }
?>
    </table>
  </div>
<?
//}
?>
  <li><a href="query_user_cond.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>" id="link_3" class="" title="<?=_("点击伸缩列表")?>" target="user"><span><?=_("OA用户查询")?></span></a></li>
</ul>
<input type="hidden" id="GROUP_ID_STR" value="<?=$GROUP_ID_STR?>">
</body>
</html>
