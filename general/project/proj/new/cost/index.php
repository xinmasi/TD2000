<?
include_once("inc/auth.inc.php");

$query="select COST_MONEY from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $COST_MONEY=$ROW["COST_MONEY"];
	 $COST_MONEY=explode(",",$COST_MONEY);
}

$HTML_PAGE_TITLE = _("项目经费");
include_once("inc/header.inc.php");
?>


<script>

</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<form name="form1" method="post" action="submit.php">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/project/cost.gif" width="24" height="24" align="absmiddle"><span class="big3"> <?=_("项目经费设定")?></span><br>
    </td>
  </tr>
</table>

<table class="TableList" width="60%" align="center">
	<thead class="TableHeader">
      <td nowrap align="center"><?=_("费用类别")?></td>
      <td nowrap align="center"><?=_("金额(人民币)")?></td>
  </thead>
<?
$COUNT=0;
$query="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='PROJ_COST_TYPE' order by CODE_ORDER";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$CODE_NO=$ROW["CODE_NO"];
  $CODE_NAME=$ROW["CODE_NAME"];
  $CODE_EXT=unserialize($ROW["CODE_EXT"]);
	if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
	   $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
  
  if($COUNT%2==0)
     $TABLELINE="TableLine1";
  else
     $TABLELINE="TableLine2";
?>
  <tr class="<?=$TABLELINE?>">
      <td nowrap align="center"><?=$CODE_NAME?></td>
      <td nowrap align="center"><input type="text" name="<?="COST_".$CODE_NO?>" class="BigInput" style="text-align:right" value="<?=$COST_MONEY[$COUNT]?>" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value"></td>
  </tr>
<?
	$COUNT++;
}
?>
  <tfoot class="TableControl">
  	<td align="center" colspan=2>
  		<input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
  		<input type="submit" class="BigButton" value="<?=_("保存")?>">
  	</td>
  </tfoot>
</form>
</table>
</body>
</html>
