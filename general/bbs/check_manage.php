<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$PAGE_SIZE=20;

$HTML_PAGE_TITLE = _("讨论区帖子审核");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script Language="JavaScript">
function set_page()
{  
  PAGE_START=(PAGE_NUM.value-1)*<?=$PAGE_SIZE?>+1;
  location="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&ASC_DESC=<?=$ASC_DESC?>&PAGE_START="+PAGE_START;
}
function check_all()
{
 if(!document.getElementsByName("title_select"))
    return;
 for (i=0;i<document.getElementsByName("title_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("title_select").item(i).checked=true;
   else
      document.getElementsByName("title_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("title_select").checked=true;
   else
      document.getElementsByName("title_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}

function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("title_select").length;i++)
  {

      el=document.getElementsByName("title_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("title_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
  
}

function pass_choose()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要批准帖子，请至少选择其中一项。")?>");
     return;
  }

  msg='<?=_("确认要批准所选帖子吗？")?>';
  if(window.confirm(msg))
  {
    URL="operation.php?DELETE_STR="+ delete_str +"&BOARD_ID=<?=$BOARD_ID?>";
    location=URL;
  }
}

function unpass_choose()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("不批准帖子，请至少选择其中一项。")?>");
     return;
  }

  msg='<?=_("确认不批准所选帖子吗？")?>';
  if(window.confirm(msg))
  {
    URL="operation.php?&OP=0&DELETE_STR="+ delete_str +"&BOARD_ID=<?=$BOARD_ID?>";
    location=URL;
  }
}

function order_by(asc_desc)
{
 window.location="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&ASC_DESC="+asc_desc;
}
</script>


<body class="bodycolor">
<?
//------- 讨论区信息 -------
$query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER))";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DEPT_ID = $ROW["DEPT_ID"];
   $PRIV_ID = $ROW["PRIV_ID"];      
   $USER_ID1 = $ROW["USER_ID"];
   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];      
   $BOARD_NAME = $ROW["BOARD_NAME"];
   $WELCOME_TEXT = $ROW["WELCOME_TEXT"];
   $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
   
   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);

   $WELCOME_TEXT=str_replace("<","&lt",$WELCOME_TEXT);
   $WELCOME_TEXT=str_replace(">","&gt",$WELCOME_TEXT);
   $WELCOME_TEXT=stripslashes($WELCOME_TEXT);
}
else
{
    //----------讨论区权限控制---------
    exit;
}

//----------讨论区权限控制---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//	 exit;

//----------获得版主名称-----------
$BOARD_HOSTER_NAME="";
$TOK=strtok($BOARD_HOSTER,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $BOARD_HOSTER_NAME.=$ROW["USER_NAME"].",";
   $TOK=strtok(",");
}
$BOARD_HOSTER_NAME=substr($BOARD_HOSTER_NAME,0,-1);

//=============================== 查阅文章信息 ===================================
if($TYPE!="") 
{
   if($TYPE==_("无分类"))
   	$WHERE_STR=" and (TYPE='$TYPE' or TYPE='') ";
   else
   	$WHERE_STR=" and (TYPE='$TYPE')  ";
}

if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
   $WHERE_STR = "and SHOW_YN='0'";
$query = "SELECT count(*) from BBS_COMMENT where BOARD_ID='$BOARD_ID' ".$WHERE_STR; 
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
$COMMENT_COUNT=0;

if($ROW=mysql_fetch_array($cursor))
   $COMMENT_COUNT=$ROW[0];

if($COMMENT_COUNT==0)
{
?>
<div>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"><?=_("讨论区")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a><br>
    </td>
  </tr>
</table>
</div>

<div id="bbs_body">
<div class="operate_bar">
<table border="0" cellspacing="1" width="95%" class="small" cellpadding="3" style="font-size:13px;">
  <tr>
   <td nowrap >&nbsp;</td>
   <td align="right">
     <? if($ANONYMITY_YN!="2") { 
	 if(find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) ||find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])|| $CUR_TIME>="12:00:00" && $CUR_TIME<="13:59:59")
	 {
	 ?>	  
     <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("发表新文章")?>"><?=_("发帖")?></a>
     <? 
	 } 
	 else
	 {
	 ?> 
     <a href="#" class="buttom_a"  title="<?=_("中午12点到下午2点允许发帖")?>"><?=_("发帖")?></a>
	 <? 
	 }
	 }?>
     <a href="user_top.php" class="buttom_a" title="<?=_("积分排行榜")?>"><?=_("积分榜")?></a>
     <a href="index.php" class="buttom_a" title="<?=_("返回讨论区目录")?>"><?=_("其它讨论区")?></a>
     
   </td>
  </tr>
</table>
</div>
<?
  Message("",_("该讨论区尚无文章"));
  exit;
}

//============ 存在文章，开始计算分页 =================

//--- 计算总页数 ---
$PAGE_TOTAL=$COMMENT_COUNT/$PAGE_SIZE;
$PAGE_TOTAL=ceil($PAGE_TOTAL);

//--- 计算,末页 ---
if($COMMENT_COUNT<=$PAGE_SIZE)
   $LAST_PAGE_START=1;
else if($COMMENT_COUNT%$PAGE_SIZE==0)
   $LAST_PAGE_START=$COMMENT_COUNT-$PAGE_SIZE+1;
else
   $LAST_PAGE_START=$COMMENT_COUNT-$COMMENT_COUNT%$PAGE_SIZE+1;

//--- 智能分页 ---
//-- 页首 --
if($PAGE_START=="")
   $PAGE_START=1;

if($PAGE_START>$COMMENT_COUNT)
   $PAGE_START=$LAST_PAGE_START;

if($PAGE_START<1)
   $PAGE_START=1;

//-- 页尾 --
$PAGE_END=$PAGE_START+$PAGE_SIZE-1;

if($PAGE_END>$COMMENT_COUNT)
   $PAGE_END=$COMMENT_COUNT;

//--- 计算当前页 ---
$PAGE_NUM=($PAGE_START-1)/$PAGE_SIZE+1;
?>
<div>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="font-size:14px;">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"><?=_("讨论区")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a><br>
    </td>
  </tr>
</table>
</div>
<?
$CUR_TIME=date("H:i:s",time());
?>
<div id="bbs_body">
<div class="operate_bar">
<table border="0" width="100%" style="font-size:13px;">
  <tr>
	  <td nowrap align="left" width="70">
	  	<span class="page_span">
	  		<A class="p_total"><?=$COMMENT_COUNT?></A>
	  		<A class="p_total"><?=$PAGE_NUM?>/<?=$PAGE_TOTAL?></A>
	    <span>
	  </td>
	  <td width="398" align="left" nowrap>
      <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("首页")?></a>
      <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&ASC_DESC=<?=$ASC_DESC?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("上一页")?></a>
      <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&ASC_DESC=<?=$ASC_DESC?>&PAGE_START=<?=($PAGE_END+1)?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?> ><?=_("下一页")?></a>
      <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&ASC_DESC=<?=$ASC_DESC?>&PAGE_START=<?=$LAST_PAGE_START?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?>><?=_("末页")?></a>
      <span style="float: left;padding-top:3px;margin-top:3px;font-size:12px;"><?=_("页数")?></span>
      <input type="text" id="PAGE_NUM" value="<?=$PAGE_NUM?>" class="SmallInput" size="2" style="float: left;margin-top: 3px;"> 
      <a href="javascript:set_page();" title="<?=_("转到指定的页面")?>" class="buttom_a"><?=_("转到")?></a>
       </td>	
    <td align="right" width="200" nowrap>
 

        <a href="board.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("返回本讨论区目录")?>"><?=_("返回本讨论区")?></a>
        <a href="index.php" class="buttom_a" title="<?=_("返回讨论区目录")?>"><?=_("返回讨论区列表")?></a>
    </td>
  </tr>
</table>
</div>

<div class="title_body"> 
<table class="fast_replay_table1" width="100%" style="font-size:14px;">
  <tr class="title_bar">
<?
if($ASC_DESC=="")
{ 
	 $ORDER_STR=" order by BOARD_ID asc,TOP desc,SUBMIT_TIME desc";
   $ASC_DESC="0";
}
if($ASC_DESC=="1")
{
	$ORDER_STR=" order by IS_CHECK desc,TOP desc,SUBMIT_TIME desc";
	$ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}
else if($ASC_DESC=="2")
{
	 $ORDER_STR=" order by IS_CHECK asc,TOP desc,SUBMIT_TIME desc";
	 $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}
else
{
$ORDER_IMG="";
$ORDER_STR=" order by BOARD_ID asc,TOP desc,SUBMIT_TIME desc";
}
//oa管理员，版主
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?>   	
      <td nowrap align="center"><?=_("选择")?></td>  	
<?
}
?>      
      <td nowrap align="center" ><?=_("标题")?></td>
      <td nowrap align="center"><?=_("帖子类型")?></td>
      <td nowrap align="center"><?=_("作者")?></td>
      <td nowrap align="center"><?=_("最后回复")?></td>
      <td nowrap align="center" onClick="order_by(<?if($ASC_DESC==2) echo 0;else echo 1+$ASC_DESC;?>);" style="cursor:hand;"><?=_("状态")?><? echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("操作")?></td>

  </tr>

<?
$query = "SELECT * from BBS_COMMENT where BOARD_ID='$BOARD_ID' ".$WHERE_STR.$ORDER_STR;
$cursor = exequery(TD::conn(), $query,$QUERY_MASTER);
$COMMENT_COUNT = 0;
while($ROW=mysql_fetch_array($cursor))
{
  $COMMENT_COUNT++; 
  if($COMMENT_COUNT < $PAGE_START)
     continue;
  else if($COMMENT_COUNT > $PAGE_END)
     break;
  $TYPE=$ROW["TYPE"];
  $COMMENT_ID = $ROW["COMMENT_ID"];
  $SHOW_YN = $ROW["SHOW_YN"];  
  $SAVEBOARD_ID=$ROW["BOARD_ID"];
  $USER_ID = $ROW["USER_ID"];
  $TOP = $ROW["TOP"];
  $JING = $ROW["JING"];
  $READEDER = $ROW["READEDER"];
  $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
  $SUBJECT = $ROW["SUBJECT"];
  $SUBMIT_TIME = $ROW["SUBMIT_TIME"];  
  $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
  if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
     $OLD_SUBMIT_TIME=$SUBMIT_TIME;
  $READ_CONT = $ROW["READ_CONT"];
  $REPLY_CONT = $ROW["REPLY_CONT"];
  $PARENT = $ROW["PARENT"];
  $CONTENT=$ROW["CONTENT"];
  $IS_CHECK=$ROW["IS_CHECK"];
  $CONTENT_SIZE=strlen($CONTENT);
  $CONTENT_SIZE=number_format($CONTENT_SIZE,0, ".",",");

  $AUTHOR_NAME=str_replace("<","&lt",$AUTHOR_NAME);
  $AUTHOR_NAME=str_replace(">","&gt",$AUTHOR_NAME);
//  $AUTHOR_NAME=stripslashes($AUTHOR_NAME);

  $SUBJECT=str_replace("<","&lt",$SUBJECT);
  $SUBJECT=str_replace(">","&gt",$SUBJECT);
//  $SUBJECT=stripslashes($SUBJECT);
  if($PARENT == '0')
    $PARENT_STR = _("主帖");
  else
    $PARENT_STR = _("跟帖");
  $query1 = "SELECT AVATAR,USER_NAME from USER where USER_ID='$USER_ID'";
  $cursor1= exequery(TD::conn(),$query1);

  if($ROW1=mysql_fetch_array($cursor1))
  {
     $AVATAR=$ROW1["AVATAR"];
     $USER_NAME=$ROW1["USER_NAME"];
  }
  else
  {
     $USER_NAME=$USER_ID;
     $AVATAR="";
  }

  $query1 = "SELECT * from BBS_COMMENT where PARENT='$COMMENT_ID' order by SUBMIT_TIME desc limit 1";
  $cursor1= exequery(TD::conn(),$query1);
  $AUTHOR_NAME1="";
  if($ROW1=mysql_fetch_array($cursor1))
  {
  	 $SUBMIT_TIME = $ROW1["SUBMIT_TIME"];
     $AUTHOR_NAME1=$ROW1["AUTHOR_NAME"];
  }
?>
  <tr class="table_row" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
<?
//oa管理员，版主
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?> 
    <td width="5" nowrap class="fast_replay_td1">   	
    	<input type="checkbox" name="title_select" value="<?=$COMMENT_ID?>" onClick="check_one(self);">
    </td> 
<?
}

if($TOP=="1")
   $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
?>
      <td width="40%" class="fast_replay_td1">
      <?
	   if($SAVEBOARD_ID=="-1")
	   {
	  ?>
        [<a style="color:red"><?=_("公告")?></a>]
      <?
	  }
	  else
	  {
	  	if($TYPE!="" && $TYPE!=_("无分类"))
	  	{
	  ?>
        [<a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>&TYPE=<?=$TYPE?>"><?=$TYPE?></a>]
      <?
    	}
	  }
	  ?>
      	<a href="check_comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>"><?=$SUBJECT?><? if($SHOW_YN=="1") echo "&nbsp;<font color=red>"._("已屏蔽")."</font>";?></a>
      <?

if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
   echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>    ";

if($JING==1)
   echo "<img src='".MYOA_STATIC_SERVER."/static/images/jing.gif' height=11 width=15>";
?>

      </td>
      <td  nowrap class="fast_replay_td1" align="center">
      <?=$PARENT_STR?>
      </td>	
      <td width="90" nowrap class="fast_replay_td1" align="center">
<?
if($USER_NAME==$AUTHOR_NAME)
{
?>
    <img src="<?=avatar_path($AVATAR)?>" width="16" height="16" align="absmiddle">
<?
}

if($ANONYMITY_YN==0  && $USER_NAME!=$AUTHOR_NAME)
{  
	 $NEWER=$USER_NAME;
?>
    <?=$USER_NAME?></td>
<?
}
else
{
	 $NEWER=$AUTHOR_NAME; 
?>
    <?=$AUTHOR_NAME?></td>
<?
}
    
if($IS_CHECK==0)
   $CHECK_STATUS="<font color='BLUE'><b>"._("未审核")."</b></font>";
else if($IS_CHECK==1)
   $CHECK_STATUS="<font color='#00AA00'><b>"._("审核通过")."</b></font>";
else if($IS_CHECK==2)
   $CHECK_STATUS="<font color='red'><b>"._("审核未通过")."</b></font>";
else if($IS_CHECK==9)
	 $CHECK_STATUS="<font color='black'><b>"._("不需要审核")."</b></font>";
?>
      <td align="center" class="fast_replay_td1"><?=$SUBMIT_TIME?></td>
      <td align="center" class="fast_replay_td1"><?=$CHECK_STATUS?></td>
      <td align="center" class="fast_replay_td1">
      <a href="operation.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>"><?=_("批准")?></a>&nbsp;&nbsp;
      <a href="operation.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&OP=0"><?=_("不批准")?></a></td>
    
  </tr>
<?
}//while

if($COMMENT_COUNT>0)
{
?>
</table>
</div>

<div class="operate_footer operate_bar">
<table border="0" width="100%">
  <tr>
    <td>
<?
//oa管理员，版主
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?>
    	<input type=checkbox  id="allbox_for" onClick="check_all();" name="allbox" style="float: left;margin-top: 3px;"><label for="allbox_for" class="page_span" style="float: left;padding-top:3px;margin-top: 3px;"><?=_("全选")?></label>
      <a href="javascript:pass_choose();" class="buttom_a"><?=_("批量批准")?></a>
      <a href="javascript:unpass_choose();" class="buttom_a"><?=_("批量不批准")?></a>
      
<?
}
?>
  </td>
  <td align="right" width="285">
 
  </td>
  </tr>
</table>	
</div>
</div>
<?
}
?>
</div>
</body>
</html>