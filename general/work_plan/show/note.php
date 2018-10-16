<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$query="select * from WORK_DETAIL where DETAIL_ID='$DETAIL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $PROGRESS=$ROW["PROGRESS"];
  $PERCENT =$ROW["PERCENT"];  
  $WRITE_TIME=$ROW["WRITE_TIME"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];  
}

$HTML_PAGE_TITLE = _("进度日志");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<body bgcolor="#FFFFCC" topmargin="5">

<div class="small">
<?=_("日志时间：")?><?=substr($WRITE_TIME,0,10)?>  <?=get_week($WRITE_TIME)?>  <?=_("进度")?>:<?=$PERCENT?>%
<hr>
<div style="word-break:break-all;">
<?=_("进度详情：")?><br>
<?=$PROGRESS?>
</div>
<br><br>
<?
if($ATTACHMENT_NAME!="")
{
?>
<?=_("附件：")?><br>
<?
echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,0);
}
?>
</div>
</body>
</html>
