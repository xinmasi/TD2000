<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("搜索文章");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">

<script>
function delete_comment(COMMENT_ID)
{
  msg="<?=_("确定要删除该文章吗?")?>";
  if(window.confirm(msg))
  {
    URL="delete.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=" + COMMENT_ID;
    window.location=URL;
  }
}
</script>


<body class="bodycolor">

<?
$BOARD_ID =intval($BOARD_ID);

 //------- 讨论区信息 -------
 $query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID'";
 $cursor = exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
   $BOARD_NAME = $ROW["BOARD_NAME"];
   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];

   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);
 }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <a href="index.php"><?=_("讨论区")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("文章搜索结果")?><br>
    </td>
  </tr>
</table>

<br>

<?
//-------查询文章-------
$COMMENT_ID_STR="";
$WHERE_STR="";
if($SUBJECT!="")
   $WHERE_STR.= " and SUBJECT like '%$SUBJECT%'";
if($CONTENT!="")
   $WHERE_STR.= " and CONTENT like '%$CONTENT%'";
if($ATTACHMENT_NAME!="")
   $WHERE_STR.= " and ATTACHMENT_NAME like '%$ATTACHMENT_NAME%'"; 
if($BEGIN_DATE!="")
{
   $BEGIN_DATE = $BEGIN_DATE." 00:00:01";
   $WHERE_STR.= " and OLD_SUBMIT_TIME >= '$BEGIN_DATE'";
}
if($END_DATE!="")
{
	 $END_DATE = $END_DATE." 23:59:59";
   $WHERE_STR.= " and OLD_SUBMIT_TIME <= '$END_DATE'";
}
if($TYPE!="")
   $WHERE_STR.= " and TYPE = '$TYPE'";
if($END_DATE!="" || $BEGIN_DATE!="")
   $WHERE_STR.= " and PARENT = '0'";
   
$WHERE_STR.=" and IS_CHECK!=0 and IS_CHECK!=2 ";   

$query = "SELECT PARENT,COMMENT_ID from BBS_COMMENT where BOARD_ID='$BOARD_ID'".$WHERE_STR." order by TOP desc,SUBMIT_TIME desc";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
	 $PARENT = $ROW["PARENT"];
	 $COMMENT_ID = $ROW["COMMENT_ID"];	 
	 if(!find_id($COMMENT_ID_STR,$PARENT) && $PARENT!="0")
	    $COMMENT_ID_STR.=$PARENT.",";
	 if($PARENT=="0")
      $COMMENT_ID_STR.=$COMMENT_ID.",";
}

//echo $COMMENT_ID_STR;

$query = "SELECT * from BBS_COMMENT where find_in_set(COMMENT_ID,'$COMMENT_ID_STR')  order by TOP desc,SUBMIT_TIME desc";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
$COMMENT_COUNT = 0;
while($ROW=mysql_fetch_array($cursor))
{
  $COMMENT_COUNT++;
  $TYPE=$ROW["TYPE"];
  if($TYPE=="") $TYPE=_("未分类");
  $COMMENT_ID = $ROW["COMMENT_ID"];
  $USER_ID = $ROW["USER_ID"];
  $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
  $SUBJECT = $ROW["SUBJECT"];
  $SUBMIT_TIME = $ROW["SUBMIT_TIME"];
  $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
  if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
     $OLD_SUBMIT_TIME=$SUBMIT_TIME;
  $READ_CONT = $ROW["READ_CONT"];
  $REPLY_CONT = $ROW["REPLY_CONT"];
  $TOP = $ROW["TOP"];
  $JING = $ROW["JING"];
  $READEDER = $ROW["READEDER"];

  $CONTENT=$ROW["CONTENT"];
  $CONTENT_SIZE=strlen($CONTENT);
  $CONTENT_SIZE=number_format($CONTENT_SIZE,0, ".",",");

  $SUBJECT=str_replace("<","&lt",$SUBJECT);
  $SUBJECT=str_replace(">","&gt",$SUBJECT);
//  $SUBJECT=stripslashes($SUBJECT);

  if($TOP=="1")
     $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";

  $query1 = "SELECT AVATAR,USER_NAME from USER where USER_ID='$USER_ID'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
  {
     $AVATAR=$ROW["AVATAR"];
     $USER_NAME=$ROW["USER_NAME"];
  }
  else
  {
     $USER_NAME=$USER_ID;
     $AVATAR="";
  }

  $class = $COMMENT_COUNT%2 == 1 ? "TableLine1" : "TableLine2";

  if($COMMENT_COUNT==1)
  {
?>
<table class="TableList" align="center" style="width:80%">
<tr class="TableHeader">
    <td align="center" width="9%"><?=_("作者")?></td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
    <td align="center" width="6%"><?=_("实名")?></td>
<?
}
?>
    <td align="center" width="10%"><?=_("分类")?></td>
    <td align="center" width="35%"><?=_("标题")?></td>
    <td align="center" width="10%"><?=_("字节")?></td>
    <td align="center" width="10%"><?=_("回/阅")?></td>
    <td align="center" width="10%"><?=_("发表时间")?></td>
    <td align="center" width="20%"><?=_("操作")?></td>
</tr>

<?
  }
?>
  <tr class="table_row <?=$class?>" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" style="	font-weight: normal;font-size: 12px;">
    <td><?if($USER_NAME==$AUTHOR_NAME){?><img width="16" height="16" src="<?=avatar_path($AVATAR)?>" align="absmiddle"><?}?> <?=$AUTHOR_NAME?></td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
   <td align="center" nowrap><?=$USER_NAME?></td>
<?
}
?>
      <td align="center" width="10%"><?=$TYPE?></td>
      <td><a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>"><?=$SUBJECT?></a>
<?
if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
   echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>    ";

if($JING==1)
   echo "<img src='".MYOA_STATIC_SERVER."/static/images/jing.gif' height=11 width=15>";
?>
     </td>
      <td align="center"><?=$CONTENT_SIZE?></td>
      <td align="center"><?=$REPLY_CONT?>/<?=$READ_CONT?></td>
      <td align="center"><?=$OLD_SUBMIT_TIME?></td>
      <td align="center">
<?
      if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
      {
?>
         <a href="javascript:;" onClick="window.open('move.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>','','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=350,top=150,resizable=yes');"><?=_("转移")?></a>
         <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>"><?=_("编辑")?></a>
         <a href="javascript:delete_comment(<?=$COMMENT_ID?>);"><?=_("删除")?></a><br>
<?
            if($TOP==0 && ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            {
?>
              <a href="javascript:;" onClick="window.open('set_top.php?COMMENT_ID=<?=$COMMENT_ID?>&TOP=1&BOARD_ID=<?=$BOARD_ID?>&DELETE_STR=<?=$COMMENT_ID?>','top','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes');"><?=_("置顶")?></a>
              <!--<a href="set_top.php?COMMENT_ID=<?=$COMMENT_ID?>&TOP=1&BOARD_ID=<?=$BOARD_ID?>"><?=_("置顶")?></a>-->
<?
            }

            if($TOP==1 && ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            {
?>
              <a href="javascript:;" onClick="window.open('set_top.php?COMMENT_ID=<?=$COMMENT_ID?>&TOP=0&BOARD_ID=<?=$BOARD_ID?>&DELETE_STR=<?=$COMMENT_ID?>','top','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes');"><?=_("取消置顶")?></a>
              <!--<a href="set_top.php?COMMENT_ID=<?=$COMMENT_ID?>&TOP=0&BOARD_ID=<?=$BOARD_ID?>"><?=_("取消置顶")?></a>-->
<?
            }

            if($JING==0 && ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            {
?>
              <a href="javascript:;" onClick="window.open('set_jing.php?COMMENT_ID=<?=$COMMENT_ID?>&JING=1&BOARD_ID=<?=$BOARD_ID?>&DELETE_STR=<?=$COMMENT_ID?>','jing','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes');"><?=_("设置精华")?></a>
              <!--<a href="set_jing.php?COMMENT_ID=<?=$COMMENT_ID?>&JING=1&BOARD_ID=<?=$BOARD_ID?>"><?=_("设置精华")?></a>&nbsp;-->
<?
            }

            if($JING==1 && ($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            {
?>
              <a href="javascript:;" onClick="window.open('set_jing.php?COMMENT_ID=<?=$COMMENT_ID?>&JING=0&BOARD_ID=<?=$BOARD_ID?>&DELETE_STR=<?=$COMMENT_ID?>','jing','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes');"><?=_("取消精华")?></a>&nbsp;
              <!--<a href="set_jing.php?COMMENT_ID=<?=$COMMENT_ID?>&JING=0&BOARD_ID=<?=$BOARD_ID?>"><?=_("取消精华")?></a>&nbsp;-->
<?
            }
      }
?>
      </td>
  </tr>
<?
 }

 if($COMMENT_COUNT>0)
 {
?>
  </table>
<?
 }
 else
    Message("","<br>"._("未找到符合条件的文章"));

?>

<br>
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.go(-2);">
</div>

</body>
</html>