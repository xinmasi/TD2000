<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("������Ŀ");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" width=22 height=22 align="absmiddle"><span class="big3">&nbsp;<?=_("������Ŀ")?></span><br>
    </td>
    </tr>
</table>
<br>
<table width="60%"  align="center" class="TableList">
  <tr class="TableHeader" align="center">
    <td><?=_("������Ŀ")?></td>
    <td><?=_("��ֵ��Χ")?></td>
  </tr>
<?
  $query = "SELECT GROUP_DESC from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
  $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
  $GROUP_DESC=_("������").$ROW["GROUP_DESC"];

  $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $MAX=$ROW["MAX"];
   $MIN=$ROW["MIN"];

?>
  <tr class="TableData">
    <td align="center">&nbsp;
     <?=$ITEM_NAME?>
    </td>
    <td align="center">
     <?=$MIN?><?=_("��")?><?=$MAX?>
   </td>
  </tr>
<?
}
?>
<tr class="TableData">
 <td align="left" colspan=2>
  <?=$GROUP_DESC?>
 </td>
</tr>
</table>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close();">
</div>
</body>
</html>