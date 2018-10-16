<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("进度图");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function my_note(DETAIL_ID)
{
  myleft=(screen.availWidth-250)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("note.php?DETAIL_ID="+DETAIL_ID,"note_win"+DETAIL_ID,"height=300,width=350,status=0,toolbar=no,menubar=no,location=no,scrollbars=auto,resizable=yes,top="+mytop+",left="+myleft);
}

function plan_detail(PLAN_ID)
{
  URL="plan_detail.php?PLAN_ID="+PLAN_ID;
  window.open(URL,"plan_detail","height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=60,left=110,resizable=yes");
}
window.setInterval("document.getElementById('showmsg').style.display='none'",3000);<?=_("　")?>
</script>

<?
$query = "SELECT NAME,BEGIN_DATE,END_DATE from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

   $NAME=$ROW['NAME'];

   $URL_BEGIN_DATE=$BEGIN_DATE1=$ROW['BEGIN_DATE'];
   $URL_END_DATE=$END_DATE1=$ROW['END_DATE'];
   $BEGIN_DATE=$ROW['BEGIN_DATE']." 00:00:01";
   $END_DATE=$ROW['END_DATE']." 23:59:59";

   $COUNT_DATE=round((strtotime($END_DATE)-strtotime($BEGIN_DATE))/86400);
   if($COUNT_DATE<=0)
      $COUNT_DATE=1;

   $STR=strtok($BEGIN_DATE1,"-");
   $YEAR=$STR;
   $STR=strtok("-");
   $MONTH=$STR;
   $STR=strtok(" ");
   $DAY=$STR;
   $TIME1=mktime(0,0,0,$MONTH,$DAY,$YEAR);

   if($END_DATE1=="0000-00-00")
   {
      $COUNT_DATE=30;
      $URL_END_DATE=$END_DATE1=date("Y-m-d",dateadd("d",30,$BEGIN_DATE1));
    }

   $COUNT_DATE1=$COUNT_DATE;

   $STR1=strtok($END_DATE1,"-");
   $YEAR1=$STR1;
   $STR1=strtok("-");
   $MONTH1=$STR1;
   $STR1=strtok(" ");
   $DAY1=$STR1;
   $TIME2=mktime(0,0,0,$MONTH1,$DAY1,$YEAR1);

   if(date("w", $TIME2)==0)
      $COUNT_DATE=$COUNT_DATE + date("w", $TIME1) + 1;
   else
      $COUNT_DATE=$COUNT_DATE + date("w", $TIME1);

   $COUNT_WEEK=ceil($COUNT_DATE/7);

   $COUNT_DATE=$COUNT_WEEK*7;

}

?>
<body class="bodycolor" onLoad="self.resizeTo(screen.availWidth,screen.availHeight);">
<div id="showmsg" style="display:none;z-index:1000;background:FF7F50;position:absolute;font-size:13px;padding:4px 10px 4px 10px;top:180px;left:135px;color:red;"><?=_("无计划任务")?></div>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("进度图")?> (<?=$NAME?> <?=format_date($BEGIN_DATE1)?> - <? if($END_DATE1!="0000-00-00") echo format_date($END_DATE1);?>)</span><br>
  </td>
 </tr>
</table><br>

<table align="center" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=3 bordercolor='#000000' width="100%" class="small">
 <tr class="TableData" valign="top">
 	<td rowspan="2" nowrap valign="center" align="center" width="115"> <?=_("姓名")?>
 		<a href="#" onClick="javascript:expand_sub_plan();return false;" style="cursor:hand"><img src="<?=MYOA_STATIC_SERVER?>/static/images/plan_exp.gif" id="expand_img" border=0 align="absmiddle" alt="<?=_("全部展开/全部折叠")?>"></a></a></td>
<?
for($I=0;$I < $COUNT_WEEK;$I++)
{
	 if($I==0)
	    $Y_M_D1=$Y_M_D=date("Y-m-d",dateadd("d",-date("w", $TIME1),$BEGIN_DATE1));
	 else
	    $Y_M_D=date("Y-m-d",dateadd("d",7,$Y_M_D));
?>
  <td colspan="<?=7?>" nowrap><?=format_date($Y_M_D)?></td>
<?
}
?>
 </tr>
 <tr class="TableData" valign="top">
<?
for($I=0;$I < $COUNT_DATE;$I++)
{
  if($I%7==0)
     $WEEK_STR=_("日");
  if($I%7==1)
     $WEEK_STR=_("一");
  if($I%7==2)
     $WEEK_STR=_("二");
  if($I%7==3)
     $WEEK_STR=_("三");
  if($I%7==4)
     $WEEK_STR=_("四");
  if($I%7==5)
     $WEEK_STR=_("五");
  if($I%7==6)
     $WEEK_STR=_("六");

  $M_D_STR=date("m-d",strtotime($Y_M_D1)+$I*86400);
  $TIME_STR=date("m-d",time());
?>
   <td align="center" style="cursor:hand" title="<?=$M_D_STR?>" bgcolor="<? if($M_D_STR==$TIME_STR) echo '#cccccc';?>"><?=$WEEK_STR?></td>
<?
}
?>
 </tr>
<?
$query = "SELECT PARTICIPATOR,MANAGER,TO_ID,OPINION_LEADER,TO_PERSON_ID,CREATOR from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PARTICIPATOR=$ROW["PARTICIPATOR"];
   $MANAGER=$ROW["MANAGER"];
   $TO_ID=$ROW["TO_ID"];
   $CREATOR=$ROW["CREATOR"];
   $TO_PERSON_ID=$ROW["TO_PERSON_ID"];
   $OPINION_LEADER=$ROW["OPINION_LEADER"];

   $FLAG1="";
   if(find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"])) //负责人,批注领导和创建人有批注权
      $FLAG1 = "1";

   $MANAGER_ARRAY=explode(",",$MANAGER);
   for($I=0;$I< count($MANAGER_ARRAY);$I++)
   {
      if(!find_id(str_replace(" ,","",$PARTICIPATOR),$MANAGER_ARRAY[$I]) && $MANAGER_ARRAY[$I]!="")
         $PARTICIPATOR.= $MANAGER_ARRAY[$I].",";
   }
}

$TOTAL_PERCENT=0;
$PEOPLE=0;
$TOK=strtok($PARTICIPATOR,",");
$PARTICIPATOR_ARRAY=explode(",",$PARTICIPATOR);
for($I=0;$I< count($PARTICIPATOR_ARRAY);$I++)
{
	 if($PARTICIPATOR_ARRAY[$I]!="")
	    $PERSON_COUNT++;
}

if($PERSON_COUNT==1)
   $ARRAY_FLAG=0;
else
   $ARRAY_FLAG=1;

while($TOK!="")
{
  $query1="select DEPT_ID,USER_NAME,USER_ID from USER where USER_ID='$TOK'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
  {
     $DEPT_ID=$ROW["DEPT_ID"];
     $USER_ID=$ROW["USER_ID"];

     $USER_SUB_ITEMS = 0;
     $query = "SELECT count(*) from WORK_PERSON where PLAN_ID='$PLAN_ID' and PUSER_ID='$USER_ID'";
     $cursor= exequery(TD::conn(),$query);
     if($ROW1=mysql_fetch_array($cursor))
        $USER_SUB_ITEMS = $ROW1["0"];

     $USER_NAME=$ROW["USER_NAME"];
     $DEPT_NAME=dept_long_name($DEPT_ID);

     $query = "SELECT MAX(PERCENT) as PERCENT from WORK_DETAIL where PLAN_ID='$PLAN_ID' and WRITER='$TOK'";
     $cursor=exequery(TD::conn(),$query);
	   if($ROW1=mysql_fetch_array($cursor))
     {
        $PERCENT=$ROW1["PERCENT"];
        $TOTAL_PERCENT=$TOTAL_PERCENT + $PERCENT;
     }

     	$PEOPLE++;
?>
 <tr class="TableData" valign="top">
   <td align="center" nowrap>
   	<div style="float:left;width=70%;padding-top:3px;" title="<?=$DEPT_NAME?>"><?=$USER_NAME?></div>
   	<div style="float:right;width=30%;display:inline;">
   		<img style="cursor:hand" title="<?=_("指派计划任务")?>" onClick="window.open('plan_resource.php?USER_ID=<?=$USER_ID?>&PLAN_ID=<?=$PLAN_ID?>&USER_NAME=<?=urlencode($USER_NAME)?>&NAME=<?=urlencode($NAME)?>&URL_BEGIN_DATE=<?=$URL_BEGIN_DATE?>&URL_END_DATE=<?=$URL_END_DATE?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');" src="<?=MYOA_STATIC_SERVER?>/static/images/newwork.gif">
   		<a style="cursor:hand" href="#" onClick="javascript:my_view('<?=$PEOPLE?>','<?=$ARRAY_FLAG?>','<?=$USER_SUB_ITEMS?>','<?=$USER_NAME?>');return false;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/updown2.gif" name="arrow_img" id="sub_plan_<?=$PEOPLE?>" border=0 align="absmiddle" alt="<?=_("显示/隐藏计划任务")?>"></a></div>
   </td>
<?
if(date("w", $TIME1)==0)
{
?>
   <td align="left" colspan="<?=$COUNT_DATE-(7-date("w", $TIME2))+1?>">
<?
$query = "SELECT DETAIL_ID,WRITE_TIME,PROGRESS,PERCENT,WRITER from WORK_DETAIL where TYPE_FLAG='0'and PLAN_ID='$PLAN_ID' and WRITER='$TOK' order by WRITE_TIME asc";
$cursor=exequery(TD::conn(),$query);
$NUM_ROWS=mysql_num_rows($cursor);
if($NUM_ROWS>0)
{
?>
      <table align="center" style='border-collapse:collapse' border=1 cellspacing=0 bordercolor='#000000' width="100%" class="small">
        <tr height="10">
<?
}

$DETAIL_COUNT=0;
$PERCENT1=0;
$DAYS1=0;
$DAYS2=0;
while($ROW=mysql_fetch_array($cursor))
{
  $DETAIL_COUNT++;
  $DETAIL_ID=$ROW["DETAIL_ID"];
  $WRITE_TIME=$ROW["WRITE_TIME"];
	$PROGRESS=$ROW["PROGRESS"];
  $PERCENT =$ROW["PERCENT"];
	$WRITER=$ROW["WRITER"];

  $STR2=substr($WRITE_TIME,0,4);
  $YEAR2=$STR2;
  $WRITE_TIME;
  $STR2=substr($WRITE_TIME,5,2);
  $MONTH2=$STR2;
  $STR2=substr($WRITE_TIME,8,2);
  $DAY2=$STR2;
  $TIME3=mktime(0,0,0,$MONTH2,$DAY2,$YEAR2);

  $DAYS=round(($TIME3-$TIME1)/3600/24);

	$PERCENT1=$ROW["PERCENT"];

  $DAYS2=$DAYS - $DAYS1;
  if($DAYS2==0)
     $DAYS2=1;
  $DAYS1=$DAYS1 + $DAYS2;
  
  $query1 = "SELECT MAX(PERCENT) AS PERCENT_CM from WORK_DETAIL where TYPE_FLAG='0'and PLAN_ID='$PLAN_ID' and WRITER='$TOK' and PERCENT < '$PERCENT'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $LAST_PERCENT=$ROW1["PERCENT_CM"];  
  
?>
          <td width="<? echo ceil($DAYS2/$COUNT_DATE1*100)?>%" background="<?=MYOA_STATIC_SERVER?>/static/images/finish.gif" align="center" title="<?=_("日期：")?><?=$MONTH2._("月").$DAY2._("日")?><?=_("，")?><?=_("合计：").$PERCENT1."%"?>" style="cursor:hand" onClick="my_note('<?=$DETAIL_ID?>')"><?=$PERCENT-$LAST_PERCENT."%";?></td>
<?
}

if($NUM_ROWS>0)
{
	 if(round((1-$DAYS1/$COUNT_DATE1)*100)!=0)
	 {
	 	  if($DAYS1!=$COUNT_DATE1)
	 	  {
	 	     if($PERCENT==0)
	 	        $ESTIMATE=$COUNT_DATE1;
	 	     else
	 	        $ESTIMATE=ceil($DAYS1/$PERCENT*(100-$PERCENT));
	 	     $BAR_LEN_ESTIMATE= round($ESTIMATE/$COUNT_DATE1*100);
	 	  }
?>
<style>
	#bar_<?=$TOK?>{float:left;margin:0px 0px 0px 0px;padding:0px;background:#cccccc;width:<?=$BAR_LEN_ESTIMATE?>%;height:5px;}<?=_("，")?>
</style>
         <td width="<? echo round((1-$DAYS1/$COUNT_DATE1)*100);?>%" align="center"><div id="bar_<?=$TOK?>" title="<?=sprintf(_("预计%s天完成，还剩下"),$ESTIMATE);?><? if((100-$PERCENT)!=0) echo (100-$PERCENT)."%";?>" style="cursor:hand"></div></td>
<?
   }
?>
        </tr>
      </table>
<?
}
?>
   </td>
<?
if(date("w", $TIME2)!=6)
{
?>
   <td colspan="<?=7?>" bgcolor="#cccccc" align="left" valign="middle" nowrap><?if($PERCENT==100) echo _("完成");else echo "&nbsp;";?></td>
<?
}
}

if(date("w", $TIME1)!=0)
{
	 $COLSPAN_DAY = $COUNT_DATE-(7-date("w", $TIME2))-date("w", $TIME1)+1;
?>
   <td align="center" colspan="<?=date("w", $TIME1)?>" bgcolor="#cccccc">&nbsp;</td>
   <td align="left" colspan="<?=$COLSPAN_DAY?>">
<?
$query = "SELECT DETAIL_ID,WRITE_TIME,PROGRESS,PERCENT,WRITER from WORK_DETAIL where TYPE_FLAG='0'and PLAN_ID='$PLAN_ID' and WRITER='$TOK' order by WRITE_TIME asc";
$cursor=exequery(TD::conn(),$query);
$NUM_ROWS=mysql_num_rows($cursor);
if($NUM_ROWS>0)
{
?>
      <table align="center" style='border-collapse:collapse' border=1 cellspacing=0 bordercolor='#000000' width="100%" class="small">
        <tr height="10">
<?
}

$DETAIL_COUNT=0;
$DAYS1=0;
$DAYS2=0;
$PERCENT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $DETAIL_COUNT++;
  $DETAIL_ID=$ROW["DETAIL_ID"];
  $WRITE_TIME=$ROW["WRITE_TIME"];
	$PROGRESS=$ROW["PROGRESS"];
  $PERCENT =$ROW["PERCENT"];
	$WRITER=$ROW["WRITER"];
	
  $query1 = "SELECT MAX(PERCENT) AS PERCENT_CM from WORK_DETAIL where TYPE_FLAG='0'and PLAN_ID='$PLAN_ID' and WRITER='$TOK' and PERCENT < '$PERCENT'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $LAST_PERCENT=$ROW1["PERCENT_CM"];

  $STR2=substr($WRITE_TIME,0,4);
  $YEAR2=$STR2;
  $STR2=substr($WRITE_TIME,5,2);
  $MONTH2=$STR2;
  $STR2=substr($WRITE_TIME,8,2);
  $DAY2=$STR2;
  $TIME3=mktime(0,0,0,$MONTH2,$DAY2,$YEAR2);

  $DAYS=round(($TIME3-$TIME1)/3600/24);

  $DAYS2=$DAYS - $DAYS1;
  if($DAYS2==0)
     $DAYS2=1;
  $DAYS1=$DAYS1 + $DAYS2;
	
	$PERCENT3=$PERCENT-$LAST_PERCENT;
?>
          <td width="<? echo round($PERCENT3)?>%" background="<?=MYOA_STATIC_SERVER?>/static/images/finish.gif" align="center" title="<?=_("日期：")?><?=$MONTH2._("月").$DAY2._("日")?> <?=_("合计：")?><?=$PERCENT?>%" style="cursor:hand" onClick="my_note('<?=$DETAIL_ID?>')"><?if($PERCENT!=0) echo $PERCENT-$LAST_PERCENT."%";?></td>
<?

} //while

if($NUM_ROWS>0)
{
	 if(round((1-$DAYS1/$COUNT_DATE1)*100)!=0)
	 {
	 	  if($DAYS1!=$COUNT_DATE1)
	 	  {
	 	     if($PERCENT==0)
	 	        $ESTIMATE=$COUNT_DATE1;
	 	     else
	 	        $ESTIMATE=ceil($DAYS1/$PERCENT*(100-$PERCENT));
	 	     $BAR_LEN_ESTIMATE= round($ESTIMATE/$COUNT_DATE1*100);
	 	  }
?>
<style>
	#bar_<?=$TOK?>{float:left;margin:0px 0px 0px 0px;padding:0px;background:#cccccc;width:<?=$BAR_LEN_ESTIMATE?>%;height:5px;}<?=_("，")?>
</style>
   <td width="<? echo round((1-$DAYS1/$COUNT_DATE1)*100);?>%" align="center"><div id="bar_<?=$TOK?>" title="<?=sprintf(_("预计%s天完成，还剩下"),$ESTIMATE);?><? if((100-$PERCENT)!=0) echo (100-$PERCENT)."%";?>" style="cursor:hand"></div></td>
<?
   }
?>
        </tr>
      </table>
<?
}
?>
   </td>
<?
if(date("w", $TIME2)!=6)
{
?>
   <td colspan="<?=7?>" bgcolor="#cccccc" align="left" valign="middle" nowrap><?if($PERCENT==100) echo _("完成");else echo "&nbsp;";?></td>
<?
}
}
?>
 </tr>
<tr id="person_content" style="display:none;">
	<td colspan="<?=$COUNT_DATE+1?>">
<?
$query = "SELECT AUTO_PERSON,PBEGEI_DATE,PEND_DATE,PPLAN_CONTENT,PUSE_RESOURCE,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_PERSON where PLAN_ID='$PLAN_ID' and PUSER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $COUNT++;

  $AUTO_PERSON=$ROW["AUTO_PERSON"];
  $PBEGEI_DATE=$ROW["PBEGEI_DATE"];
  $PEND_DATE=$ROW["PEND_DATE"];
  $ATTACHMENT_ID2=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME2=$ROW["ATTACHMENT_NAME"]; 	 	   
  
  $PPLAN_CONTENT=str_replace("\n","<br>",$ROW["PPLAN_CONTENT"]);
  $PUSE_RESOURCE=str_replace("\n","<br>",$ROW["PUSE_RESOURCE"]);

  if($PEND_DATE=="0000-00-00")
     $PEND_DATE="";

  if($COUNT==1)
	{
?>
<table id="<?=$PEOPLE?>" border="0" cellspacing="0" width="100%" class="small" style="border:1px solid #B1CCF2;" bgcolor="#000000" cellpadding="3" align="center">
   <tr class="TableLine2" style="background-color:#B1CCF2;color:#000000;">
     <td nowrap align="center" width="100" style="border:1px solid #B1CCF2;"><?=_("开始时间")?></td>
     <td nowrap align="center" width="100" style="border:1px solid #B1CCF2;"><?=_("结束时间")?></td>
     <td align="center" style="border:1px solid #B1CCF2;"><?=_("计划任务")?></td>
     <td nowrap align="center" style="border:1px solid #B1CCF2;"><?=_("附件")?></td>     
     <td align="center" style="border:1px solid #B1CCF2;"><?=_("相关资源")?></td>
   </tr>
<?
  }

  if($COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";

?>
  <tr class="<?=$TableLine?>">
     <td nowrap align="center" width="100"><?=$PBEGEI_DATE?></td>
  	 <td nowrap align="center" width="100"><?=$PEND_DATE?></td>
     <td align="left"><?=$PPLAN_CONTENT?></td>
     <td align="left"><?=attach_link($ATTACHMENT_ID2,$ATTACHMENT_NAME2,0,1,1)?></td>
     <td align="left"><?=$PUSE_RESOURCE?></td>
</tr>
<?
} //while

if($COUNT!=0)
{
?>
</table>
<?
}
}
  $TOK=strtok(",");
}

?>
</table>
<br>
<?
if($TOTAL_PERCENT > 0)
{
?>
<style>
	#bar1{float:left;margin:0px 0px 0px 0px;padding:0px;background:#cccccc;width:<? echo round((1-$TOTAL_PERCENT/($PEOPLE*100))*100);?>%;height:5px;}<?=_("，")?>
</style>
<table align="center" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=3 bordercolor='#000000' width="100%" class="small">
 <tr class="TableData" valign="top">
 	 <td width="60" align="center"><?=_("总进度")?></td>
   <td align="center">
   	  <table align="center" style='border-collapse:collapse' border=1 cellspacing=0 bordercolor='#000000' width="100%" class="small">
        <tr height="10">
        	<td width="<? echo round($TOTAL_PERCENT/($PEOPLE*100)*100)?>%" background="<?=MYOA_STATIC_SERVER?>/static/images/finish.gif" title="<?=_("完成")?><? echo round($TOTAL_PERCENT/($PEOPLE*100)*100)?>%" align="center" style="cursor:hand"><?=round($TOTAL_PERCENT/($PEOPLE*100)*100)?>%</td>
          <td width="<? echo round((1-$TOTAL_PERCENT/($PEOPLE*100))*100);?>%" title="<?=_("还剩下")?><? echo round((1-$TOTAL_PERCENT/($PEOPLE*100))*100);?>%" align="center"><div id="bar1" style="cursor:hand"></div></td>
        </tr>
      </table>
   </td>
 </tr>
</table><br>
<?
}

$query = "SELECT DETAIL_ID,WRITE_TIME,PROGRESS,PERCENT,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_DETAIL where TYPE_FLAG='1'and PLAN_ID='$PLAN_ID' order by WRITE_TIME desc";
$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $DETAIL_COUNT++;
  $DETAIL_ID=$ROW["DETAIL_ID"];
	$WRITE_TIME=$ROW["WRITE_TIME"];
	$PROGRESS=$ROW["PROGRESS"];
	$PERCENT =$ROW["PERCENT"];
	$WRITER=$ROW["WRITER"];
  $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

  $PROGRESS=str_replace("\n","<br>",$PROGRESS);
  $query1 = "SELECT USER_NAME from USER where USER_ID='$WRITER'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $USER_NAME=$ROW1["USER_NAME"];

  if($DETAIL_COUNT==1)
	{
?>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center" style="margin-top:10px;">
   <tr class="TableHeader">
   	 <td nowrap align="center" colspan="4"><?=_("领导批注信息")?></td>
   </tr>
   <tr class="TableData">
     <td nowrap align="center" width="15%"><?=_("批注领导")?></td>
     <td nowrap align="center"><?=_("批注内容")?></td>
     <td nowrap align="center"><?=_("附件")?></td>
     <td nowrap align="center" width="20%"><?=_("批注时间")?></td>
   </tr>
<?
  }

  if($DETAIL_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";

?>
  <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$USER_NAME?></td>
  	 <td style="word-break:break-all;" align="left"><?=$PROGRESS?></td>
     <td nowrap align="left"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>
     <td nowrap align="center"><?=$WRITE_TIME?></td>
  </tr>

<?
} //while

if($DETAIL_COUNT==0)
{
   Message("",_("无批注"));
}
else
   echo "</table>";

echo "<br><center>";
if($HINT_FLAG!=1&&$STATUS!=2&&$STATUS!=3) //负责人和参与人可以工作日志（没暂停、结束）
   echo "<input type=\"button\" value=\""._("撰写进度日志")."\" class=\"BigButton\"  onclick=\"window.open('add_diary.php?PLAN_ID=$PLAN_ID&BACK_FLAG=1','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');\">&nbsp;";
if($FLAG1 == "1")
   echo "<input type=\"button\" value=\""._("领导批注")."\" class=\"BigButton\" onclick=\"window.open('add_opinion.php?PLAN_ID=$PLAN_ID','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');\">&nbsp;";
echo "<input type=\"button\" value=\""._("工作计划详情")."\" class=\"BigButton\"  onclick=\"plan_detail('$PLAN_ID')\">&nbsp;";
echo "<input type=\"button\" value=\""._("刷新")."\" class=\"BigButton\"  onclick=\"location.reload();\">&nbsp;";
echo "<input type=\"button\" value=\""._("关闭")."\" class=\"BigButton\"  onclick=\"window.close();\"></center>";
echo "</center>";
?>
</body>
</html>

<?
function gettime($d)
{
   if(is_numeric($d))
      return $d;
   else
   {
      if(!is_string($d))
         return 0;
      if(strpos($d, ":") > 0)
      {
         $buf = explode(" ",$d);
         $year = explode("-",$buf[0]);
         $hour = explode(":",$buf[1]);
         if(strpos($buf[2], "pm") >= 0)
            $hour[0] += 12;

         return mktime($hour[0],$hour[1],$hour[2],$year[1],$year[2],$year[0]);
      }
      else
      {
         $year = explode("-",$d);
         return mktime(0,0,0,$year[1],$year[2],$year[0]);
      }
   }
}


function dateadd($interval, $number, $date)
{
   $date = gettime($date);
   $date_time_array = getdate($date);
   $hours = $date_time_array["hours"];
   $minutes = $date_time_array["minutes"];
   $seconds = $date_time_array["seconds"];
   $month = $date_time_array["mon"];
   $day = $date_time_array["mday"];
   $year = $date_time_array["year"];
   switch ($interval)
   {
      case "yyyy": $year +=$number; break;
      case "q": $month +=($number*3); break;
      case "m": $month +=$number; break;
      case "y":
      case "d":
      case "w": $day+=$number; break;
      case "ww": $day+=($number*7); break;
      case "h": $hours+=$number; break;
      case "n": $minutes+=$number; break;
      case "s": $seconds+=$number; break;
   }
   $timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year);
   return $timestamp;
}
?>

<script>
var view_flag = new Array();
for(var k = 1;k <= <?=$PEOPLE?>; k++)
    view_flag[k]=1;

function my_view(I,array_flag,user_sub_items,uname)
{
	if(user_sub_items!=0)
	{
   	if(array_flag==0)
   	{
   		 obj_img=arrow_img;
   		 obj_content=person_content;
   	}
   	else
   	{
   		 obj_img=arrow_img[I-1];
   		 obj_content=person_content[I-1];
   	}

   	if(view_flag[I]==1)
   	{
        obj_img.src="<?=MYOA_STATIC_SERVER?>/static/images/updown1.gif";
        obj_content.style.display=""
        document.getElementById("showmsg").style.top = document.body.scrollTop + event.clientY-10;
   	}
   	else
   	{
   	   obj_img.src="<?=MYOA_STATIC_SERVER?>/static/images/updown2.gif";
        obj_content.style.display="none"
     }

     view_flag[I]=1-view_flag[I];
	}else{
		document.getElementById("showmsg").style.display ="";
		document.getElementById("showmsg").style.top = document.body.scrollTop + event.clientY-10;
		document.getElementById("showmsg").innerHTML= uname+"<?=_("无计划任务")?>";
	}
}

function expand_sub_plan()
{
  var expand_img=document.getElementById('expand_img');
  var show_count = 0;
  if(expand_img.src.substr(expand_img.src.lastIndexOf("/")+1)=="plan_exp.gif")
  {
  	 for(var i=0;i < <?=$PEOPLE?>;i++)
  	 {
        if (!(!document.getElementById(i+1) && typeof(document.getElementById(i+1))!="undefined" && document.getElementById(i+1)!=0))
        {
  	 	     show_count++;
  	 	     document.getElementById("showmsg").style.top = document.body.scrollTop + event.clientY-10;
  	 	     person_content[i].style.display='';
  	 	     var j = i+1;
  	 	     document.getElementById('sub_plan_'+j).src="<?=MYOA_STATIC_SERVER?>/static/images/updown1.gif";
  	 	     expand_img.src="<?=MYOA_STATIC_SERVER?>/static/images/plan_cls.gif";
  	 	     view_flag[i+1]=0;
  	 	  }
  	 }
  }else{
  	 for(var i=0;i < <?=$PEOPLE?>;i++)
  	 {
  	    show_count++
  	    person_content[i].style.display='none';
  	 	  var j = i+1;
  	 	  document.getElementById('sub_plan_'+j).src="<?=MYOA_STATIC_SERVER?>/static/images/updown2.gif";
  	    expand_img.src="<?=MYOA_STATIC_SERVER?>/static/images/plan_exp.gif";
  	    view_flag[i+1]=1;
  	 }
  }

  if(expand_img.src.substr(expand_img.src.lastIndexOf("/")+1)=="plan_exp.gif" && show_count==0)
  {
     document.getElementById("showmsg").style.display ="";
     document.getElementById("showmsg").style.top = document.body.scrollTop + event.clientY-10;
     document.getElementById("showmsg").innerHTML="<?=_("所有人都没有设置计划任务")?>";
  }
}
</script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>