<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�г�ʱ�̲�ѯ");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function CheckForm1()
{
   if(document.form1.START.value=="" || document.form1.END.value=="")
   { alert("<?=_("ģ����ѯ����ʼվ���յ�վ������Ϊ�գ��������벻��������")?>");
     return (false);
   }
   return (true);
}

function CheckForm2()
{
   if(document.form2.TRAIN.value=="")
   { alert("<?=_("��ȷ��ѯ�г��β���Ϊ�գ�")?>");
     return (false);
   }
   return (true);
}
</script>


<body class="bodycolor">
<?
if(!mysql_select_db("TRAIN", TD::conn()))
{
   Message(_("��ʾ"),_("�뵽��վ������������ ������ѡ����������ڰ�װ�г�ʱ�����ݿ�"));
   exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�г�ʱ��ģ����ѯ")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
 <form action="search.php"  name="form1" onsubmit="return CheckForm1();">
    	<?=_("��ʼվ��")?><input type="text" name="START" size="10" maxlength="50" class="BigInput" value="<?=$START_NAME?>" title="<?=_("��ʼվ���յ�վ�������벻��������")?>">&nbsp;&nbsp;
        <?=_("�յ�վ��")?><input type="text" name="END" size="10" maxlength="50" class="BigInput" value="<?=$END_NAME?>" title="<?=_("��ʼվ���յ�վ�������벻��������")?>">
      	<input type="submit" value="<?=_("��ѯ")?>" class="BigButton" name="button" title="<?=_("��ʼ��ѯ")?>">
 </form>
 </span>
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�г�ʱ�̾�ȷ��ѯ")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
 <form action="search.php" name="form2" onsubmit="return CheckForm2();">
	<?=_("���Σ�")?><input type="text" name="TRAIN" size="10" maxlength="50" class="Biginput" value="<?=$TRAIN?>">
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" name="button">
 </form>
 </span>
</div>

<br>
<?
Message("",_("���ݽ����ο�"));
?>
</body>
</html>
