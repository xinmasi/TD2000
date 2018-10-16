<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("查看日志");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_comment(COMMENT_ID,USER_NAME)
{
  var msg='<?=_("删除该点评会连带日志回复一并删除，确认删除吗？")?>';
  if(window.confirm(msg))
  {
  	var URL="delete.php?COMMENT_ID="+COMMENT_ID+"&DIA_ID=<?=$DIA_ID?>&USER_NAME="+USER_NAME+"&FROM=<?=$FROM?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=$SUBJECT?>";
  	location=URL;
  }
}
</script>

<?
$query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DIA_TIME=$ROW["DIA_TIME"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $WRITE_USER_ID=$ROW["USER_ID"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $DIA_TYPE=$ROW["DIA_TYPE"];
   $READERS=$ROW["READERS"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];
   if($ROW["COMPRESS_CONTENT"] == "")
   {
      $CONTENT=$NOTAGS_CONTENT;
   }
   else
   {
      $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
      if($CONTENT===FALSE)
         $CONTENT=$NOTAGS_CONTENT;
   }
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($DIA_TIME=="0000-00-00 00:00:00")
      $DIA_TIME="";
}

if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]) and $WRITE_USER_ID!=$_SESSION["LOGIN_USER_ID"])
   $READERS = $READERS.$_SESSION["LOGIN_USER_ID"].",";
   $DIA_ID  = intval($DIA_ID);
$query2="update DIARY set READERS='$READERS' where DIA_ID='$DIA_ID'";
exequery(TD::conn(),$query2);
?>

<body class="bodycolor">

<div align="center" class="Big1">
<b>[<?=$USER_NAME?> - <?=_("工作日志查询")?>]</b>
</div>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=sprintf(_("查看日志（%s）"), $DIA_DATE)?></span>
    </td>
  </tr>
</table>

<br>
  <table class="TableList" width="97%"  align="center">
    <tr class="TableHeader">
      <td><?=_("日志类型：")?><?=get_code_name($DIA_TYPE,"DIARY_TYPE");?></td>
    </tr>
<?
if($ATTACHMENT_NAME!="")
{
?>
    <tr>
      <td class="TableData"><?=_("附件文件")?>:<br><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1)?></td>
    </tr>
<?
}
?>
    <tr>
      <td class="TableData" height="100" valign="top">
      <font color="#0000FF">[<?=_("写日志时间：")?><?=$DIA_TIME?>]</font><br>
<?
echo $CONTENT;

$COUNT=0;
$query = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' order by SEND_TIME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $COUNT++;
  $COMMENT_ID=$ROW["COMMENT_ID"];
  $USER_ID=$ROW["USER_ID"];
  $SEND_TIME=$ROW["SEND_TIME"];
  $CONTENT=$ROW["CONTENT"];

  $query = "SELECT * from USER where USER_ID='$USER_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW1=mysql_fetch_array($cursor1))
     $USER_NAME1=$ROW1["USER_NAME"];

  if($COUNT==1)
  {
?>
    <br><br>
    <b>[<?=_("以下是点评内容")?>]</b><br>
<?
  }
?>
    <font color="#0000FF"><?=$COUNT?><?=_("楼")?> <?=$USER_NAME1?>&nbsp;&nbsp;<?=$SEND_TIME?></font>&nbsp;&nbsp;
<?
if($USER_ID == $_SESSION["LOGIN_USER_ID"])
{
?>
    <a href="javascript:delete_comment('<?=$COMMENT_ID?>','<?=$USER_NAME?>');"><?=_("删除")?></a>
<?
}
?>
    <div class="replycontent">
<?
   echo "<p style='margin:5px 5px 0px 5px;padding:5px;'>".$CONTENT."</p>";
   $query3 = "SELECT * from DIARY_COMMENT_REPLY where COMMENT_ID='$COMMENT_ID' order by REPLY_TIME asc";
   $cursor3= exequery(TD::conn(),$query3);
   $REPLYER="";
   $REPLY_ID="";
   $COMMENT_ID="";
   $REPLY_TIME="";
   $REPLY_COMMENT="";
   while($ROW3=mysql_fetch_array($cursor3))
   {
   	  $REPLYER=$ROW3["REPLYER"];
   	  $REPLY_ID=$ROW3["REPLY_ID"];
   	  $COMMENT_ID=$ROW3["COMMENT_ID"];
   	  $REPLY_TIME=$ROW3["REPLY_TIME"];
   	  $REPLY_COMMENT=$ROW3["REPLY_COMMENT"];
   	  $REPLY_COMMENT=str_replace("\n","<br>",$REPLY_COMMENT);

      $query4 = "SELECT USER_NAME from USER where USER_ID='$REPLYER'";
      $cursor4= exequery(TD::conn(),$query4);
   	  if($ROW4=mysql_fetch_array($cursor4))
   	     $USER_NAME4=$ROW4["USER_NAME"];
      echo "<div style='word-break:break-all;dispaly: block;background-color: #E1E1E1;margin:0px 5px 5px 5px;padding:5px;border:1px solid #666666;line-height:14px;'>
      "._("回复：").$REPLY_COMMENT."<br><br><i>".
      _("回复时间：").$REPLY_TIME."&nbsp;&nbsp;"._("回复人：").$USER_NAME4."</i></div>";
   }
?>
    </div>
<?
}
?>
    </td>
  </tr>

</table>
<?
Button_Back();
?>
</body>
</html>