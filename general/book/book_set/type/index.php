<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ͼ��������");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TYPE_NAME.value=="")
   { alert("<?=_("������Ʋ���Ϊ�գ�")?>");
     return (false);
   }
}

function delete_type(TYPE_ID)
{
 msg='<?=_("ȷ��Ҫɾ���������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TYPE_ID=" + TYPE_ID;
  window.location=URL;
 }
}
</script>

<body class="bodycolor" onload="document.form1.TYPE_NAME.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("���ͼ�����")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock"  width="400"  align="center" >
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("������ƣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput" size="25" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("���")?>" class="BigButton" title="<?=_("������")?>" name="button">
    </td>
  </form>
</table>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("ͼ��������")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ ��ʾ�Ѷ������ =======================================
 $query = "select * from BOOK_TYPE order by TYPE_ID";
 $cursor= exequery(TD::conn(),$query);

 $TYPE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_COUNT++;
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];

    if($TYPE_COUNT==1)
    {
?>

    <table class="TableList" width="400">

<?
    }
    if($TYPE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center" width="80">
      <a href="edit.php?TYPE_ID=<?=$TYPE_ID?>"> <?=_("�༭")?></a>
      <a href="javascript:delete_type(<?=$TYPE_ID?>);"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
 }

 if($TYPE_COUNT>0)
 {
?>
    <tr class="TableControl">
      <td colspan="4" align="center">
        &nbsp;
      </td>
    </tr>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("ͼ�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("��δ����"));
?>

</div>

</body>
</html>