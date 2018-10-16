<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("审批意见");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

  $query="select AUDITER,REASON from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $AUDITER=$ROW["AUDITER"];
    $REASON=$ROW["REASON"];
  }
  $FROM_NAME=td_trim(GetUserNameById($AUDITER));
  
?>
<body bgcolor="#FFFFCC" topmargin="5">

<div class="small">
<?=$FROM_NAME?>  <?=_("对此公告通知的意见")?>
<hr>
<div style="word-break:break-all">
<?=$REASON?>
</div>
</div>
</body>
</html>
