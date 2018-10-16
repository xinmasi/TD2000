<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("考核数据录入")?></span>
    </td>
  </tr>
</table>
<br>

<?
$query = "SELECT FLOW_TITLE from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $FLOW_DESC=$ROW["FLOW_TITLE"];
Message(_("提示"),_("考核任务名称：“").$FLOW_DESC."”");
?>

<br>
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="parent.location='index.php?connstatus=1'">&nbsp;
</div>

</body>
</html>
