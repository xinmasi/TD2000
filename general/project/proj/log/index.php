<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目审批记录");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"/>
	<span class="big3"> <?=_("项目审批记录")?></span>
	<td></tr>
</table>
<?
$query = "select APPROVE_LOG FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $APPROVE_LOG=$ROW["APPROVE_LOG"];
$APPROVE_LOG_ARR=explode("|*|",$APPROVE_LOG);

$COUNT=0;
foreach($APPROVE_LOG_ARR AS $V)
{
	if($V!="")
	{
		if($COUNT==0)
		{   
?>
<table align="center" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=3 bordercolor='#b8d1e2' width="90%" class="small" style="font-size:14px">
<?
    }
    $COUNT++;
    if($COUNT%2==0)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td><?=$V?></td>
    </tr>
<?
   }
}
if($COUNT>0)
  echo '</table>';
else
  Message("",_("暂无审批记录！"));
?>
</body>
</html>