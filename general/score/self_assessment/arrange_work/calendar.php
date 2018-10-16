<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("工作安排查询");
include_once("inc/header.inc.php");
?>



<script>
my_top=50;
my_left=50;

function my_note(CAL_ID)
{
  my_top+=25;
  my_left+=15;

  window.open("note.php?CAL_ID="+CAL_ID,"note_win"+CAL_ID,"height=170,width=180,status=0,toolbar=no,menubar=no,location=no,scrollbars=auto,top="+ my_top +",left="+ my_left +",resizable=no");
}

function My_Submit()
{
  document.form1.submit();
}

function set_year(op)
{
  if(op==-1 && document.form1.YEAR.selectedIndex==0)
     return;
  if(op==1 && document.form1.YEAR.selectedIndex==(document.form1.YEAR.options.length-1))
     return;
  document.form1.YEAR.selectedIndex=document.form1.YEAR.selectedIndex+op;

  My_Submit();
}

function set_mon(op)
{
  if(op==-1 && document.form1.MONTH.selectedIndex==0)
     return;
  if(op==1 && document.form1.MONTH.selectedIndex==(document.form1.MONTH.options.length-1))
     return;
  document.form1.MONTH.selectedIndex=document.form1.MONTH.selectedIndex+op;

  My_Submit();
}

function user_list(str)
{
  parent.user_list.location=str;
}

function task(str)
{
  my_left=document.body.scrollLeft+400;
  my_top=document.body.scrollTop+300;

  window.open(str,'','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');

}

function cur_month(str)
{
  location=str;
}

</script>


<body class="bodycolor">
	
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<form action="calendar.php?USER_ID=<?=$USER_ID?>"  method="post" name="form1">
  <tr>
    <td class="Big3"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"> <?=_("工作安排查询")?>

<?
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

$DATE=1;
$DAY=1;

if(!$YEAR)
   $YEAR = $CUR_YEAR;
if(!$MONTH)
   $MONTH = $CUR_MON;

if($YEAR>9999 or $YEAR<0){
  echo  "<script>
    alert('"._("年份超出范围！")."')
    history.go(-1)
    </script>
  ";
  exit;
}

if($MONTH>12 or $MONTH<0){
  echo  "<script>
    alert('"._("月份超出范围！")."')
    history.go(-1)
    </script>
  ";
  exit;
}

while (checkdate($MONTH,$DATE,$YEAR))
  $DATE++;
?>

<!-------------- 年 ------------>
        <input type="button" value="<?=_("〈")?>" class="BigButton" title="<?=_("上一年")?>" onclick="set_year(-1);"><select name="YEAR" class="BigSelect" onchange="My_Submit();">
<?
        for($I=2000;$I<=2015;$I++)
        {
?>
          <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select><input type="button" value="<?=_("〉")?>" class="BigButton" title="<?=_("下一年")?>" onclick="set_year(1);"> <b><?=_("年")?></b>

<!-------------- 月 ------------>
        <input type="button" value="<?=_("〈")?>" class="BigButton" title="<?=_("上一月")?>" onclick="set_mon(-1);"><select name="MONTH" class="BigSelect" onchange="My_Submit();">
<?
        for($I=1;$I<=12;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$MONTH) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select><input type="button" value="<?=_("〉")?>" class="BigButton" title="<?=_("下一月")?>" onclick="set_mon(1);"> <b><?=_("月")?></b>&nbsp;
        <input type="button" value="<?=_("本月")?>" class="BigButton" title="<?=_("本月")?>" onclick="javascript:cur_month('calendar.php?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MONTH?>&USER_ID=<?=$USER_ID?>');">
    </td>
  </tr>
  </form>
</table>

  <table border="0" cellspacing="1" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr align="center" class="TableHeader">
      <td width="30" bgcolor="#FFCCFF"><b><?=_("日")?></b></td>
      <td width="30"><b><?=_("一")?></b></td>
      <td width="30"><b><?=_("二")?></b></td>
      <td width="30"><b><?=_("三")?></b></td>
      <td width="30"><b><?=_("四")?></b></td>
      <td width="30"><b><?=_("五")?></b></td>
      <td width="30" bgcolor="#CCFFCC"><b><?=_("六")?></b></td>
      <td width="50"><b><?=_("周次")?></b></td>
    </tr>

<?
$WEEK_COUNT=0;

while ($DAY<$DATE)
{
  if($DAY == $CUR_DAY && $YEAR == $CUR_YEAR && $MONTH == $CUR_MON)
     $DAY_COLOR = "TableContent";
  else
     $DAY_COLOR = "TableData";

  $WEEK=date("w",mktime(0,0,0,$MONTH,$DAY,$YEAR));

  if ($WEEK==0 || $DAY==1)
  {
?>
   <tr>
<?
  }

  if($DAY==1)
  {
    for($I=0;$I<$WEEK;$I++)
    {
?>
     <td class="TableData" width="30">&nbsp;</td>
<?
    }
  }
?>
     <td class="<?=$DAY_COLOR?>" width="30" align="center">
       <b><a href="javascript:user_list('user_list.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$DAY?>&END_DAY=<?=$DAY?>&USER_ID=<?=$USER_ID?>');"><?=$DAY?></a></b>
     </td>
<?
  if ($WEEK==6)
  {

      $WEEK_COUNT++;

      $BEGIN_DAY=$DAY-6;
      if($BEGIN_DAY < 0)
         $BEGIN_DAY=1;
      $MSG = sprintf(_("第%s周"),$WEEK_COUNT);
?>
     <td class="TableData" width="50" align="center"><a href="javascript:user_list('user_list.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$BEGIN_DAY?>&END_DAY=<?=$DAY?>&USER_ID=<?=$USER_ID?>');"><?=$MSG?></a></td>
   </tr>
<?
  }

  $DAY++;
}//while

//------------- 补结尾空格 -------------
if($WEEK!=6)
{
  for($I=$WEEK;$I<6;$I++)
  {
?>
     <td class="TableData" width="30">&nbsp;</td>
<?
  }

  $WEEK_COUNT++;

  $DAY--;
  $BEGIN_DAY=$DAY-$WEEK;
  if($BEGIN_DAY<0)
     $BEGIN_DAY=1;
  
  $MSG2 = sprintf(_("第%s周"),$WEEK_COUNT);
?>
     <td class="TableData" width="50" align="center"><a href="javascript:user_list('user_list.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$BEGIN_DAY?>&END_DAY=<?=$DAY?>&USER_ID=<?=$USER_ID?>');"><?=$MSG2?></a></td>
   </tr>
<?
}
?>
</table>

</body>
</html>

