<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("������������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/workflow.gif" align="absmiddle"><span class="big3">&nbsp;<?=_("������������")?></span><br>
    </td>
    </tr>
</table>
<br>
<?
  $query = "SELECT FLOW_DESC from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
  $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
  $FLOW_DESC=$ROW["FLOW_DESC"];
  if ($FLOW_DESC=="")
  {
?>

<div align="center" title="<?=_("��ʾ��Ϣ��")?>">
<span style="BACKGROUND:#EEEEEE;COLOR:#FF6633;margin: 10px;border:1px dotted #FF6633;font-weight:bold;padding:8px;width=300">
<font><b><?=_("�޿�������")?></b></font>
</span>
</div>

<?
 }
 else
 {
?>
<div align="center" title="<?=_("��ʾ��Ϣ��")?>">
<span style="BACKGROUND:#EEEEEE;COLOR:#FF6633;margin: 10px;border:1px dotted #FF6633;font-weight:bold;padding:8px;width=300">
<font><b><?=$FLOW_DESC?></b></font>
</span>
</div>

<?
}
?>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("�ر�")?>" onclick="window.close();">
</div>
</body>
</html>