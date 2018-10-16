<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("日志点评");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(getEditorText('CONTENT').length==0 && getEditorHtml('CONTENT')=="")
   { alert("<?=_("日志内容不能为空！")?>");
     return (false);
   }
   return (true);
}

function InsertImage(src)
{
   AddImage2Editor('CONTENT', src);
}
</script>

<body class="bodycolor">
<?
$DIA_ID=intval($DIA_ID);
 $query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $DIA_TIME=$ROW["DIA_TIME"];
    $DIA_DATE=$ROW["DIA_DATE"];
    $CONTENT=$ROW["CONTENT"];
    $SUBJECT=$ROW["SUBJECT"];
    $DIA_TYPE=$ROW["DIA_TYPE"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    if($SUBJECT=="")
       $SUBJECT=csubstr(strip_tags($CONTENT),0,50).(strlen($CONTENT)>50?"...":"");
    if($DIA_TIME=="0000-00-00 00:00:00")
       $DIA_TIME="";
 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("日志点评")?> (<?=$SUBJECT?>)</span>
    </td>
  </tr>
</table>

  <table width="550" align="center" class="TableBlock">
  <form action="comment_submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr class="TableHeader">
      <td><?=_("日志类型：")?><?=get_code_name($DIA_TYPE,"DIARY_TYPE");?></td>
    </tr>
    <tr>
      <td class="TableData" height="100" valign="top">
      <font color="#0000FF">[<?=_("写日志时间：")?><?=$DIA_TIME?>]</font><br>
      <?=$CONTENT?>
<?
 $ATTACH_ARRAY = trim_inserted_image("", $ATTACHMENT_ID, $ATTACHMENT_NAME);
 if($ATTACH_ARRAY["NAME"]!="")
 {
	 echo "<br><br>"._("附件：")."<br>";
	 echo attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1,0,0,1,1,0,"diary");
 }

 $COUNT=0;
 $query = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' order by SEND_TIME desc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT++;
    $USER_ID1=$ROW["USER_ID"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $CONTENT=str_replace("\"","'",$CONTENT);

    $query = "SELECT * from USER where USER_ID='$USER_ID1'";
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
      <font color="#0000FF"><?=$USER_NAME1?>&nbsp;&nbsp;<?=$SEND_TIME?></font>&nbsp;&nbsp;
      <br>
      <?=$CONTENT?><br><br>
<?
}
?>
      </td>
    </tr>
<?
 $query = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $CONTENT=$ROW["CONTENT"];
    $COMMENT_CONTENT=str_replace("\"","'",$CONTENT);
 }
?>
    <tr>
      <td class="TableData">
<?
$editor = new Editor('CONTENT') ;
$editor->ToolbarSet = 'Basic';
$editor->Config = array('model_type' => '17');
$editor->Value = $COMMENT_CONTENT ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr>
      <td class="TableData">
<?=sms_remind(13);?>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td nowrap>
        <input type="hidden" name="COMMENT_ID" value="<?=$COMMENT_ID?>">
        <input type="hidden" name="DIA_ID" value="<?=$DIA_ID?>">
        <input type="hidden" name="DIA_USER" value="<?=$USER_ID?>">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='user_diary.php?USER_ID=<?=$USER_ID?>'">
      </td>
    </tfoot>
  </table>
</form>

</body>
</html>