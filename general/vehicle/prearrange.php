<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("预约情况");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" HEIGHT="20" width="20" align="absmiddle"><span class="big3"> <?=_("车辆预约情况图表")?></span>
    </td>
  </tr>
</table>	
<table class=small>
	<tr>
		<td><?=_("图例说明：")?></td>
		<td width=20 bgColor="#378CD9"></td>
		<td width=40><?=_("空闲")?></td>
		<td width=20 bgColor="#ff33ff"></td>
		<td width=40><?=_("待批")?></td>
		<td width=20 bgColor="#00ff00"></td>
		<td width=40><?=_("已准")?></td>
		<td width=20 bgColor="#ff0000"></td>
		<td width=80><?=_("使用中")?></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="3" cellspacing="1" class="small" align="center">
<tr class=TableHeader>
	<td width="10%" align="center"><?=_("未来七天")?></td>
<?
$query = "SELECT * from VEHICLE  where V_STATUS ='0' and (DEPT_RANGE = 'ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_RANGE) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_RANGE) or DEPT_RANGE='' and USER_RANGE='') order by V_NUM";
$cursor= exequery(TD::conn(),$query);
$VEHICLE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $VEHICLE_COUNT++;
   $V_ID_ARRAY[]=$V_ID=$ROW["V_ID"];
   $V_MODEL=$ROW["V_MODEL"];  
   $V_NUM=$ROW["V_NUM"];
    
?>	
	<td width="20%" align="center" nowrap><?=$V_MODEL?>(<?=$V_NUM?>)</td>
<?
}

?>
</tr>

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

for($I=0;$I<7;$I++)
{
	$PRE_DATE=time()+$I*24*3600;
	$PRE_DATE=date("Y-m-d",$PRE_DATE);
	$PRE_DATE_START=$PRE_DATE." 00:00:00";
	$PRE_DATE_END=$PRE_DATE." 23:59:59";
  $PRE_DATE_DESC=substr($PRE_DATE,5);
  $WEEK_DAY=substr($PRE_DATE,5,10)."(".get_week($PRE_DATE).")";
?>
<tr class="TableData">
	<td width="10%" align="center" height="30" nowrap><?=$WEEK_DAY?></td>
<?
for($J=0;$J<$VEHICLE_COUNT;$J++)
{  
	 $query = "select * from VEHICLE_USAGE where V_ID='$V_ID_ARRAY[$J]' and VU_STATUS!=4 and  VU_STATUS!=3 and ((VU_START>'$PRE_DATE_START'and VU_START<'$PRE_DATE_END') or (VU_END>'$PRE_DATE_START' and VU_END<'$PRE_DATE_END') or (VU_START<'$PRE_DATE_START' and VU_END>'$PRE_DATE_END')) order by VU_START";
	 $cursor = exequery(TD::conn(),$query);
   $COUNT=0;
   $HTML_STR="<table border=0 width=100% height=100%><tr>";
   while($ROW=mysql_fetch_array($cursor))
   {
   	  $VU_STATUS=$ROW["VU_STATUS"];
   	  $DMER_STATUS=$ROW["DMER_STATUS"];
   	  $DEPT_MANAGER=$ROW["DEPT_MANAGER"];
   	
   	  if($DMER_STATUS!=1&&$DEPT_MANAGER!="")
   	    continue;
   	    
   	  $COUNT++;
   	     	  
   	  if($VU_STATUS==0&&$DMER_STATUS==1&&$DEPT_MANAGER!="" || $VU_STATUS==0&&$DEPT_MANAGER=="")
   	     $bgColor1="ff33ff"; 
   	  if($VU_STATUS==1)
   	     $bgColor1="00ff00";
   	  if($VU_STATUS==2)
   	     $bgColor1="ff0000";   	     
      $VU_START=$ROW["VU_START"];
      $VU_END=$ROW["VU_END"]; 
      $COUNT_DATE=round((strtotime($VU_END)-strtotime($VU_START))/(24*60*60)*1000)/1000;
      if($COUNT_DATE>0 && $COUNT_DATE < 1)
         $COUNT_DATE=1;
      else
         $COUNT_DATE=$COUNT_DATE+1;
         
      if($COUNT_DATE==1)
         $TIME_STR=substr($VU_START,11,5)."-<BR>".substr($VU_END,11,5);
      else
         if($PRE_DATE==substr($VU_END,0,10))
            $TIME_STR="08:00-<BR>".substr($VU_END,11,5);
         else if($PRE_DATE==substr($VU_START,0,10))
            $TIME_STR=substr($VU_START,11,5)."-<BR>17:00";
         else
            $TIME_STR="08:00-<BR>17:00";
            
      $TIME_STR1=substr($VU_START,5,11)._(" 至 ").substr($VU_END,5,11);
      $HTML_STR.="<td title='".$TIME_STR1."' bgColor=#".$bgColor1." style='text-align:center;border:1px solid white'>".$TIME_STR."</td>";
   }
   //echo $COUNT;
   //$HTML_STR.="</tr></table>";
    if($COUNT==0)
    {
      echo "<td width=\"20%\" align=\"center\" bgColor=\"#378CD9\">&nbsp;</td>";
    }
    else
    {
      //echo $COUNT1=100-$COUNT*20;
      echo "<td width=\"20%\" align=\"center\">".$HTML_STR."</td></tr></table>";
    }
}
?>
</tr>	
<?
}
?>
<tr class=TableControl>
	<td colspan=100 align=center>
		<input type="button" value="<?=_("关 闭")?>" class="BigButton" onClick="window.close()">
  </td>
</tr>
</table>

</body>
</HTML>
