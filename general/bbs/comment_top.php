<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("十大热门文章");
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
$BOARD_ID=intval($BOARD_ID);
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
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <a href="index.php"><?=_("讨论区")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("十大热门文章")?> <br>
    </td>
  </tr>
</table>

<br>

<?
  //-------查询文章-------
  $query = "SELECT * from BBS_COMMENT where BOARD_ID='$BOARD_ID' and IS_CHECK!=0 and IS_CHECK!=2 order by READ_CONT desc";
  $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);

  $COMMENT_COUNT = 0;
  while($ROW=mysql_fetch_array($cursor))
  {
    $COMMENT_COUNT++;

    if($COMMENT_COUNT>10)
       break;

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
    $PARENT = $ROW["PARENT"];
    $TYPE=$ROW["TYPE"];
    if($TYPE=="") $TYPE=_("未分类");
    $CONTENT=$ROW["CONTENT"];
    $CONTENT_SIZE=strlen($CONTENT);
    $CONTENT_SIZE=number_format($CONTENT_SIZE,0, ".",",");

    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
//    $SUBJECT=stripslashes($SUBJECT);

    $query1 = "SELECT * from USER where USER_ID='$USER_ID'";
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

    if($COMMENT_COUNT==1)
    {
?>
<table class="TableList" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("排行")?></td>
      <td align="center"nowrap ><?=_("分类")?></td>
      <td nowrap align="center"><?=_("作者")?></td>

<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      <td nowrap align="center"><?=_("实名")?></td>
<?
}
?>
      <td nowrap align="center"><?=_("标题")?></td>
      <td nowrap align="center"><?=_("字节")?></td>
      <td nowrap align="center"><?=_("回/阅")?></td>
      <td nowrap align="center"><?=_("发表时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>

<?
    }
?>
    <tr class="table_row" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" style="	font-weight: normal;font-size: 12px;">
      <td align="center" nowrap width="5%"><?=$COMMENT_COUNT?></td>
      <td align="center" nowrap width="5%"><?=$TYPE?></td>
      <td nowrap width="8%"><?if($USER_NAME==$AUTHOR_NAME){?><img src="<?=avatar_path($AVATAR)?>" width="16" height="16" align="absmiddle"><?}?> <?=$AUTHOR_NAME?></td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
     <td align="center" nowrap  width="7%"><?=$USER_NAME?></td>
     
<?
}
?>
      <td><a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>"><?=$SUBJECT?></a></td>
      <td align="center" nowrap  width="5%"><?=$CONTENT_SIZE?></td>
      <td align="center" nowrap  width="5%"><?=$REPLY_CONT?>/<?=$READ_CONT?></td>
      <td align="center" nowrap width="15%"><?=$OLD_SUBMIT_TIME?></td>
      <td align="center" nowrap width="15%">
<?
      if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
      {  if($PARENT==0)
      	 {
?>
         <a href="javascript:;" onClick="window.open('move.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>','','height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=350,top=150,resizable=yes');"><?=_("转移")?></a>&nbsp;
<?
         }
?>
         <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>"><?=_("编辑")?></a>&nbsp;
         <a href="javascript:delete_comment(<?=$COMMENT_ID?>);"><?=_("删除")?></a>
<?
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
    Message("",_("该讨论区尚无文章"));

 Button_Back();
?>

</body>
</html>