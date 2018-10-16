<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("讨论区设置");
include_once("inc/header.inc.php");
?>



<script>
function delete_board(BOARD_ID)
{
 msg="<?=_('确认要删除该讨论区吗？这将删除该讨论区中的所有文章且不可恢复！')?>";
 if(window.confirm(msg))
 {
    URL="delete.php?BOARD_ID="+BOARD_ID;
    window.location=URL;
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建讨论区")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value=<?=_("新建讨论区")?> class="BigButton" onClick="location='edit.php';" title=<?=_("新建讨论区")?>>
</div>
<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理讨论区")?></span><br>
    </td>
  </tr>
</table>
<br>
<div align="center">

<?
$POSTFIX = _("，");
//-------列出所有可见公告板标题-------
$query = "SELECT * from BBS_BOARD order by BOARD_NO";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);

$BOARD_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $BOARD_COUNT++;

  $BOARD_ID = $ROW["BOARD_ID"];
  $BOARD_NO = $ROW["BOARD_NO"];
  $DEPT_ID = $ROW["DEPT_ID"];
  $PRIV_ID = $ROW["PRIV_ID"];      
  $USER_ID = $ROW["USER_ID"];
  $BOARD_NAME = $ROW["BOARD_NAME"];
  $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
  $BOARD_HOSTER = $ROW["BOARD_HOSTER"];
  $NEED_CHECK = $ROW["NEED_CHECK"];

  $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
  $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
  $BOARD_NAME=stripslashes($BOARD_NAME);

   if($NEED_CHECK=="0")
      $NEED_CHECK=_("不需审核");
   else if($NEED_CHECK=="1")
      $NEED_CHECK=_("需要审核");
 
   if($ANONYMITY_YN=="0")
      $ANONYMITY_YN=_("禁止匿名");
   else if($ANONYMITY_YN=="1")
      $ANONYMITY_YN=_("允许匿名");
   else if($ANONYMITY_YN=="2")
      $ANONYMITY_YN=_("禁止发帖");

  $TO_NAME="";
  if($DEPT_ID=="ALL_DEPT")
     $TO_NAME=_("全体部门");
  else
  {
    $TO_NAME="";
    $TOK=strtok($DEPT_ID,",");
    while($TOK!="")
    {
      if($TO_NAME!="")
         $TO_NAME.=",";
      $query1="select * from DEPARTMENT where DEPT_ID='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $TO_NAME.=$ROW["DEPT_NAME"];
      $TOK=strtok(",");
     }
  }

  $TO_NAME_TITLE="";
  $TO_NAME_STR="";
  if($TO_NAME!="")
  {
     if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
        $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
     $TO_NAME_TITLE.=_("部门：").$TO_NAME;
     $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
  }

  $PRIV_NAME="";
  $PRIV_ID=td_trim($PRIV_ID);
  if($PRIV_ID!="")
  {
  $query1 = "SELECT * from USER_PRIV where USER_PRIV in ($PRIV_ID)";
  $cursor1= exequery(TD::conn(),$query1);
  while($ROW=mysql_fetch_array($cursor1))
     $PRIV_NAME.=$ROW["PRIV_NAME"]._("，");
  }
  if($PRIV_NAME!="")
  {
     $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
     if($TO_NAME_TITLE!="")
        $TO_NAME_TITLE.="\n\n";
     $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
     $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
  }

  $USER_NAME="";
  $query1 = "SELECT * from USER where find_in_set(USER_ID,'$USER_ID')";
  $cursor1= exequery(TD::conn(),$query1);
  while($ROW=mysql_fetch_array($cursor1))
     $USER_NAME.=$ROW["USER_NAME"]._("，");
  if($USER_NAME!="")
  {
     $USER_NAME=substr($USER_NAME,0,-strlen($POSTFIX));
     if($TO_NAME_TITLE!="")
        $TO_NAME_TITLE.="\n\n";
     $TO_NAME_TITLE.=_("人员：").$USER_NAME;
     $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
  }

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

if($BOARD_COUNT==1)
{
?>
  <table class="TableList" width="85%">
    <tr class="TableHeader">
    	<td nowrap align="center"><?=_("序号")?></td>
      <td nowrap align="center"><?=_("讨论区")?></td>
      <td nowrap align="center"><?=_("开放范围")?></td>
      <td nowrap align="center"><?=_("审核")?></td>
      <td nowrap align="center"><?=_("发帖")?></td>
      <td nowrap align="center"><?=_("版主")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
}
if($BOARD_COUNT%2==1)
   $TableLine="TableLine1";
else
   $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
    	<td align="center"><?=$BOARD_NO?></td>
      <td align="center"><?=$BOARD_NAME?></td>
      <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
      
      <td align="center"><?=$NEED_CHECK?></td>
      <td align="center"><?=$ANONYMITY_YN?></td>
      <td align="left"><?=$BOARD_HOSTER_NAME?></td>
      <td nowrap align="center">
          <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>&IS_MAIN=<?=$IS_MAIN?>"><?=_("编辑")?></a>
          <a href="javascript:delete_board(<?=$BOARD_ID?>);"><?=_("删除")?></a></td>
    </tr>
<?
}//while ROW

if($BOARD_COUNT>0)
{
?>
 </table>
<?
}
else
   Message("",_("尚未定义讨论区"));
?>

</div>
</body>
</html>