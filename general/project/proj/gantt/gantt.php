<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//include ("inc/jpgraph/jpgraph.php");
//include ("inc/jpgraph/jpgraph_gantt.php");

//��Ŀ��Ϣ
$query = "SELECT * from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_NAME=$ROW["PROJ_NAME"];
    $PROJ_ACT_END_TIME=$ROW["PROJ_ACT_END_TIME"];
   if(strtotime($PROJ_ACT_END_TIME)>0)
      $end_date_act = $PROJ_ACT_END_TIME;
}
//��������Ŀ�ʼ�ͽ���ʱ��
$query = "SELECT min(TASK_START_TIME) as start_date,max(TASK_END_TIME) as end_date from PROJ_TASK where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $start_date=$ROW["start_date"];
    $end_date=$ROW["end_date"];
}
if(strtotime($end_date_act)<strtotime($end_date) && strtotime($end_date_act)>0)
{
   $end_date = $end_date_act;
}

/*
//-- ����ͼ --
$graph = new GanttGraph();
$graph->title->Set(td_iconv($PROJ_NAME, MYOA_CHARSET, MYOA_OS_CHARSET));
$graph->title->SetFont(FF_SIMSUN,FS_BOLD,13);
$graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$graph->scale->SetDateLocale('chs');
$graph->scale->week->SetStyle(WEEKSTYLE_TDOA);

if ($start_date && $end_date)
{
	$day_diff = floor((strtotime($end_date) - strtotime($start_date))/86400);
	
  if($day_diff > 240){
     $graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
  } 
  else if ($day_diff > 90)
  {
     $graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HWEEK );
     $graph->scale->week->SetStyle(WEEKSTYLE_WNBR);
  }
  //������ĩ
  if(date("w",strtotime($end_date))==0 || date("w",strtotime($end_date))>4)
     $end_date=date("Y-m-d",strtotime("+1 week",strtotime($end_date)));
   if(strtotime($start_date)<strtotime($end_date))
      $graph->SetDateRange( $start_date, $end_date );
}


$graph->scale->actinfo->vgrid->SetColor('gray');
$graph->scale->actinfo->SetColor('darkgray');

$graph->scale->actinfo->SetColTitles(array(_("��������"), _("������") , _("����"), _("��ʼ����"), _("��������")),array(120,80,30,60,60));
$graph->scale->actinfo->SetFont(FF_SIMSUN,FS_NORMAL,9);

//һ����Ŀ����������
$query = "SELECT * from PROJ_TASK where PROJ_ID='$PROJ_ID' order by TASK_NO";
$cursor= exequery(TD::conn(),$query);
$TASK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	$TASK_COUNT++;
  $TASK_ID=$ROW["TASK_ID"];
  $TASK_NO=$ROW["TASK_NO"];
  $TASK_NAME=$ROW["TASK_NAME"];
  $TASK_USER=$ROW["TASK_USER"];
  $DETAIL_DESC=$ROW["DETAIL_DESC"];
  $TASK_START_TIME =$ROW["TASK_START_TIME"];
  $TASK_END_TIME =$ROW["TASK_END_TIME"];
  $TASK_ACT_END_TIME = $ROW["TASK_ACT_END_TIME"];
  if(strtotime($TASK_ACT_END_TIME)>0)
    $TASK_END_TIME = $TASK_ACT_END_TIME;
  $TASK_TIME =$ROW["TASK_TIME"];
  $START_END=$ROW["START_END"];
  $TASK_MILESTONE=$ROW["TASK_MILESTONE"];
  $TASK_PERCENT_COMPLETE = $ROW["TASK_PERCENT_COMPLETE"];

  $query1 = "SELECT USER_NAME from USER where USER_ID='$TASK_USER'";
  $cursor1= exequery(TD::conn(),$query1); 
  if($ROW = mysql_fetch_array($cursor1))
     $TASK_USER_NAME=$ROW["USER_NAME"];
       
  if($TASK_MILESTONE==1)
  {
     $bar = new MileStone($TASK_COUNT, array($TASK_NAME._("(��̱�)"),$TASK_USER_NAME,'-','-','-'),$TASK_START_TIME , '');
     $bar->title->SetFont(FF_SIMSUN,FS_NORMAL);
     $graph->Add($bar);
     continue;
  }
  //����
  if($TASK_PERCENT_COMPLETE > 100)
		$TASK_PERCENT_COMPLETE = 100;
	elseif($TASK_PERCENT_COMPLETE < 0)
		$TASK_PERCENT_COMPLETE = 0;
     
  $caption='['.$TASK_PERCENT_COMPLETE.'%]';
   if(strtotime($TASK_END_TIME)>strtotime($end_date))
      $TASK_END_TIME = $end_date;
  $bar = new GanttBar($TASK_COUNT,array($TASK_NAME,$TASK_USER_NAME,$TASK_TIME._("������"),$TASK_START_TIME." ",$TASK_END_TIME." "),$TASK_START_TIME,$TASK_END_TIME);
  $bar->title->SetFont(FF_SIMSUN,FS_NORMAL);
  $bar->progress->Set($TASK_PERCENT_COMPLETE/100);
  $bar->SetPattern(BAND_RDIAG,'blue');
  $bar->caption = new TextProperty($caption);
  $bar->caption->SetFont(FF_SIMSUN,FS_NORMAL,10); 
  $bar->caption->Align('left','center');
  $graph->Add($bar);
}
$graph->Stroke();
*/
?>