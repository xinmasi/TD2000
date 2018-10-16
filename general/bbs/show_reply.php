<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");

$FONT_SIZE = get_font_size("FONT_DISCUSSION", 12);
$REPLAY_COMMENT_ID = $COMMENT_ID;

$HTML_PAGE_TITLE = _("讨论区");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function delete_comment(COMMENT_ID,USER_ID)
{
  msg="<?=_("确定要删除该文章吗?")?>";
  if(window.confirm(msg))
  {
    URL="delete.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&USER_ID="+USER_ID;
    window.location=URL;
  }
}

function CheckForm()
{
  if(getEditorText('CONTENT').length==0 && getEditorHtml('CONTENT')=="")
  {
    alert("<?=_("内容不能为空!")?>");
    return false;
  }  
  if(NAME_SELECT==2 && document.form1.NICK_NAME.value=="")
  {
     alert("<?=_("署名不能为空!")?>");
     return false;
  }
  return true;
}

function SubmitForm()
{
	if(CheckForm())
	  document.form1.submit();
}

function lock_comment(COMMENT_ID,LOCK_FLAG)
{
  msg="<?=_("确定要锁定吗?")?>";
  if(window.confirm(msg))
  {
    URL="lock.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&LOCK_FLAG="+LOCK_FLAG;
    window.location=URL;
  }
}
function show_comment(COMMENT_ID)
{
  msg="<?=_("确定要屏蔽吗?")?>";
  if(window.confirm(msg))
  {
    URL="shielding.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID_ROOT=<?=$REPLAY_COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&SHOW_YN=1";
    window.location=URL;
  }
} 
function recover_comment(COMMENT_ID)
{
  msg="<?=_("确定要恢复此被屏蔽文章吗?")?>";
  if(window.confirm(msg))
  {
    URL="shielding.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID_ROOT=<?=$REPLAY_COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&SHOW_YN=0";
    window.location=URL;
  }
} 

function quote_comment(the_layer)
{
	var ContentHTML = eval("document.getElementById('span_" + the_layer + "')").innerHTML;
	var ContentHTML2 = eval("document.getElementById('content_" + the_layer + "')").innerHTML;
	ContentHTML = "<style>.BBS_QUOTE{border:#666 1px dashed;padding:8px;margin:8px; text-align:left;background-color:#fff; zoom:1;}.BBS_QUOTE{filter:alpha(opacity=70);opacity:0.7;background-color:none;}.BBS_QUOTE img{position:relative;vertical-align:baseline;}</style><DIV align=center><DIV class=BBS_QUOTE><IMG src='<?=MYOA_STATIC_SERVER?>/static/images/bbs_quote.gif'><B><?=_("引用")?> " + ContentHTML + "</B><BR><DIV>"+ContentHTML2+"</DIV></DIV></DIV>";
  setEditorHtml('CONTENT',ContentHTML);	
}
</script>
<style>
.pagebar{
   text-align: center;
   font-size: 12px;
   word-wrap:break-word;
   word-brak:normal;
}	
</style>


<body class="bodycolor" style="text-align: center;">
<?
$query = "SELECT COMMENT_ID from BBS_COMMENT where COMMENT_ID='$COMMENT_ID' AND IS_CHECK!='0' AND IS_CHECK!='2'";
$cursor = exequery(TD::conn(),$query);
if(mysql_num_rows($cursor) <= 0)
{
   Message(_("提示"),_("帖子不存在,可能已经删除"));
   exit;	
}

//------- 个人信息 --------
$query = "SELECT USER.USER_NAME as USER_NAME,USER_EXT.NICK_NAME as NICK_NAME from USER,USER_EXT where USER.UID='".$_SESSION["LOGIN_UID"]."' and USER.UID=USER_EXT.UID";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_NAME1=$ROW["USER_NAME"];
   $NICK_NAME1=$ROW["NICK_NAME"];
}

//------- 讨论区信息 -------
$query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER))";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DEPT_ID = $ROW["DEPT_ID"];
   $PRIV_ID = $ROW["PRIV_ID"];
   $USER_ID1 = $ROW["USER_ID"];
   $BOARD_NAME = $ROW["BOARD_NAME"];
   $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];
   $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
   $LOCK_DAYS_BEFORE = $ROW["LOCK_DAYS_BEFORE"];
   $NEED_CHECK = $ROW["NEED_CHECK"];
   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);
?>

<script>
<?
 if($AUTHOR_NAME==$USER_NAME1 || $ANONYMITY_YN=="0")
    $NAME_SELECT=1;
 else
    $NAME_SELECT=2;
?>

NAME_SELECT=<?=$NAME_SELECT?>;

function set_name(name)
{
  NAME_SELECT=name;
}
</script>
<? 
}
else
{
    //----------讨论区权限控制---------
    exit;
}

//----------讨论区权限控制---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//	 exit;
?>
<div>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"> <?=_("讨论区")?></a>
    	 &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("查看回复")?><br>
    </td>
  </tr>
</table>
</div>

<div id="comment">
  <div id="comment_body">
<?
$COMMENT_ID_PEARENT=$COMMENT_ID;
$WHERE_STR="";
if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
   $WHERE_STR = "and (SHOW_YN='0'||USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";
   
//-------- 阅读数加1 ---------
if($PAGE==""||$PAGE==1)
{
   $query = "update BBS_COMMENT set READ_CONT=READ_CONT+1 where COMMENT_ID='$COMMENT_ID'";
   $cursor = exequery(TD::conn(),$query);
}

$query = "SELECT BOARD_ID from BBS_COMMENT where (COMMENT_ID='$COMMENT_ID' OR PARENT='$COMMENT_ID') ".$WHERE_STR."";
$cursor= exequery(TD::conn(),$query);  
$NUM_ROWS = mysql_num_rows($cursor);

$NUM_P = 10;
if($PAGE=="")
   $PAGE=1;
$START = ($PAGE-1)*$NUM_P;

$P = ceil(($NUM_ROWS)/$NUM_P);
$PRE_I = $PAGE - 1;
if($PAGE != 1)
{
	 $PBAR_STR .= _("<a href=\"show_reply.php?PAGE=1&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">首页</a>&nbsp;");
	 $PBAR_STR .= _("<a href=\"show_reply.php?PAGE=$PRE_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">上一页</a>&nbsp;");	
}	

$SHOW_COUNT = 0; 
for($I = 1;$I <= $P;$I++)
{  
	 if($I==$PAGE)
	 {
	    $SHOW_COUNT++;
	    $PBAR_STR .= "&nbsp;".$I."&nbsp;&nbsp;";	 
	 }
	 else
	 {    
	    if($PAGE > $P - 10 && $I > $P - 10)
	    {
   	     $SHOW_COUNT++;
   	     if($SHOW_COUNT > 10)
   	        break;
	       $PBAR_STR .= "<a href=\"show_reply.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";	       
	    }
	    else
	    {
   	    if($I > $PAGE - 5)	       	      	           
        {
   	       $SHOW_COUNT++;
   	       if($SHOW_COUNT > 10)
   	          break;
   	          
   	       $PBAR_STR .= "<a href=\"show_reply.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";	    	
         } 
      }   	
   }
}

$NEXT_I = $PAGE + 1;
if($PAGE < $P)
{
  $PBAR_STR .= _("<a href=\"show_reply.php?PAGE=$NEXT_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">下一页</a>&nbsp;");  
	$PBAR_STR .= _("<a href=\"show_reply.php?PAGE=$P&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">末页</a>&nbsp;");
}

//-------- 文章信息 ----------
$query = "SELECT * from BBS_COMMENT where PARENT='$COMMENT_ID' AND IS_CHECK!=0 AND IS_CHECK!=2 ".$WHERE_STR." ORDER BY OLD_SUBMIT_TIME limit $START,$NUM_P";
$cursor = exequery(TD::conn(),$query);
$COUNT_ALL=mysql_num_rows($cursor);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $COUNT++;
  $SAVEBOARD_ID = $ROW["BOARD_ID"];
  $COMMENT_ID = $ROW["COMMENT_ID"];
  $USER_ID = $ROW["USER_ID"];  
  $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
  $SUBJECT = $ROW["SUBJECT"];
  $CONTENT = $ROW["CONTENT"];
  $SIGNED_YN = $ROW["SIGNED_YN"];
  $SUBMIT_TIME = $ROW["SUBMIT_TIME"];
  $READEDER = $ROW["READEDER"];
  $SHOW_YN = $ROW["SHOW_YN"];
  $DELETE_YN = $ROW["DELETE_YN"];
  $UPDATE_PERSON = $ROW["UPDATE_PERSON"];
  $UPDATE_TIME = $ROW["UPDATE_TIME"];
  $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
  if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
     $OLD_SUBMIT_TIME=$SUBMIT_TIME;
  $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
  $PARENT = $ROW["PARENT"];
  $LOCK_YN = $ROW["LOCK_YN"];
  $DELETE_YN = $ROW["DELETE_YN"];
  $TYPE= $ROW["TYPE"];
  $AUTHOR_NAME_TMEP = $ROW["AUTHOR_NAME_TMEP"];
  if($SAVEBOARD_ID=="-1")
     $TYPE=_("公告");
  $AUTHOR_NAME=str_replace("<","&lt",$AUTHOR_NAME);
  $AUTHOR_NAME=str_replace(">","&gt",$AUTHOR_NAME);
//  $AUTHOR_NAME=stripslashes($AUTHOR_NAME);
  $SUBJECT=str_replace("<","&lt",$SUBJECT);
  $SUBJECT=str_replace(">","&gt",$SUBJECT);
//  $SUBJECT=stripslashes($SUBJECT);
  $USER_NAME="";
  $query1 = "SELECT * from USER where USER_ID='$USER_ID'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
  {
     $USER_NAME=$ROW["USER_NAME"];
     $BBS_SIGNATURE = $ROW["BBS_SIGNATURE"];
  }

  if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
  {
  	  $READEDER.=$_SESSION["LOGIN_USER_ID"].",";
  	  $query1="update BBS_COMMENT set READEDER='$READEDER' where COMMENT_ID='$COMMENT_ID'";
      exequery(TD::conn(),$query1);
  }
  
  $COMMENT_ID_ROOT = "";
  if($PARENT == 0)
     $COMMENT_ID_ROOT=$COMMENT_ID;
  else
     $COMMENT_ID_ROOT=$REPLAY_COMMENT_ID;

//  $CONTENT=stripslashes($CONTENT);
  
?>
   <div class="comment_box">
<?
if($COUNT==1)
{
?>
     <div class="subject_header1">
     	  	
     	</div>
<?
}
?>
     <div class="comment_title">
     	<table width="99%" height="100%">
     		<tr>
     			<td>
     				<span class="flower_span"><?=$COUNT+($PAGE-1)*$NUM_P?>#</span>
     				<span class="info_span" id="span_<?=$COUNT+($PAGE-1)*$NUM_P?>">
<?
if($ANONYMITY_YN==0 || ($ANONYMITY_YN==1 && $_SESSION["LOGIN_USER_PRIV"]==1))
{  
	 if($AUTHOR_NAME_TMEP==2 || $AUTHOR_NAME!=$USER_NAME)
	    echo _("作者昵称：").$AUTHOR_NAME;
	 else
	    echo _("作者姓名：").$USER_NAME;
}else{
	if($AUTHOR_NAME_TMEP==2 || $AUTHOR_NAME!=$USER_NAME)  
	   echo _("作者昵称：").$AUTHOR_NAME;
	else
	   echo _("作者姓名：").$USER_NAME;
}
echo "&nbsp;&nbsp;".$OLD_SUBMIT_TIME;
if($DELETE_YN=="1")
   echo "&nbsp;<font color=red>"._("已删除")."</font>";
if($SHOW_YN=="1")
   echo "&nbsp;<font color=red>"._("已屏蔽")."</font>";
?>
           </span>
          </td>
       </tr>
     </table>
     </div>
     <div class="main_contment">
     <?
if(!(find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1)&&$DELETE_YN=="1") 
{  
   $SUBJECT="";
   $CONTENT=_("------------帖子已删除-----------");
}
?>    
     <b><?=$SUBJECT?></b><br><br>

<span id="content_<?=$COUNT+($PAGE-1)*$NUM_P?>" style="font-size:<?=$FONT_SIZE?>pt"><?=$CONTENT?></span>
<?
     if($ATTACHMENT_NAME!="")
     {
        $IMAGE_COUNT=0;
        $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
        $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
        $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";

        $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
           if($ATTACHMENT_ID_ARRAY[$I]=="" || stristr($CONTENT, "ATTACHMENT_ID=".attach_id_encode($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I])))
              continue;

           $IMAGE_COUNT++;
           if($IMAGE_COUNT == 1)
              echo "<br><br><br><b>"._("附件：")."</b>";
           echo "<br>";
           echo attach_link($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I],1,1,1);

           $MODULE=attach_sub_dir();
           $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
           $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
           if($YM)
              $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
           $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

           $EXT_NAME=substr(strrchr($ATTACHMENT_NAME_ARRAY[$I],"."),1);
           $EXT_NAME=strtolower($EXT_NAME);

           if(find_id($IMG_TYPE_STR,$EXT_NAME))
           {
              $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
           if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
              $WIDTH=$IMG_ATTR[0];
           if($WIDTH>600)
              $WIDTH=600;
?>
        <br><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>">
<?
           }
        }//for
     }//if
?>
   </div> 
<?
if($SIGNED_YN==1 && trim($BBS_SIGNATURE)!="")
{
?>
   <div style="text-align:left;margin-left:10px;">
   	<img src="<?=MYOA_STATIC_SERVER?>/static/images/sigline.gif"><br>
   	<?=$BBS_SIGNATURE?>
   </div>  
<?
}
if($UPDATE_PERSON!="" && $UPDATE_TIME!="")
{
?>
   <div style="text-align:left;margin-left:10px;">
   	<I><font size="2" color=blue>
<?
$EDIT_NICK_NAME="";
$query6 = "SELECT USER_NAME,NICK_NAME from USER where USER_ID='$UPDATE_PERSON'";
$cursor6= exequery(TD::conn(),$query6);
if($ROW6=mysql_fetch_array($cursor6))
   $EDIT_NICK_NAME=$ROW6["NICK_NAME"];
//if($UPDATE_PERSON!=$USER_ID)
//   echo substr(GetUserNameById($UPDATE_PERSON),0,-1);
//else
   echo $EDIT_NICK_NAME;

?>
&nbsp;<?=sprintf(_("编辑于%s"),"&nbsp;".$UPDATE_TIME)?></font></I>
   </div>  
<?
}
if($ANONYMITY_YN!=2)
{
?>  
   
  </div>   
<?
}//if($ANONYMITY_YN!=2)
}//while

if($NICK_NAME1=="")
   $NICK_NAME1=$USER_NAME1;
?>
</div>
<br /><br />
<?
if($P > 1)
{
?>
<div class="pagebar"><?=$PBAR_STR?></div>
<br />
<br />
<?
}
?>

</div>
</body>
</html>
