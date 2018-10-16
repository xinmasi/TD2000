<?
include_once("inc/auth.inc.php");
mysql_select_db("BUS", TD::conn());

$HTML_PAGE_TITLE = _("邮政编码查询");
include_once("inc/header.inc.php");
?>



<script>
function CheckForm1()
{
   if(document.form1.AREA.value=="")
   {
     alert("<?=_("查询条件不能为空")?>");
     return false;
   }
   else
     return true;
}

function CheckForm2()
{
   if(document.form2.POST_NO.value=="")
   {
     alert("<?=_("查询条件不能为空")?>");
     return false;
   }
   else
     return true;
}
</script>


<body class="bodycolor" onload="document.form1.AREA.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("邮政编码查询")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

  <table class="TableBlock" width="450" align="center">
    <tr class="TableHeader">
      <td nowrap align="center" colspan="5"><b><?=_("省(直辖市/自治区)")?></b></td>
    </tr>

<?
 //============================ 显示省名 =======================================
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
      <td nowrap align="center" colspan="5"><b><?=_("国际")?> - <?=_("大洲")?></b></td>
    </tr>


<?
 //============================ 显示大洲名 =======================================
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
    <td colspan="3" align="center"><b><?=_("模糊查询")?></b></td>
</tr>

<form name=form1 method="post" action="search.php" onsubmit="return CheckForm1();">
<tr class="TableData">
    <td><?=_("市/区/县/街道的名称包含：")?></td>
    <td align="center"><input type="text" name="AREA" size="20" class="BigInput"></td>
    <td align="center"><input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button"></td>
</tr>
</form>

<form name=form2 method="post" action="search.php" onsubmit="return CheckForm2();">
<tr class="TableData">
    <td><?=_("邮政编码包含：")?></td>
    <td align="center"><input type="text" name="POST_NO" size="20" class="BigInput"></td>
    <td align="center"><input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button"></td>
</tr>
</form>
</table>

</div>

</body>
</html>