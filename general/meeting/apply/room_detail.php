<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" HEIGHT="20" width="20" align="absmiddle"><span class="big3"> <?=_("����������")?></span>
    </td>
  </tr>
</table>

<?
//============================ ��ʾ����Ԥ����� =======================================
$query = "SELECT * from MEETING_ROOM where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR) or TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or (TO_ID='' and SECRET_TO_ID='')";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ROOM_COUNT++;

   $MR_ID=$ROW["MR_ID"];
   $MR_NAME=$ROW["MR_NAME"];
   $MR_CAPACITY=$ROW["MR_CAPACITY"];
   $MR_DEVICE=$ROW["MR_DEVICE"];
   $MR_DESC=$ROW["MR_DESC"];
   $MR_PLACE=$ROW["MR_PLACE"];

   if($ROOM_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   if($ROOM_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("����������")?></td>
    <td nowrap align="center"><?=_("�豸���")?></td>
    <td nowrap align="center"><?=_("���ڵص�")?></td>
    <td nowrap align="center"><?=_("����������")?></td>
  </tr>
<?
   }
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$MR_NAME?></td>
     <td nowrap align="center"><?=$MR_CAPACITY?></a></td>
     <td align="center"><?=$MR_DEVICE?></a></td>
     <td align="center"><?=$MR_PLACE?></td>
     <td align="center"><?=$MR_DESC?></td>
   </tr>
<?
}

if($ROOM_COUNT>0)
{
?>
<tr align="center" class="TableControl">
  <td colspan="5">
     <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
  </td>
</tr>
</table>
<?
}
else
   Message("",_("�޻�����"));
?>
</body>

</html>
