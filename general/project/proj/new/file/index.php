<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��Ŀ�ĵ�");
include_once("inc/header.inc.php");
?>


<script>
function delete_sort(SORT_ID)
{
 msg='<?=_("ȷ��Ҫɾ�����ļ������⽫ɾ�����ļ����е������ļ��Ҳ��ɻָ���")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?SORT_ID="+SORT_ID+"&PROJ_ID=<?=$PROJ_ID?>";
  window.location=URL;
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½��ĵ�Ŀ¼")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("�½��ĵ�Ŀ¼")?>" class="BigButton" onClick="location='new/?PROJ_ID=<?=$PROJ_ID?>';">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�ĵ�Ŀ¼�б�")?></span>
    </td>
  </tr>
</table>

<br>

<?
 //============================ �����ļ��� =======================================
 $query = "SELECT * from PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' order by SORT_NO,SORT_NAME";
 $cursor= exequery(TD::conn(),$query);
 $SORT_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $SORT_COUNT++;

    $SORT_ID=$ROW["SORT_ID"];
    $SORT_NAME=$ROW["SORT_NAME"];

    $SORT_NAME=td_htmlspecialchars($SORT_NAME);

    if($SORT_COUNT==1)
    {
?>

    <table class="TableList" width="80%" align="center">

<?
    }

    if($SORT_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$SORT_NAME?></td>
      <td nowrap align="center">
          <a href="edit.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>"> �༭</a>&nbsp;
          <a href="javascript:delete_sort(<?=$SORT_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
          <a href="set_priv/?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>"> <?=_("Ȩ������")?></a>&nbsp;
      </td>
    </tr>
<?
 }

 if($SORT_COUNT==0)
 {
   Message("",_("��δ������Ŀ�ĵ�Ŀ¼��"));
   exit;
 }
 else
 {
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("Ŀ¼����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
   </thead>
   </table>
<?
 }
?>

</body>
</html>
