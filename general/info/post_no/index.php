<?
include_once("inc/auth.inc.php");
mysql_select_db("BUS", TD::conn());

$HTML_PAGE_TITLE = _("���������ѯ");
include_once("inc/header.inc.php");
?>



<script>
function CheckForm1()
{
   if(document.form1.AREA.value=="")
   {
     alert("<?=_("��ѯ��������Ϊ��")?>");
     return false;
   }
   else
     return true;
}

function CheckForm2()
{
   if(document.form2.POST_NO.value=="")
   {
     alert("<?=_("��ѯ��������Ϊ��")?>");
     return false;
   }
   else
     return true;
}
</script>


<body class="bodycolor" onload="document.form1.AREA.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("���������ѯ")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

  <table class="TableBlock" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center" colspan="5"><b><?=_("ʡ(ֱϽ��/������)")?></b></td>
    </tr>

<?
 //============================ ��ʾʡ�� =======================================
 $query = "SELECT PROVINCE from POST_TEL where NO<61811 group by PROVINCE";
 $cursor= exequery(TD::conn(),$query);

 $PROVINCE_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $PROVINCE_COUNT++;
    $PROVINCE=$ROW["PROVINCE"];

    if($PROVINCE_COUNT%5==1)
    {
?>
    <tr class="TableData">
<?
    }
?>
      <td nowrap align="center"><a href="post_info.php?PROVINCE=<?=$PROVINCE?>"><?=$PROVINCE?></td>

<?
    if($PROVINCE_COUNT%5==0)
    {
?>
    </tr>
<?
    }

 }

 $REMAIN=5-$PROVINCE_COUNT%5;

 if($REMAIN==5)
    $REMAIN=0;

 for($I=1;$I<=$REMAIN;$I++)
 {
?>
  <td nowrap align="center">&nbsp;</td>
<?
 }
?>
    </tr>
  </table>
<br>

  <table class="TableBlock" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center" colspan="5"><b><?=_("����")?> - <?=_("����")?></b></td>
    </tr>


<?
 //============================ ��ʾ������ =======================================
 $query = "SELECT PROVINCE from POST_TEL where NO>=61811 group by PROVINCE";
 $cursor= exequery(TD::conn(),$query);

 $PROVINCE_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $PROVINCE_COUNT++;
    $PROVINCE=$ROW["PROVINCE"];

    if($PROVINCE_COUNT%5==1)
    {
?>
    <tr class="TableData">
<?
    }
?>
      <td nowrap align="center"><a href="post_info.php?WORLD=1&PROVINCE=<?=$PROVINCE?>"><?=$PROVINCE?></td>

<?
    if($PROVINCE_COUNT%5==0)
    {
?>
    </tr>
<?
    }

 }

 $REMAIN=5-$PROVINCE_COUNT%5;

 if($REMAIN==5)
    $REMAIN=0;

 for($I=1;$I<=$REMAIN;$I++)
 {
?>
  <td nowrap align="center">&nbsp;</td>
<?
 }
?>
    </tr>
  </table>
<br>

<table class="TableBlock" width="450" align="center">
<tr class="TableHeader">
    <td colspan="3" align="center"><b><?=_("ģ����ѯ")?></b></td>
</tr>

<form name=form1 method="post" action="search.php" onsubmit="return CheckForm1();">
<tr class="TableData">
    <td><?=_("��/��/��/�ֵ������ư�����")?></td>
    <td align="center"><input type="text" name="AREA" size="20" class="BigInput"></td>
    <td align="center"><input type="submit" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("���в�ѯ")?>" name="button"></td>
</tr>
</form>

<form name=form2 method="post" action="search.php" onsubmit="return CheckForm2();">
<tr class="TableData">
    <td><?=_("�������������")?></td>
    <td align="center"><input type="text" name="POST_NO" size="20" class="BigInput"></td>
    <td align="center"><input type="submit" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("���в�ѯ")?>" name="button"></td>
</tr>
</form>
</table>

</div>

</body>
</html>