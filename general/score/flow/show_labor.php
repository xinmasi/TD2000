<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("绩效考核情况");
include_once("inc/header.inc.php");
?>


<script>
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("考核情况")?></span>
    </td>
    </tr>
</table>
  <table class="TableBlock" width="100%" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("考核人员")?></td>	
      <td nowrap align="center"><?=_("已考核人员")?></td>
      <td nowrap align="center"><?=_("未考核人员")?></td>
    </tr>
<?
$rank_arr=array();
$query2="SELECT *  from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor2= exequery(TD::conn(),$query2);
if($ROW2=mysql_fetch_array($cursor2))
{
	$RANKMAN=$ROW2["RANKMAN"];
	$PARTICIPANT=$ROW2["PARTICIPANT"];
}
$RANKMAN_ALL_ARR=explode(",",trim($RANKMAN,","));
$PARTICIPANT_ALL_ARR=explode(",",trim($PARTICIPANT,","));

$already_arr=array();
$query1="select RANKMAN,PARTICIPANT from SCORE_DATE where FLOW_ID='$FLOW_ID'";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{
	$RANKMAN=$ROW1["RANKMAN"];
	$PARTICIPANT=$ROW1["PARTICIPANT"];
	
	$already_arr[$RANKMAN][]=$PARTICIPANT;
}
$remain_arr=array();

foreach($already_arr as $key => $value)
{
	$self=array($key);
	$remain_arr[$key]=array_diff($PARTICIPANT_ALL_ARR,$value,$self);
}
foreach($RANKMAN_ALL_ARR as $RANKER)
{
	$RANKER_NAME=td_trim(GetUserNameById($RANKER));
	if(isset($already_arr[$RANKER]))
	{
	    $ALREADY_STR1=implode(",",$already_arr[$RANKER]);
	    $ALREADY_STR=td_trim(GetUserNameById($ALREADY_STR1));
	}
	if(isset($remain_arr[$RANKER]))
	{
	    $REMAIN_STR1=implode(",",$remain_arr[$RANKER]);
	    $REMAIN_STR=td_trim(GetUserNameById($REMAIN_STR1));
	}
	else
		$REMAIN_STR=td_trim(GetUserNameById(implode(",",$PARTICIPANT_ALL_ARR)));
?>

    <tr class="TableData">
      <td nowrap align="center"><?=$RANKER_NAME?></td>
      <td nowrap align="center"><?=$ALREADY_STR?></td>
      <td nowrap align="center"><?=$REMAIN_STR?></td>
    </tr>
<?
}
?>
  </table>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();">
</div>
</body>
</html>