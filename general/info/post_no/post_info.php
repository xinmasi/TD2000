<?
include_once("inc/auth.inc.php");
mysql_select_db("BUS", TD::conn());

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("��������")?> - <?=$PROVINCE?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
if(!$WORLD)
{
?>
  <table class="TableList" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��/��")?></td>
      <td nowrap align="center"><?=_("�ֵ�")?></td>
      <td nowrap align="center"><?=_("�ʱ�")?></td>
    </tr>
<?
}
else
{
?>
  <table class="TableList" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("�ʱ�")?></td>
    </tr>
<?
}

 //============================ ��ʾ���� =======================================
 $query = "SELECT * from POST_TEL where PROVINCE='$PROVINCE' and POST_NO<>'' order by POST_NO";
 $cursor= exequery(TD::conn(),$query);

 $AREA_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $AREA_COUNT++;

    $CITY=$ROW["CITY"];
    $COUNTY=$ROW["COUNTY"];
    $TOWN=$ROW["TOWN"];

    $POST_NO=$ROW["POST_NO"];

    if($AREA_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$CITY?></td>
      <td nowrap align="center"><?=$COUNTY?></td>
      <td nowrap align="center"><?=$TOWN?></td>
      <td nowrap align="center"><?=$POST_NO?></td>
    </tr>
<?
 }
?>
  </table>

</div>

<?
Button_Back();
?>
</body>
</html>
