<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�½�������");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{

   if(document.form1.SUBJECT.value=="")
   { alert("<?=_("���������Ʋ���Ϊ�գ�")?>");
     return (false);
   }

   return (true);
}
</script>


<body class="bodycolor" onload="document.form1.SUBJECT.focus();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�������")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="500" align="center" >
  <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"> <?=_("���������ƣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="SUBJECT" size="40" maxlength="100" class="BigInput" value="<?=$SUBJECT?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='../'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>