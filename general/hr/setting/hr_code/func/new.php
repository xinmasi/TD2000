<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����༭");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.PARENT_NO.value=="")
   { alert("<?=_("���������಻��Ϊ�գ�")?>");
     return (false);
   }
   
   if(document.form1.CODE_NO.value=="")
   { alert("<?=_("�����Ų���Ϊ�գ�")?>");
     return (false);
   }
   
   if(document.form1.CODE_ORDER.value=="")
   { alert("<?=_("����Ų���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.CODE_NAME.value=="")
   { alert("<?=_("�������Ʋ���Ϊ�գ�")?>");
     return (false);
   }
   
   if(isNaN(document.form1.CODE_ORDER.value))
   { alert("<?=_(" ����ű���Ϊ���֣�")?>");
     return (false);
   }

    if(isNaN(document.form1.CODE_NO.value))
    { alert("<?=_(" �����ű���Ϊ���֣�")?>");
        return (false);
    }
}
</script>


<body class="bodycolor" onload="document.form1.CODE_NO.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("���Ӵ�����")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center">
  <form action="insert.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr height="30">
    <td nowrap class="TableData" width="120"><?=_("���������ࣺ")?></td>
    <td nowrap class="TableData">
        <select name="PARENT_NO" class="BigSelect">
<?
 $query = "SELECT * from HR_CODE where PARENT_NO='' order by CODE_ORDER";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $CODE_NO=$ROW["CODE_NO"];
    $CODE_NAME=$ROW["CODE_NAME"];
?>
          <option value="<?=$CODE_NO?>"<? if($CODE_NO==$PARENT_NO) echo " selected";?>><?=$CODE_NAME?></option>
<?
 }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�����ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_NO" class="BigInput" size="20" maxlength="100" value="">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("����ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_ORDER" class="BigInput" size="20" maxlength="100" value="">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�������ƣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="CODE_NAME" class="BigInput" size="20" maxlength="100" value="">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="history.back();">
    </td>
  </form>
</table>

</body>
</html>