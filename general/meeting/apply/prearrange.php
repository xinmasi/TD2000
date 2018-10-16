<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("预约情况");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" HEIGHT="20" width="20" align="absmiddle"><span class="big3"> <?=_("会议预约情况图表")?></span>
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
		<td width=80><?=_("进行中")?></td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#000000" class="small" align="center">
<tr class=TableHeader>
	<td width="10%" align="center" nowrap><?=_("未来七天")?></td>
<?
$query = "SELECT * from MEETING_ROOM where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR) or TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or (TO_ID='' and SECRET_TO_ID='')";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ROOM_COUNT++;
   $MR_ID_ARRAY[]=$MR_ID=$ROW["MR_ID"];
   $MR_NAME=$ROW["MR_NAME"];   
?>	
	<td align="center" nowrap><?=$MR_NAME?></td>
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
  $WEEK_DAY=substr($PRE_DATE,5,10)._("(").get_week($PRE_DATE).")";
?>
<tr class="TableData">
	<td width="10%" align="center" height="30" nowrap><?=$WEEK_DAY?></td>
<?
for($J=0;$J<$ROOM_COUNT;$J++)
{  
	 $query = "select * from MEETING where M_ROOM='$MR_ID_ARRAY[$J]' and M_STATUS!=4 and  M_STATUS!=3 and ((M_START>'$PRE_DATE_START'and M_START<'$PRE_DATE_END') or (M_END>'$PRE_DATE_START' and M_END<'$PRE_DATE_END') or (M_START <'$PRE_DATE_START' and M_END>'$PRE_DATE_END')) order by M_START";
   $cursor = exequery(TD::conn(),$query);
   $COUNT=0;
   $HTML_STR="<table border=0 width=100% height=100%><tr>";
   while($ROW=mysql_fetch_array($cursor))
   {
   	  $COUNT++;
   	  
   	  $M_STATUS=$ROW["M_STATUS"];
   	  $M_PROPOSER=$ROW["M_PROPOSER"];   	  
   	  $query1 = "select USER_NAME from USER where USER_ID='$M_PROPOSER'";
      $cursor1 = exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $USER_NAME=$ROW1["USER_NAME"];
      
   	  if($M_STATUS==0)
   	     $bgColor1="ff33ff";
   	  if($M_STATUS==1)
   	     $bgColor1="00ff00";
   	  if($M_STATUS==2)
   	     $bgColor1="ff0000";   	     
   
      $M_START=$ROW["M_START"];
      $M_END=$ROW["M_END"]; 
      
      $COUNT_DATE=round((strtotime($M_END)-strtotime($M_START))/(24*60*60)*1000)/1000;
      if($COUNT_DATE>0 && $COUNT_DATE < 1)
         $COUNT_DATE=1;
      else
         $COUNT_DATE=$COUNT_DATE+1;
         
      if($COUNT_DATE==1)
         $TIME_STR=substr($M_START,11,5)."-<BR>".substr($M_END,11,5);
      
	  else
         if($PRE_DATE==substr($M_END,0,10)) //找到最后一天
            $TIME_STR="00:00-<BR>".substr($M_END,11,5);
         else if($PRE_DATE==substr($M_START,0,10)) //找到开始一天
            $TIME_STR=substr($M_START,11,5)."-<BR>23:59";
         else
            $TIME_STR="00:00-<BR>23:59";
            
      $TIME_STR1=substr($M_START,5,11)._(" 至 ").substr($M_END,5,11);
      $HTML_STR.="<td title='".$TIME_STR1._("  申请人：").$USER_NAME."' bgColor=#".$bgColor1." width='40'><font size=2>".$TIME_STR."</font></td>";
   }
   //$HTML_STR.="</tr></table>";
   if($COUNT==0)
      echo "<td align=\"center\" bgColor=\"#378CD9\">&nbsp;</td>";
   else
   {
   	  //echo $COUNT1=100-$COUNT*20;
      echo "<td align=\"center\">".$HTML_STR."<td width='".$COUNT1."%'  bgColor=\"#FFFFFF\">&nbsp;</td></tr></table></td>";
   }
}
?>
</tr>	
<?
}
?>
<tr class=TableControl>
	<td colspan=100 align=center>
		<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
  </td>
</tr>
</table>

</body>
</HTML>
