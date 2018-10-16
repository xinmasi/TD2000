<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("日志点评");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<style>
   .hr1{border-top:1px dotted gray;HEIGHT:0;}
</style>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script> 
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(getEditorText('CONTENT').length==0 && checkEditorDirty('CONTENT')=="")
   { alert("<?=_("点评内容不能为空！")?>");
     return (false);
   }
   return (true);
}


function InsertImage(src)
{
   AddImage2Editor('CONTENT', src);
}
function close_this_new()
{
	TJF_window_close();
}

</script>
<style>
  ol{margin-left:20px;}
</style>
<body class="bodycolor">

<?
$URL_SUBJECT  = $SUBJECT;
$URL_DIA_TYPE = $DIA_TYPE;
$query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DIA_TIME       = $ROW["DIA_TIME"];
   $DIA_DATE       = $ROW["DIA_DATE"];
   $NOTAGS_CONTENT = $ROW["CONTENT"];
   if($ROW["COMPRESS_CONTENT"] == "")
   {
      $CONTENT = $NOTAGS_CONTENT;
   }
   else
   {
      $CONTENT = @gzuncompress($ROW["COMPRESS_CONTENT"]);
      if($CONTENT===FALSE)
	  {
		  $CONTENT = $NOTAGS_CONTENT;
	  }
         
   }
   $SUBJECT         = $ROW["SUBJECT"];
   $DIA_TYPE        = $ROW["DIA_TYPE"];
   $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];

   if($SUBJECT=="")
   {
	 $SUBJECT=csubstr(strip_tags($CONTENT),0,50).(strlen($CONTENT)>50?"...":"");  
   }
   if($DIA_TIME=="0000-00-00 00:00:00")
   {
	 $DIA_TIME="";  
   }
      
}
?>
<div class="PageHeader">
   <div class="title"><?=_("日志点评")?></div>
</div>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=$SUBJECT?>
      </td>
      <td class="right"></td>
   </tr>
</table>

<form action="comment_submit.php" enctype="multipart/form-data" method="post" name="form1" onSubmit="return CheckForm();">
<div class="diary_type"><?=get_code_name($DIA_TYPE,"DIARY_TYPE");?> | <?=_("写日志时间：")?><?=$DIA_TIME?></div>
<div class="content" style="overflow-y:auto;overflow-x:auto;width=100%;height=100%"><?=$CONTENT?></div>
<?
if($ATTACHMENT_NAME!="")
{
?>
	<div class="content"><b><?=_("附件文件")?>:</b><br><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1)?></div>
<?
}
?>
  <div class="content">
<?

$COUNT  = 0;
$query  = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' order by SEND_TIME asc";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $USER_ID1                 = $ROW["USER_ID"];
   $SEND_TIME                = $ROW["SEND_TIME"];
   $COMMENT_ID               = $ROW["COMMENT_ID"];
   $CONTENT                  = $ROW["CONTENT"];
   $COMMENT_FLAG             = $ROW["COMMENT_FLAG"];
   $ATTACHMENT_ID_COMMENT    = $ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME_COMMENT  = $ROW["ATTACHMENT_NAME"];
   $CONTENT                  = str_replace("\"","'",$CONTENT);
   $query   = "SELECT * from USER where USER_ID='$USER_ID1'";
   $cursor1 = exequery(TD::conn(),$query);
   if($ROW1 = mysql_fetch_array($cursor1))
   {
	  $USER_NAME1=$ROW1["USER_NAME"]; 
   } 
  /*if($COMMENT_FLAG==0 && $DIA_TYPE!=2)
     $COMMENT_FLAG_DESC="<font color=red>"._("未读")."</font>";
  else if($DIA_TYPE!=2)
     $COMMENT_FLAG_DESC="<font color=green>"._("已读")."</font>";*/
$MSG2 = sprintf(_("%d楼%s点评%s%s"), $COUNT,"&nbsp;<b>".$USER_NAME1."</b>&nbsp;","&nbsp;&nbsp;".$SEND_TIME."&nbsp;",$COMMENT_FLAG_DESC);
?>
   <hr class="hr1">
     <div class="diary_comment"><?=$MSG2?>&nbsp;</div>
     <div class="replycontent">
<?
   echo "<p style='margin:5px 5px 0px 5px;padding:5px;'>".$CONTENT."</p>";
   if($ATTACHMENT_NAME_COMMENT != ""){
      echo "<div class='content'><b>"._("附件文件:")."</b><br>" . attach_link($ATTACHMENT_ID_COMMENT,$ATTACHMENT_NAME_COMMENT,1,1,1) . "</div>";
   }
   
   $query3  = "SELECT * from DIARY_COMMENT_REPLY where COMMENT_ID='$COMMENT_ID' order by REPLY_TIME asc";
   $cursor3 = exequery(TD::conn(),$query3);
   $REPLYER       = "";
   $REPLY_ID      = "";
   $COMMENT_ID    = "";
   $REPLY_TIME    = "";
   $REPLY_COMMENT = "";
   while($ROW3=mysql_fetch_array($cursor3))
   {
   	  $REPLY_ID      = $ROW3["REPLY_ID"];
   	  $COMMENT_ID    = $ROW3["COMMENT_ID"];
   	  $REPLY_TIME    = $ROW3["REPLY_TIME"];
   	  $REPLY_COMMENT = $ROW3["REPLY_COMMENT"];
   	  $REPLY_COMMENT = str_replace("\n","<br>",$REPLY_COMMENT);
      $REPLYER       = $ROW3["REPLYER"];
      $USER_NAME4    = td_trim(GetUserNameById($REPLYER));
      
      echo "<div class='diary_comment_replay'>
      "._("回复：").$REPLY_COMMENT."<br><br>".
      "<i>"._("回复时间：").$REPLY_TIME."&nbsp;&nbsp;"._("回复人：").$USER_NAME4."</i></div>";
   }
?>

     </div>
<?
}
?>
</div>
<?
$query  = "SELECT CONTENT from DIARY_COMMENT where DIA_ID='$DIA_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by SEND_TIME desc";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $CONTENT         = $ROW["CONTENT"];
   $COMMENT_CONTENT = str_replace("\"","'",$CONTENT);
}
?>
<table class="TableBlock" width="100%" align="center">
  
 
   <tr><td colspan="2">
<?
$editor = new Editor('CONTENT') ;
$editor->ToolbarSet = 'Basic';
$editor->Config = array("model_type" => "03");
$editor->Height = '320';
$editor->Create() ;
?>
      </td>
    </tr>
</table>
<table class="TableBlock" width="100%" align="center">
    <tr class="TableControl" style="display:none;">
         <td width="66" nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
         <td class="TableData">
           <script>ShowAddFile();ShowAddImage();</script>
           <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
           <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
         </td>
      </tr>
      <tr class="TableControl">
    <td colspan="2"><?echo sms_remind(13);?></td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="2">
        <input type="hidden" name="COMMENT_ID" value="<?=$COMMENT_ID?>">
        <input type="hidden" name="DIA_ID" value="<?=$DIA_ID?>">
        <input type="hidden" name="DIA_USER" value="<?=$USER_ID?>">
        <input type="hidden" name="FROM" value="<?=$FROM?>">
        <input type="hidden" name="BEGIN_DATE" value="<?=$BEGIN_DATE?>">
        <input type="hidden" name="END_DATE" value="<?=$END_DATE?>">
        <input type="hidden" name="SUBJECT" value="<?=$URL_SUBJECT?>">
        <input type="hidden" name="DIA_TYPE" value="<?=$URL_DIA_TYPE?>">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <input type="hidden" name="TO_ID1" value="<?=$TO_ID1?>">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
        <input type="hidden" name="COPYS_TO_ID" value="<?=$COPYS_TO_ID?>">

        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
<?
if ($FROM==3)
{
?>
	    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.opener.location.reload();self.close();">
<?
}else if($FROM==1)
  
{
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='user_search.php?FROM=<?=$FROM?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=$URL_SUBJECT?>&DIA_TYPE=<?=$URL_DIA_TYPE?>&TO_ID=<?=$TO_ID?>&TO_ID1=<?=$TO_ID1?>&PRIV_ID=<?=$PRIV_ID?>&COPYS_TO_ID=<?=$COPYS_TO_ID?>'">
<?
}else
 {
        if($FROM==10)
        {
        ?>
           <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='diary_body.php?IS_MAIN=<?=$IS_MAIN?>'">
        <?
        }
        else if($FROM==4)
        {
?>
        	 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../share_read.php?FROM_FLAG=1&DIA_ID=<?=$DIA_ID?>&IS_MAIN=<?=$IS_MAIN?>'">
<?
        }
        else
        {
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='user_diary.php?USER_ID=<?=$USER_ID?>&IS_MAIN=<?=$IS_MAIN?>'">
<?
        }
}
?>
      </td>
    </tr>
  </table>
</form>
</div>
</body>
</html>