<?
//URLD:\MYOA\webroot\general\bbs\user_top.php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("讨论区积分榜");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><?=_("讨论区积分榜")?><br>
    </td>
  </tr>
</table>

<br>

<?
 //------- 用户信息 -------
 //------------------------修改-----------------------
 //$query = "SELECT USER_NAME,NICK_NAME,AVATAR,BBS_COUNTER from USER where BBS_COUNTER>0 order by BBS_COUNTER desc";
 $query = "SELECT a.USER_NAME as USER_NAME,b.NICK_NAME as NICK_NAME,a.AVATAR as AVATAR,b.BBS_COUNTER as BBS_COUNTER from USER a , USER_EXT b where b.BBS_COUNTER>0 AND a.UID=b.UID order by BBS_COUNTER desc";
 //------------------------结束-----------------------
 $cursor = exequery(TD::conn(),$query);

 $USER_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $USER_COUNT++;

   $USER_NAME = $ROW["USER_NAME"];
   $NICK_NAME = $ROW["NICK_NAME"];
   $AVATAR=$ROW["AVATAR"];
   $BBS_COUNTER=$ROW["BBS_COUNTER"];

   $MONEY=$BBS_COUNTER*10;

   $NICK_NAME=str_replace(">","&gt",$NICK_NAME);
//   $NICK_NAME=stripslashes($NICK_NAME);

   if($NICK_NAME=="")
      $NICK_NAME=_("未设置昵称");

   if($USER_COUNT==1)
   {
?>
<div>
<table class="TableList"  align="center" width="100%">
  <tr class="TableHeader">
      <td align="center"><?=_("排行")?></td>
      <td align="center"><?=_("昵称")?></td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      <td align="center"><?=_("实名")?></td>
<?
}
?>
      <td align="center"><?=_("发表文章数")?></td>
      <td align="center"><?=_("积分")?></td>
  </tr>

<?
    }
?>
    <tr class="table_row" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" style="	font-weight: normal;font-size: 12px;">
      <td align="center" nowrap><?=$USER_COUNT?></td>
      <td nowrap align="center"><?=$NICK_NAME?></td>

<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
     <td align="center" nowrap><?=$USER_NAME?></td>
<?
}
?>
      <td align="center" nowrap><?=$BBS_COUNTER?></td>
      <td align="center" nowrap><?=$MONEY?></td>
  </tr>
<?
 }

 if($USER_COUNT>0)
 {
?>
  </table>
</div>
<?
 }
 else
    Message("","<br>"._("积分榜暂空"));

 Button_Back();
?>

</body>
</html>