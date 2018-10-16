<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d");

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;
$where_str=" and a.PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."' ";
$query3 = "select * from SCORE_DATE a left join SCORE_FLOW b ON a.FLOW_ID=b.FLOW_ID where 1=1";
$query3 .= $where_str;
$query3 .= " group by a.FLOW_ID ";
$cursor3= exequery(TD::conn(),$query3);
$STAFF_COUNT = mysql_num_rows($cursor3);

$limit_str=" limit $start,$PAGE_SIZE";

$HTML_PAGE_TITLE = _("绩效考核");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
</script>

<body class="bodycolor" leftmargin="0">
	
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
  	<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("绩效考核信息")?></span><br></td>
    <?
        if($STAFF_COUNT>0)
        {
    ?>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
    <?
        }
    ?>
	</tr>
</table>
<div style="margin-left:20px;">
<?
$query = "select * from SCORE_DATE a left join SCORE_FLOW b ON a.FLOW_ID=b.FLOW_ID where 1=1";
$query .=$where_str;
$query .=" group by a.FLOW_ID ";
$query .=$limit_str;
$cursor= exequery(TD::conn(),$query);
$FLOW_COUNT=0;
if($STAFF_COUNT>0)
{
while($ROW=mysql_fetch_array($cursor))
{
	$FLOW_COUNT++;
	$FLOW_ID=$ROW["FLOW_ID"];
  $FLOW_TITLE=$ROW["FLOW_TITLE"]; //每行数据的标题
  $BEGIN_DATE1=$ROW["BEGIN_DATE"];
  $END_DATE1=$ROW["END_DATE"];
  $SEND_TIME=$ROW["SEND_TIME"];
  $GROUP_ID=$ROW["GROUP_ID"];
	
	$query1="select ITEM_NAME from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
	$cursor1= exequery(TD::conn(),$query1);
	$rol_name=array();
	$rol_count=0;
	
	while($ROW1=mysql_fetch_array($cursor1))
	{
		$rol_name[]= $ROW1['ITEM_NAME']; //考核内容名称
	}
	
	//计算每项考核的平均值
	$sql="select SCORE from SCORE_DATE where PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."' and FLOW_ID='$FLOW_ID'";
	$cur=exequery(TD::conn(),$sql);
	$MY_SCORE=array();
	while($ROW=mysql_fetch_array($cur))
	{
		$score_str=$ROW['SCORE'];
		$score_str=substr($score_str,0,-1);
		
		$MY_SCORE[]=explode(",",$score_str);
	}
	if(sizeof($MY_SCORE)==0)
	{
		$result=0;
	}
	else
	{
		$sum_arr=array();
		$avg_arr=array();
		for($j=0;$j<sizeof($MY_SCORE);$j++)
		{	
			for($i=0;$i<sizeof($MY_SCORE[$j]);$i++)
			{
				$sum_arr[$i]=0;
				$avg_arr[$i]=0;
				if($MY_SCORE[$j][$i]=="")
					$MY_SCORE[$j][$i]=0;
			}
			for($j=0;$j<sizeof($MY_SCORE);$j++)
			{
				for($i=0;$i<sizeof($MY_SCORE[$j]);$i++)
				{
					$sum_arr[$i]+=$MY_SCORE[$j][$i];
				}
			}
			for($i=0;$i<sizeof($sum_arr);$i++)
			{
				$avg_arr[$i]=$sum_arr[$i]/sizeof($MY_SCORE);
			}
		}
	}
	
	$avg_sum=0;
	for($m=0;$m<sizeof($avg_arr);$m++)
	{
		$avg_sum+=$avg_arr[$m];
	}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
  	<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" align="absMiddle"><span class="big3"><?=$FLOW_TITLE?></span><br></td>
	</tr>
</table>


<table class="TableBlock" align="center" width="100%" cellspacing="0" cellpadding="0">
	<tr class="TableHeader" align="center">
<?
	for($j=0;$j<count($rol_name);$j++)
	{
?>
		<td nowrap ><b><?=$rol_name[$j]?></b></td>
<?
	}
?>
			<td nowrap ><b><?=_("总分")?></b></td>
	</tr>
	<tr class="TableLine1" align="center">
<?
	for($i=0;$i<count($avg_arr);$i++)
	{
?>
		<td nowrap ><b><?=$avg_arr[$i]?></b></td>
<?
	}
?>
		<td nowrap align="center"><?=$avg_sum?></td>
	</tr>
	</table>
	<br />
<?
}
}
 else 
{
    Message("",_("无绩效考核成绩！"));	
}
?>
</div>
</body>
</html>