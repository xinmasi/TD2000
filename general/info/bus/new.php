<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�½�·��");
include_once("inc/header.inc.php");
?>





<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
<? if($ID=="")
{
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/info.gif" height="22" width="20" align="absmiddle"><span class="big3">  <?=_("�½���·")?></span>
<?
}
else
{
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/info.gif" height="22" width="20" align="absmiddle"><span class="big3">  <?=_("�༭��·")?></span>

<?
}
?>
    </td>
  </tr>
</table>
<?
$TABLE=$CITY_ID."_LINE";

if($ID!="")
{
   mysql_select_db("BUS", TD::conn());

   $query="SELECT * from $TABLE where ID='$ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $ID=$ROW["id"];
      $LINEID=$ROW["lineid"];
      $STARTTIME=$ROW["startTime"];
      $ENDTIME=$ROW["endTime"];
      $BUSTYPE=$ROW["busType"];
      $PASSBY=$ROW["PassBy"];
   }
}
   
   mysql_select_db("BUS", TD::conn());

   $query="SELECT * from CITY where CITY_ID='$CITY_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $CITY_ID=$ROW["city_id"];
      $CITY_NAME=$ROW["city_name"];
    }

?>
<table class="TableBlock" width="450" align="center">
<form enctype="multipart/form-data" action="add.php" method="post" name="form1">
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("���У�")?></td>
    <td class="TableData" colspan="3">
    	<?=$CITY_NAME?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("��·��")?></td>
    <td class="TableData" colspan="3">
      <input type="text" name="LINEID" size="8" maxlength="100" class="BigInput" value="<?=$LINEID?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("�װ೵ʱ�䣺")?></td>
    <td class="TableData" colspan="3">
      <input type="text" name="STARTTIME" size="8" maxlength="100" class="BigInput" value="<?=$STARTTIME?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("ĩ�೵ʱ�䣺")?></td>
    <td class="TableData" colspan="3">
      <input type="text" name="ENDTIME" size="8" maxlength="100" class="BigInput" value="<?=$ENDTIME?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_("���ͣ�")?></td>
    <td class="TableData" colspan="3">
      <input type="text" name="BUSTYPE" size="8" maxlength="100" class="BigInput" value="<?=$BUSTYPE?>">(<?=_("�磺��ͨ�����յ�����С��ר�ߣ�������·")?>)
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent" width="80"><?=_(";��վ�㣺")?></td>
    <td class="TableData" colspan="3">
      <textarea name="PASSBY" class="BigInput" cols="45" rows="8"><?=$PASSBY?></textarea><br> <?=_("ע�⣺����Ӣ�Ķ��ŷָ������磺������,�微��")?>
    </td>
  </tr>
  <tr class="TableControl">
    <td nowrap colspan="4" align="center">
    	<input type="hidden" value="<?=$TABLE?>" name="TABLE">
    	<input type="hidden" value="<?=$ID?>" name="ID">
      <input type="submit" value="<?=_("����")?>" class="BigButton" >&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("����")?>" onclick="history.back();">
    </td>
  </tr>
</form>
</table>
</body>
</html>