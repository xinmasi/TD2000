<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;
?>

<script>
function CheckForm()
{
   if(document.form1.ITEM_NAME.value=="")
   { alert("<?=_("������Ŀ���ܿգ�����")?>");
   	 form1.ITEM_NAME.focus();
     return (false);
   }
    if(document.form1.MIN.value=="")
   { alert("<?=_("��Сֵ���ܿգ�����")?>");
   	 form1.MIN.focus();
     return (false);
   }
    if(document.form1.MAX.value=="")
   { alert("<?=_("���ֵ���ܿգ�����")?>");
   	  form1.MAX.focus();
     return (false);
   }
   if(checknum(document.form1.MAX.value)=="0")
   { alert("<?=_("��ֵ��Χ�������֣�����")?>");
   	  form1.MAX.focus();
     return (false);
   }
     if(checknum(document.form1.MIN.value)=="0")
   { alert("<?=_("��ֵ��Χ���������֣�����")?>");
   	 form1.MIN.focus();
     return (false);
   }

   if(Number(document.form1.MIN.value)>=Number(document.form1.MAX.value))
   { alert("<?=_("��ֵ��Χ���벻��ȷ������")?>");
   	 form1.MIN.focus();
     return (false);
   }
   return (true);
}

function checknum(p)
{

var l = p.length;
var count=0;
for(var i=0; i<l; i++)
{
var digit = p.charAt(i);
if(digit == "." )
{
  ++count;
  if(count>1)
  {
   return 0;
  }
  }
else if(digit < "0" || digit > "9")
  {
   return 0;
  }
  }
return 1;
}


</script>
<?
$HTML_PAGE_TITLE = _("����ָ�꼯��ϸ");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">

  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/score_item.gif" align="absmiddle"><span class="big3"> &nbsp;<?=_("����ָ�꼯��ϸ")?></span>
    </td>
  </tr>
</table>
<br>
<table border="0" width="70%" cellpadding="2" cellspacing="1" align="center" bgcolor="#000000" class="small">
  <tr class="TableHeader" align="center">
    <td width="30%"><?=_("������Ŀ")?></td>
    <td width="20%"><?=_("��ֵ��Χ")?></td>
    <td width="20%"><?=_("����")?></td>
  </tr>
<?
$GROUP_ID=intval($GROUP_ID);
$query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_ID=$ROW["ITEM_ID"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $MAX=$ROW["MAX"];
   $MIN=$ROW["MIN"];

?>
  <tr class="TableData">
    <form method="post" action="update.php?GROUP_ID=<?=$GROUP_ID?> & ITEM_ID=<?=$ITEM_ID?> & CUR_PAGE=<?=$CUR_PAGE?>">
    <td align="center">&nbsp;
     <INPUT type="text" name="ITEM_NAME" class=SmallInput size="20" value="<?=$ITEM_NAME?>">
    </td>

    <td align="center">
     <INPUT type="text"name="MIN" class=SmallInput size="3" value="<?=$MIN?>"><?=_("��")?>
     <INPUT type="text"name="MAX" class=SmallInput size="3" value="<?=$MAX?>">
    </td>

    <td align="center">

    <input type="submit" name="submit" value="<?=_("�޸�")?>" class="SmallButton">&nbsp;&nbsp;
    <input type="button" value="<?=_("ɾ��")?>" class="SmallButton" name="button" onclick="if (confirm('<?=_("�����Ҫɾ������ѡ����")?>\n\n<?=_("ע�⣺�ò������ɻָ���")?>'))location.href='delete.php?ITEM_ID=<?=$ITEM_ID?>&GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>'">
    </td>
    </form>
  </tr>
<?
}
?>
  <tr class="TableControl">
    <form method="post" name="form1" action="add.php?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>" onsubmit="return CheckForm();">
    <td colspan="4"><?=_("������Ŀ")?>:
    	<INPUT type="text"name="ITEM_NAME" class=SmallInput size="20">
    &nbsp;
     <?=_("��ֵ��Χ")?>:
     <INPUT type="text"name="MIN" class=SmallInput size="3"><?=_("��")?>
     <INPUT type="text"name="MAX" class=SmallInput size="3">
   &nbsp;&nbsp;
     <input type="submit" name="submit" class="SmallButton" value="<?=_("���")?>"></td>
    </form>
  </tr>
</table>

<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("����")?>" onclick="location='../index.php?CUR_PAGE=<?=$CUR_PAGE?>'">
</div>
</body>
</html>