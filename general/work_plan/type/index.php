<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ƻ���������");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/validation/validationEngine.jquery.min.css">
<script src="/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight",maxErrorsPerField:1});
});

function delete_type(TYPE_ID)
{
 msg='<?=_("ȷ��Ҫɾ���üƻ����ͣ�")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TYPE_ID=" + TYPE_ID;
  window.location=URL;
 }
}


function delete_all()
{
 msg='<?=_("ȷ��Ҫɾ�����мƻ�������")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}
</script>


<body class="bodycolor" onload="document.form1.TYPE_NO.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("��Ӽƻ�����")?></span>
    </td>
  </tr>
</table>
<form action="add.php"  method="post" id="form1" name="form1">
  <table class="TableBlock"  width="450"  align="center" >
   <tr>
    <td nowrap class="TableData"><?=_("����ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NO" class="BigInput validate[required,custom[onlyNumberSp],min[0]]" data-prompt-position="centerRight:-2,-7" size="2" maxlength="10">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�ƻ��������ƣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput validate[required]" data-prompt-position="centerRight:-2,-7" size="25" maxlength="25">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("���")?>" class="BigButton" title="<?=_("��Ӽƻ�����")?>" name="button">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("����ƻ�����")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ ��ʾ�Ѷ������ =======================================
 $query = "SELECT * from PLAN_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);

 $TYPE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_COUNT++;
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NO=$ROW["TYPE_NO"];
    $TYPE_NAME=$ROW["TYPE_NAME"];

    if($TYPE_COUNT==1)
    {
?>

    <table class="TableList" width="450">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$TYPE_NO?></td>
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center" width="80">
      <a href="edit.php?TYPE_ID=<?=$TYPE_ID?>"> <?=_("�༭")?></a>
      <a href="javascript:delete_type('<?=$TYPE_ID?>');"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
 }

 if($TYPE_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("�����")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    <thead class="TableControl">
      <td nowrap align="center" colspan="4">
      <input type="button" class="BigButton" OnClick="javascript:delete_all();" value="<?=_("ȫ��ɾ��")?>">
      </td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("��δ����ƻ�����"));
?>

</div>

</body>
</html>