<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("会议主题");
include_once("inc/header.inc.php");
?>




<?
 $query = "SELECT * from NETMEETING where MEET_ID='$MEET_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $SUBJECT=$ROW["SUBJECT"];

    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
    $SUBJECT=stripslashes($SUBJECT);
 }
?>
<body bgcolor="#F1FAF5" topmargin="6" onunload="window.status='';">

<span class="big1"><b><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/netmeeting.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <?=_("会议主题：")?><?=$SUBJECT?></span>

</body>
</html>
