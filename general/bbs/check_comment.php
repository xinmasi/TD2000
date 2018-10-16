<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
//URLD:\MYOA\webroot\general\bbs\check_comment.php
$FONT_SIZE = get_font_size("FONT_DISCUSSION", 12);

$HTML_PAGE_TITLE = _("讨论区");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

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
$query = "SELECT COMMENT_ID from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
$cursor = exequery(TD::conn(),$query);
if(mysql_num_rows($cursor) <= 0)
{
    Message(_("提示"),_("帖子不存在,可能已经删除"));
    exit;
}

//修改事务提醒状态--yc
update_sms_status('18',$BOARD_ID);

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

   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);

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
    	 &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("查看文章")?><br>
    </td>
  </tr>
</table>
</div>
<div style="width:98%;text-align:center">
  <div class="threadflow" style="width:190px;height:25px;padding-top:3px;">
<?
$WHERE_STR0="";
if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
   $WHERE_STR0 = "and SHOW_YN='0'";

$query = "SELECT COMMENT_ID from BBS_COMMENT where (BOARD_ID='$BOARD_ID' or  BOARD_ID='-1') and PARENT='0' ".$WHERE_STR0." order by  BOARD_ID asc,TOP desc,SUBMIT_TIME desc";
$cursor = exequery(TD::conn(),$query);
$ARRAY_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $ARRAY_COUNT++;
  $COMMENT_ID1 = $ROW["COMMENT_ID"];
  $COMMENT_ID_ARRAY1[$ARRAY_COUNT] = $COMMENT_ID1;
  $COMMENT_ID_ARRAY2[$COMMENT_ID1] = $ARRAY_COUNT;
}

$LAST_COMMENT_ID = $COMMENT_ID_ARRAY2[$COMMENT_ID]-1;
$NEXT_COMMENT_ID = $COMMENT_ID_ARRAY2[$COMMENT_ID]+1;

if($COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]!="")
{
?>
  	<a href="check_comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]?>&PAGE_START=<?=$PAGE_START?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/previouspage.gif" style="vertical-align :text-bottom;"><?=_("上一主题")?> </a> | 
<?
}
else
   echo "<img src=\"".MYOA_STATIC_SERVER."/static/images/previouspage.gif\" style=\"vertical-align :text-bottom;\">"._("上一主题 |");
if($COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]!="")
{
?>

  	<a href="check_comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]?>&PAGE_START=1"> <?=_("下一主题")?><img src="<?=MYOA_STATIC_SERVER?>/static/images/nextpage.gif" style="vertical-align :text-bottom;"></a>
<?
}else 
   echo _("下一主题")."<img src=\"".MYOA_STATIC_SERVER."/static/images/nextpage.gif\" style=\"vertical-align :text-bottom;\">";  
?>
  </div>
</div>
<div id="comment">
  <div id="comment_body">
<?
$COMMENT_ID_PEARENT=$COMMENT_ID;
$WHERE_STR="";
if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
   $WHERE_STR = "and SHOW_YN='0'";
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
	 $PBAR_STR .= _("<a href=\"check_comment.php?PAGE=1&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">首页</a>&nbsp;");
	 $PBAR_STR .= _("<a href=\"check_comment.php?PAGE=$PRE_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">上一页</a>&nbsp;");	
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
	       $PBAR_STR .= "<a href=\"check_comment.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";	       
	    }
	    else
	    {
   	    if($I > $PAGE - 5)	       	      	           
        {
   	       $SHOW_COUNT++;
   	       if($SHOW_COUNT > 10)
   	          break;
   	          
   	       $PBAR_STR .= "<a href=\"check_comment.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";	    	
         } 
      }   	
   }
}

$NEXT_I = $PAGE + 1;
if($PAGE < $P)
{
  $PBAR_STR .= _("<a href=\"check_comment.php?PAGE=$NEXT_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">下一页</a>&nbsp;");  
	$PBAR_STR .= _("<a href=\"check_comment.php?PAGE=$P&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">末页</a>&nbsp;");
}

//-------- 文章信息 ----------
$query = "SELECT * from BBS_COMMENT where (COMMENT_ID='$COMMENT_ID' OR PARENT='$COMMENT_ID') ".$WHERE_STR." ORDER BY OLD_SUBMIT_TIME limit $START,$NUM_P";
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
  $UPDATE_PERSON = $ROW["UPDATE_PERSON"];
  $UPDATE_TIME = $ROW["UPDATE_TIME"];
  $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
  if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
     $OLD_SUBMIT_TIME=$SUBMIT_TIME;
  $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
  $PARENT = $ROW["PARENT"];
  $LOCK_YN = $ROW["LOCK_YN"];
  $TYPE= $ROW["TYPE"];
  $IS_CHECK= $ROW["IS_CHECK"];
  if($TYPE=="")
     $TYPE=_("未分类");
  if($SAVEBOARD_ID=="-1")
     $TYPE=_("公告");
  $AUTHOR_NAME=str_replace("<","&lt",$AUTHOR_NAME);
  $AUTHOR_NAME=str_replace(">","&gt",$AUTHOR_NAME);
//  $AUTHOR_NAME=stripslashes($AUTHOR_NAME);
  $SUBJECT=str_replace("<","&lt",$SUBJECT);
  $SUBJECT=str_replace(">","&gt",$SUBJECT);
//  $SUBJECT=stripslashes($SUBJECT);
  $USER_NAME="";
  //-----------------修改----------------------
  //$query1 = "SELECT * from USER where USER_ID='$USER_ID'";
  $query1 = "SELECT a.USER_NAME as USER_NAME,b.BBS_SIGNATURE as BBS_SIGNATURE from USER a,USER_EXT b where a.USER_ID='$USER_ID' and a.UID=b.UID";
  //-----------------结束----------------------
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
  {
     $USER_NAME=$ROW["USER_NAME"];
     $BBS_SIGNATURE = $ROW["BBS_SIGNATURE"];
  }
//  $CONTENT=stripslashes($CONTENT);
?>
   <div class="comment_box">
<?
if($COUNT==1)
{
?>
     <div class="subject_header1">
     	<table width="100%" height="100%" border="0">
     		<tr>
     			<td style="font-weight: bold;font-size: 13px;">[<?=$TYPE?>]<?=_("标题：")?><?=$SUBJECT?></td>  
     			<td style="" width="110">
<?
   echo  "&nbsp;&nbsp;&nbsp;&nbsp;";
?>
     				<input type="button" value="<?=_("返回")?>" class="SmallButton" onClick="location='check_manage.php?BOARD_ID=<?=$BOARD_ID?>';">
     			</td>  
     		</tr>
     	</table>    	
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
if($ANONYMITY_YN==0 || ($ANONYMITY_YN==1 && ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))))
{  
	 if($AUTHOR_NAME!=$USER_NAME)
	    echo _("作者姓名：").$USER_NAME._("（昵称：").$AUTHOR_NAME._("）");
	 else
	    echo _("作者姓名：").$USER_NAME;
}else{
	if($AUTHOR_NAME!=$USER_NAME)  
	   echo _("作者昵称：").$AUTHOR_NAME;
	else
	   echo _("作者姓名：").$USER_NAME;
}
echo "&nbsp;&nbsp;".$OLD_SUBMIT_TIME;
if($SHOW_YN=="1")
   echo "&nbsp;<font color=red>"._("已屏蔽")."</font>";
if($IS_CHECK=="1")
   echo "&nbsp;&nbsp;<font color='#00AA00'><B>"._("审核通过")."</font>";
if($IS_CHECK=="0")
   echo "&nbsp;&nbsp;<font color=blue><B>"._("未审核")."</font>";
if($IS_CHECK=="2")
   echo "&nbsp;&nbsp;<font color=red><B>"._("审核未通过")."</font>";
if($IS_CHECK=="9")
	 echo "&nbsp;&nbsp;<font color=black><B>"._("不需要审核")."</font>";
if(find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1)
   {
?>   
   &nbsp;&nbsp;<a href="operation.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>"><?=_("批准")?></a>
   &nbsp;&nbsp;<a href="operation.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&OP=0"><?=_("不批准")?></a>
<?
   }
?>
           </span>
          </td>
       </tr>
     </table>
     </div>
     <div class="main_contment" style="overflow:auto;">
     <b><?=$SUBJECT?></b><br><br>
<span id="content_<?=$COUNT+($PAGE-1)*$NUM_P?>"><?=$CONTENT?></span>
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
        }
     }
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
//----------------修改---------------
//$query6 = "SELECT USER_NAME,NICK_NAME from USER where USER_ID='$UPDATE_PERSON'";
$query6 = "SELECT a.USER_NAME as USER_NAME,b.NICK_NAME as NICK_NAME from USER a , USER_EXT b where a.USER_ID='$UPDATE_PERSON' and a.UID=b.UID";
//----------------结束---------------
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
?>
	 </div>
<?
}
?>
</div>
</body>
</html>
