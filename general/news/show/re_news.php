<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

if(!isset($TYPE))
   $TYPE="0";
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("NEWS", 10);
if(!isset($start))
   $start=0;
 
$ROW=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,NICK_NAME");
$POST_PRIV=$ROW["POST_PRIV"];
$NICK_NAME=$ROW["NICK_NAME"];

$HTML_PAGE_TITLE = _("新闻评论");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function CheckForm()
{
   if(document.form1.CONTENT.value=="")
   { alert("<?=_("评论的内容不能为空！")?>");
     return (false);
   }

   if(document.getElementsByName("AUTHOR_NAME").length >1 && document.getElementsByName("AUTHOR_NAME").item(1).checked && document.form1.NICK_NAME.value=="")
   { alert("<?=_("昵称不能为空！")?>");
     return (false);
   }

   return (true);
}
</script>
<body class="bodycolor">

<?
// $query = "SELECT SUBJECT,ANONYMITY_YN,FORMAT,SUBJECT_COLOR from NEWS where NEWS_ID='$NEWS_ID' and PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID))";
 if($POST_PRIV==1 ||$_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT SUBJECT,ANONYMITY_YN,FORMAT,SUBJECT_COLOR,PUBLISH,PROVIDER from NEWS where NEWS_ID='$NEWS_ID'  ";
 else
    $query = "SELECT SUBJECT,ANONYMITY_YN,FORMAT,SUBJECT_COLOR,PUBLISH,PROVIDER from NEWS where NEWS_ID='$NEWS_ID'  and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID))";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
    $SUBJECT       = $ROW["SUBJECT"];
    $FORMAT        = $ROW["FORMAT"];
	$PROVIDER      = $ROW["PROVIDER"];
    $ANONYMITY_YN  = $ROW["ANONYMITY_YN"];
    $SUBJECT_COLOR = $ROW["SUBJECT_COLOR"];
    $SUBJECT       = td_htmlspecialchars($SUBJECT);
    $PUBLISH       = $ROW["PUBLISH"];
    if($SUBJECT_COLOR!="")
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
 }
 else
 {
 	  Message(_("错误"),_("此新闻不存在或您没有该新闻的查看权限"));
 	  exit;
 }

 if($ANONYMITY_YN=="2")
 {
 	  Message("",_("此新闻禁止评论"));
 	  exit;
 }
 
 
?>
<table width=100% border=0 cellspacing=0 cellpadding=0>
  <tr height="40">
    <td align=center><span class="big3"><?=_("原文")?> </span><a href="read_news.php?NEWS_ID=<?=$NEWS_ID?>" style="TEXT-DECORATION:underline"><span class="big3"><?=$SUBJECT?></span></a></span></td>
  </tr>
</table>

<?
 $query = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $COMMENT_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $COMMENT_COUNT=$ROW[0];

 if($COMMENT_COUNT==0)
 {
   Message("",_("暂无评论"));
  
  if($PUBLISH==1){ //如果是生效的评论才可以发表评论
?>
<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
  <table class="TableBlock" width="95%" align="center">
     <tr>
      <td class="TableHeader" colspan="2">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("发表评论：")?>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("内容：")?></td>
      <td class="TableData">
        <textarea cols="57" name="CONTENT" rows="5" class="BigInput" wrap="on"></textarea>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("署名：")?></td>
      <td class="TableData">
        <input type="radio" name="AUTHOR_NAME" value="USER_ID" <? if($ANONYMITY_YN=="0")echo "checked";?>>
        <input type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>
<?
        if($ANONYMITY_YN=="1")
        {
?>
        <input type="radio" name="AUTHOR_NAME" value="NICK_NAME" checked><?=_("昵称")?>
        <input type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$NICK_NAME?>">
<?
        }
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="2">
        <input type="hidden" value="<?=$NEWS_ID?>" name="NEWS_ID">
        <input type="hidden" value="<?=$MANAGE?>" name="MANAGE">
        <input type="submit" value="<?=_("发表")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();">
      </td>
    </tr>
  </table>
</form>
<?
   exit;
 }
}
?>
<table width=95% border=0 cellspacing=0 cellpadding=2 align="center" class="small1">
  <tr>
    <td>&nbsp;&nbsp;<?=sprintf(_("相关评论%d条"),$COMMENT_COUNT)?></td>
    <td align="right"><?=page_bar($start,$COMMENT_COUNT,$PAGE_SIZE)?></td>
  </tr>
</table>

 <table class="TableBlock" width="95%" align="center" class="small">

<?
 $COUNT=0;
 $NEWS_ID=intval($NEWS_ID);
 $query = "SELECT * from NEWS_COMMENT where NEWS_ID='$NEWS_ID' order by RE_TIME desc limit $start,$PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT++;
    $COMMENT_ID=$ROW["COMMENT_ID"];
    $PARENT_ID=$ROW["PARENT_ID"];
    $CONTENT=$ROW["CONTENT"];
    $RE_TIME=$ROW["RE_TIME"];
    $USER_ID=$ROW["USER_ID"];
    $NICK_NAME=$ROW["NICK_NAME"];

    $CONTENT=td_htmlspecialchars($CONTENT);
    $CONTENT=str_replace("\n","<br>",$CONTENT);

    $USER_NAME="";
    if($NICK_NAME=="")
    {
       $UID=UserId2Uid($USER_ID);
       if($UID!="")
       {
          $ROW1=GetUserInfoByUID($UID,"USER_NAME,DEPT_ID");
          $DEPT_ID=$ROW1["DEPT_ID"];
          $DEPT_NAME=dept_long_name($DEPT_ID);
          $USER_NAME="<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u>";
       }
    }
    else
    {
       $USER_NAME=$NICK_NAME;
    }

    $query = "SELECT CONTENT from NEWS_COMMENT where COMMENT_ID='$PARENT_ID'";
    $cursor1= exequery(TD::conn(),$query,$QUERY_MASTER);
    if($ROW1=mysql_fetch_array($cursor1))
    {
       $CONTENT1=$ROW1["CONTENT"];
       $CONTENT1=str_replace("<","&lt",$CONTENT1);
       $CONTENT1=str_replace(">","&gt",$CONTENT1);
       $CONTENT1=stripslashes($CONTENT1);
       $CONTENT1=str_replace("\n","<br>",$CONTENT1);
    }

    $query = "SELECT count(*) from NEWS_COMMENT where PARENT_ID='$COMMENT_ID'";
    $cursor1= exequery(TD::conn(),$query,$QUERY_MASTER);
    if($ROW1=mysql_fetch_array($cursor1))
       $RELAY_COUNT=$ROW1[0];
?>
          <tr>
            <td class="TableHeader">
              &nbsp;&nbsp;<?=$USER_NAME?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("发表时间：")?><?=$RE_TIME?>
            </td>
          </tr>
          <tr height="40">
            <td class="TableData">
              <?=$CONTENT?>
<?
if($PARENT_ID!=0)
{
?>
              <br><hr width="95%">
              <b>[<?=_("原贴")?>]</b><br>
              <?=$CONTENT1?>
<?
}
?>
            </td>
          </tr>
         
          <tr>
            <td class="TableControl" align="right">
            	 <?
          if($PUBLISH==1){
          ?>
              <a href="relay.php?COMMENT_ID=<?=$COMMENT_ID?>&NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>&MANAGE=<?=$MANAGE?>" style="text-decoration:underline"><?=_("回复本贴")?></a>&nbsp;&nbsp;&nbsp;
<?

if($_SESSION["LOGIN_USER_PRIV"] == 1 || $PROVIDER == $_SESSION["LOGIN_USER_ID"] || $USER_ID == $_SESSION["LOGIN_USER_ID"])
{
?>
              <a href="delete.php?COMMENT_ID=<?=$COMMENT_ID?>&NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>&MANAGE=<?=$MANAGE?>" style="text-decoration:underline"><?=_("删除")?></a>&nbsp;&nbsp;&nbsp;
<?
} }
?>
              <?=_("回复数：")?><?=$RELAY_COUNT?>&nbsp;&nbsp;
          </td>
        </tr>
        
<?
}

?>

  </table>
<?
if($PUBLISH==1){
?>
<br>
<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
  <table class="TableBlock" width="95%" align="center">
     <tr>
      <td class="TableHeader" colspan="2">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("发表评论：")?>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("内容：")?></td>
      <td class="TableData">
        <textarea cols="57" name="CONTENT" rows="5" class="BigInput" wrap="on"></textarea>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("署名：")?></td>
      <td class="TableData">
        <input type="radio" name="AUTHOR_NAME" value="USER_ID" <? if($ANONYMITY_YN=="0")echo "checked";?>>
        <input type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>
<?
        if($ANONYMITY_YN=="1")
        {
?>
        <input type="radio" name="AUTHOR_NAME" value="NICK_NAME" checked><?=_("昵称")?>
        <input type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$NICK_NAME?>">
<?
        }
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="2">
        <input type="hidden" value="<?=$NEWS_ID?>" name="NEWS_ID">
        <input type="hidden" value="<?=$MANAGE?>" name="MANAGE">
        <input type="submit" value="<?=_("发表")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();">
      </td>
    </tr>
  </table>
 <?
}
 ?>
</form>
</body>

</html>
