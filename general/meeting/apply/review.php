<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_file.php");


if($status=="1")
{
   $CUR_TIME=$SEND_TIME?$SEND_TIME:date("Y-m-d H:i:s",time());
   //--------- 上传附件 ----------
   if(count($_FILES)>1)
   {
      $ATTACHMENTS=upload();
      $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
   
      $ATTACHMENT_ID=$ATTACHMENTS["ID"];
      $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
   }
   $ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
   $ATTACHMENT_NAME.=$ATTACH_NAME;
   $query="insert into MEETING_COMMENT (MEETING_ID,USER_ID,ATTACHMENT_ID,ATTACHMENT_NAME,CONTENT,RE_TIME) values ($M_ID,'".$_SESSION["LOGIN_USER_ID"]."','$ATTACHMENT_ID','$ATTACHMENT_NAME','$CONTENT','$CUR_TIME')";
   exequery(TD::conn(),$query);
}

$HTML_PAGE_TITLE = _("会议纪要");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   /*if(getEditorHtml('SUMMARY')=="")
   { alert("<?=_("请填写会议纪要！")?>");
     return (false);
   }*/
<?
    $REMIND_URL="meeting/apply/review.php?M_ID=$M_ID";
    if($SMS_REMIND1=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$M_FACT_ATTENDEE,805,$_SESSION["LOGIN_BYNAME"]._("对会议纪要进行了评论"),$REMIND_URL,$M_ID);
    if($SMS2_REMIND1=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$M_FACT_ATTENDEE,$_SESSION["LOGIN_BYNAME"]._("对会议纪要进行了评论"),805);
?>
 return (true);
}

/*function InsertImage(src)
{
   AddImage2Editor('SUMMARY', src);
}*/
function close_this_new()
{
  TJF_window_close();
}
</script>
<?
$query = "SELECT SUMMARY_STATUS from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SUMMARY_STATUS =$ROW["SUMMARY_STATUS"];
	if($SUMMARY_STATUS != 2){
		message(_("提示："),_("该会议纪要暂时未发布！"));
?>
	<br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="javascript:window.close()" /></center>
<?
		exit;
	}
}
?>
<body class="bodycolor" >
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" width="22" height="18"><span class="big3"> <?=_("会议纪要")?></span>
    </td>
  </tr>
</table>
<?
//修改事务提醒状态--yc
update_sms_status('805',$M_ID);

$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $M_NAME=$ROW["M_NAME"];
    $SUMMARY=$ROW["SUMMARY"];
    $READ_PEOPLE_ID=$ROW["READ_PEOPLE_ID"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID1"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME1"];
    $M_FACT_ATTENDEE=$ROW["M_FACT_ATTENDEE"];
}
$TOK=strtok($READ_PEOPLE_ID,",");
while($TOK!="")
{
    $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $USER_NAME2.=$ROW["USER_NAME"].",";
    $TOK=strtok(",");
}

$TOK2=strtok($M_FACT_ATTENDEE,",");
while($TOK2!="")
{
    $query3 = "SELECT * from USER where USER_ID='$TOK2'";
    $cursor3= exequery(TD::conn(),$query3);
    if($ROW=mysql_fetch_array($cursor3))
        $M_FACT_ATTENDEE_NAME.=$ROW["USER_NAME"].",";
    $TOK2=strtok(",");
}
?>

<table class="TableBlock" width="90%" align="center"><tr>
   <td nowrap class="TableContent" width="80"><?=_("会议名称：")?></td>
   <td class="TableData" colspan="3"><?=$M_NAME?>
   </td>
</tr>
<tr>
   <td nowrap class="TableContent" width="80"><?=_("指定读者：")?></td>
   <td class="TableData" colspan="3"><?=$USER_NAME2?>
   </td>
</tr>
<tr>
   <td nowrap class="TableContent" width="80"><?=_("实际参会人员：")?></td>
   <td class="TableData" colspan="3"><?=$M_FACT_ATTENDEE_NAME?>
   </td>
</tr>
<tr>
   <td valign="top" nowrap class="TableContent" height="160" width="80"><?=_("纪要内容：")?></td>
   <td valign="top" class="TableData" colspan="3"><?=$SUMMARY?></td>      
</tr>

<tr>
   <td class="TableData"><?=_("附件文件")?>:</td><td class="TableData"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1)?></td>
</tr>
</table>
<br>
<table class="TableBlock" width="90%" align="center">
    <tr align="center" class="TableHeader">
      <td colspan="2"><?=_("最新5条评论")?></td>
    </tr>
<?
    $COUNT=0;
    $query = "SELECT * from MEETING_COMMENT where MEETING_ID='$M_ID' order by RE_TIME desc";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $COMMENT_ID=$ROW["COMMENT_ID"];
        $PARENT_ID=$ROW["PARENT_ID"];
        $CONTENT=$ROW["CONTENT"];
        $RE_TIME=$ROW["RE_TIME"];
        $USER_ID=$ROW["USER_ID"];
        $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];
        $NICK_NAME=$ROW["NICK_NAME"];

        $CONTENT=td_htmlspecialchars($CONTENT);
        $CONTENT=str_replace("\n","<br>",$CONTENT);

        $USER_NAME="";
        $query = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW1["DEPT_ID"];
            $DEPT_NAME=dept_long_name($DEPT_ID);
            $USER_NAME="<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u>";
        }

        $query = "SELECT CONTENT from MEETING_COMMENT where COMMENT_ID='$PARENT_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $CONTENT1=$ROW1["CONTENT"];
            $CONTENT1=str_replace("<","&lt",$CONTENT1);
            $CONTENT1=str_replace(">","&gt",$CONTENT1);
            $CONTENT1=stripslashes($CONTENT1);
            $CONTENT1=str_replace("\n","<br>",$CONTENT1);
        }
?>
          <tr>
            <td class="TableControl">
              <?=$USER_NAME?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("发表时间：")?><?=$RE_TIME?>&nbsp;&nbsp;&nbsp;
<?
if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]=='1')
{
?>
          <a align="right" href="review_delete.php?M_ID=<?=$M_ID?>&COMMENT_ID=<?=$COMMENT_ID?>" style="text-decoration:underline"><?=_("删除")?></a>
<?
}
?>
          </td>
        </tr>
          <tr height="40">
            <td class="TableData">
              <?=$CONTENT?>
            </td>
          </tr>
          
<?
}

if($COUNT==0)
{
?>
          <tr height="40">
            <td class="TableData"><?=_("暂无评论")?></td>
          </tr>
<?
}
?>

</table>
<br>

<table class="TableBlock" width="90%" align="center">	
	<form enctype="multipart/form-data" action="#"  method="post" name="form1" onSubmit="return CheckForm();">
<tr>
	<td class="TableHeader" colspan="2"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("添加评论：")?></td>
</tr>
<tr>
   <td valign="top" nowrap class="TableContent" width="80"><?=_("内容：")?></td>
   <td class="TableData" colspan="3">
   <textarea cols="57" name="CONTENT" rows="5" class="BigInput" wrap="on"></textarea>
   </td>
</tr>
<tr height="25" id="attachment1" style="display:none;">
  <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
  <td class="TableData">
    <script>ShowAddFile();</script>
    <!--  <input type="hidden" name="ATTACHMENT_ID" value="<?=$ATTACHMENT_ID?>">
    <input type="hidden" name="ATTACHMENT_NAME" value="<?=$ATTACHMENT_NAME?>">-->
  </td>
</tr>

<tr>
    <td nowrap class="TableData" width="80"> <?=_("提醒实际参会人员：")?></td>
    <td class="TableData" colspan="3">
    	 <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"8")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息 ")?></label>&nbsp;
    <?
    $query = "select * from SMS2_PRIV";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $TYPE_PRIV=$ROW["TYPE_PRIV"];

    if(find_id($TYPE_PRIV,8)) //检查该模块是否允许手机提醒
    {
    ?>
       <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"8")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("发送手机短信提醒")?></label>
    <?
    }
    ?>
    </td>
</tr>

<tr class="TableControl">
<td align="center" colspan="4">
	<input type="hidden" value="<?=$M_ID?>" name="M_ID">
	<input type="hidden" value="1" name="status">
	<input type="hidden" value="<?=$M_FACT_ATTENDEE?>" name="M_FACT_ATTENDEE">
  <input type="submit" value="<?=_("保存")?>" class="BigButton" title="<?=_("保存会议纪要")?>">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="close_this_new();"">
</td>
</tr>
	</form>

</table>

</body>
</html>
