<?
include_once("inc/auth.inc.php");
ob_end_clean();
//��������Ŀ�ʼ�ͽ���ʱ��
$query = "SELECT min(TASK_START_TIME) as start_date,max(TASK_END_TIME) as end_date,max(TASK_ACT_END_TIME) as end_date_act_task from PROJ_TASK where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $start_date=$ROW["start_date"];
    $end_date=$ROW["end_date"];
    $end_date_act_task=$ROW["end_date_act_task"];
    if(strtotime($end_date_act_task)>strtotime($end_date))
      $end_date = $end_date_act_task;
}
$query = "SELECT PROJ_END_TIME,PROJ_ACT_END_TIME from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_END_TIME=$ROW["PROJ_END_TIME"];
    $PROJ_ACT_END_TIME=$ROW["PROJ_ACT_END_TIME"];
   if(strtotime($PROJ_ACT_END_TIME)>0)
      $end_date_act = $PROJ_ACT_END_TIME;
}
//�����������������
$query = "SELECT * from PROJ_TASK where PROJ_ID='$PROJ_ID' order by TASK_NO";
$cursor= exequery(TD::conn(),$query);
$TASK_COUNT=0;
$TASK_ARR = array();
while($ROW=mysql_fetch_assoc($cursor))
{
    $TASK_COUNT++;
    foreach($ROW as $key=>$val)
    {
        if($key === "TASK_USER")
        {//�û���
            $query1 = "SELECT USER_NAME from USER where USER_ID='$val'";
            $cursor1= exequery(TD::conn(),$query1); 
            if($ROW1 = mysql_fetch_array($cursor1))
                $TASK_ARR[$TASK_COUNT][$key] = iconv(MYOA_CHARSET,"UTF-8",$ROW1['USER_NAME']);
        }
        else if(($key === "TASK_PERCENT_COMPLETE") && ($val > 100 || $val < 0))
        {//����
            if($val > 100)
                $TASK_ARR[$TASK_COUNT][$key] = 100;
            else if($val < 0)
                $TASK_ARR[$TASK_COUNT][$key] = 0;
        }
        else
        {
            $TASK_ARR[$TASK_COUNT][$key] = iconv(MYOA_CHARSET,"UTF-8",$val);
        }
    }
    //�������ʱ��
    $query2 = "SELECT max(LOG_TIME) as UPDATE_TIME from PROJ_TASK_LOG where TASK_ID='".$ROW['TASK_ID']."'";
    $cursor2 = exequery(TD::conn(),$query2);
    if($ROW2 = mysql_fetch_array($cursor2))
    {
        $UPDATE_TIME = explode(" ",$ROW2['UPDATE_TIME']);
        if($UPDATE_TIME[0] > $end_date)
        {
            $end_date = $UPDATE_TIME[0];
        }
        $TASK_ARR[$TASK_COUNT]['UPDATE_TIME'] = $UPDATE_TIME[0];
    }
}


//print_r($TASK_ARR);
//����xml
$dom = new DOMDocument('1.0',MYOA_CHARSET);
$dom->formatOutput = true;
$chart = $dom->createElement("chart");
$chart->setAttribute("dateFormat", "yyyy-mm-dd");
$chart->setAttribute("outputDateFormat", "yyyy-mm-dd");
$chart->setAttribute("ganttWidthPercent", "65");
$chart->setAttribute("canvasBorderColor", "999999");
$chart->setAttribute("canvasBorderThickness", "0");
$chart->setAttribute("gridBorderColor", "4567aa");
$chart->setAttribute("gridBorderAlpha", "20");
$chart->setAttribute("ganttPaneDuration", "3");
$chart->setAttribute("ganttPaneDurationUnit", "m");

//---------------------------------���������Ϣ��ʼ----------------------
//�������
$processes = $dom->createElement("processes");
$processes->setAttribute("headerText", iconv(MYOA_CHARSET,"UTF-8",_("��������")));
$processes->setAttribute("fontColor", "000000");
$processes->setAttribute("fontSize", "12");
$processes->setAttribute("isAnimated", "1");
$processes->setAttribute("bgColor", "4567aa");
$processes->setAttribute("headerVAlign", "bottom");
$processes->setAttribute("headerAlign", "left");
$processes->setAttribute("headerbgColor", "4567aa");
$processes->setAttribute("headerFontColor", "ffffff");
$processes->setAttribute("headerFontSize", "16");
$processes->setAttribute("align", "left");
$processes->setAttribute("isBold", "1");
$processes->setAttribute("bgAlpha", "25");

foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{//ѭ������������
    $process = $dom->createElement("process");
    //$process->setAttribute("labelDisplay", "stagger");
    //$process->setAttribute("staggerLines", 2);
    $process->setAttribute("label", $TASK_VAL['TASK_NAME']);
    $process->setAttribute("id", $TASK_VAL['TASK_ID']);
    $processes->appendChild($process);
}
$chart->appendChild($processes);
//��������
$dataTable = $dom->createElement("dataTable");
$dataTable->setAttribute("showProcessName", "1");
$dataTable->setAttribute("nameAlign", "left");
$dataTable->setAttribute("fontColor", "000000");
$dataTable->setAttribute("fontSize", "12");
$dataTable->setAttribute("vAlign", "right");
$dataTable->setAttribute("align", "center");
$dataTable->setAttribute("headerVAlign", "bottom");
$dataTable->setAttribute("headerAlign", "left");
$dataTable->setAttribute("headerbgColor", "4567aa");
$dataTable->setAttribute("headerFontColor", "ffffff");
$dataTable->setAttribute("headerFontSize", "16");

//������
$dataColumn = $dom->createElement("dataColumn");
$dataColumn->setAttribute("bgColor", "eeeeee");
$dataColumn->setAttribute("headerText", iconv(MYOA_CHARSET,"UTF-8",_("������")));
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $text = $dom->createElement("text");
    $text->setAttribute("label", $TASK_VAL['TASK_USER']);
    $dataColumn->appendChild($text);
}
$dataTable->appendChild($dataColumn);

//����
$dataColumn = $dom->createElement("dataColumn");
$dataColumn->setAttribute("bgColor", "eeeeee");
$dataColumn->setAttribute("headerText", iconv(MYOA_CHARSET,"UTF-8",_("����")));
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $text = $dom->createElement("text");
    $text->setAttribute("label", $TASK_VAL['TASK_TIME'] . iconv(MYOA_CHARSET,"UTF-8",_("��")));
    $dataColumn->appendChild($text);
}
$dataTable->appendChild($dataColumn);

//��ʼ����
$dataColumn = $dom->createElement("dataColumn");
$dataColumn->setAttribute("bgColor", "eeeeee");
$dataColumn->setAttribute("headerText", iconv(MYOA_CHARSET,"UTF-8",_("��ʼ����")));
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $text = $dom->createElement("text");
    $text->setAttribute("label", $TASK_VAL['TASK_START_TIME']);
    $dataColumn->appendChild($text);
}
$dataTable->appendChild($dataColumn);

//��������
$dataColumn = $dom->createElement("dataColumn");
$dataColumn->setAttribute("bgColor", "eeeeee");
$dataColumn->setAttribute("headerText", iconv(MYOA_CHARSET,"UTF-8",_("��������")));
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $text = $dom->createElement("text");
    if(!$end_date_act){
      $text->setAttribute("label", $TASK_VAL['TASK_END_TIME']);
   }else{
      $text->setAttribute("label",iconv(MYOA_CHARSET,"UTF-8",_("�ƻ���")).$TASK_VAL['TASK_END_TIME']."\n".iconv(MYOA_CHARSET,"UTF-8",_("ʵ�ʣ�")).$end_date_act);
   }
    $dataColumn->appendChild($text);
}
$dataTable->appendChild($dataColumn);

$chart->appendChild($dataTable);
//---------------------------------���������Ϣ����----------------------

//---------------------------------���ڿ�ʼ----------------------
//����
$start_date_arr = explode("-", $start_date);
$start_year = $start_date_arr[0];

$end_date_arr = explode("-", $end_date);
$end_year = $end_date_arr[0];
//flash��ͼ����ʾ��������Ϊ��׼ by zy 2012.7.10
$start_date_first = $start_date_arr[0]."-".$start_date_arr[1]."-01";
$end_date_last = $end_date_arr[0]."-".$end_date_arr[1]."-30";
//��
for($i = $start_year; $i <= $end_year; $i++)
{
    $start_year_date = ($i . "-01-01") < $start_date_first ? $start_date_first : $i . "-01-01";
    $end_year_date = ($i . "-12-31") > $end_date_last ? $end_date_last : $i . "-12-31";
    $year_arr[$i] = array("start"=>$start_year_date, "end"=>$end_year_date);
    //��
    for($m = 1; $m <= 12; $m++)
    {
        $month_count = date("t", strtotime($i . "-" . str_pad($m, 2, "0", STR_PAD_LEFT)));
        if($i . "-" . str_pad($m, 2, "0", STR_PAD_LEFT) < $start_date_arr[0] . "-" . $start_date_arr[1])
        {
            continue;
        }else if($i . "-" . str_pad($m, 2, "0", STR_PAD_LEFT) > $end_date_arr[0] . "-" . $end_date_arr[1])
        {
            continue;
        }else{
            $start_month_date = $i . "-" . str_pad($m, 2, "0", STR_PAD_LEFT) . "-01";//ȡ�³�
        }
        $end_month_date = ($i . "-" . str_pad($m, 2, "0", STR_PAD_LEFT) ."-" . $month_count);//ȡ��ĩ
        $month_arr[] = array("month"=>str_pad($m, 2, "0", STR_PAD_LEFT), "start"=>$start_month_date, "end"=>$end_month_date);
    }
}

//�����
$categories = $dom->createElement("categories");
$categories->setAttribute("bgColor", '009999');

foreach($year_arr as $k=>$v)
{
    $category = $dom->createElement("category");
    $category->setAttribute("start",$v['start']);
    $category->setAttribute("end",$v['end']);
    $category->setAttribute("label",$k);
    $category->setAttribute("fontColor","ffffff");
    $category->setAttribute("fontSize","16");
    $categories->appendChild($category);
}
$chart->appendChild($categories);
//�����
$categories = $dom->createElement("categories");
$categories->setAttribute("bgColor", '009999');
foreach($month_arr as $k=>$v)
{
    $category = $dom->createElement("category");
    $category->setAttribute("start",$v['start']);
    $category->setAttribute("end",$v['end']);
    $category->setAttribute("label",$v['month']);
    $category->setAttribute("fontColor","ffffff");
    $category->setAttribute("fontSize","12");
    $categories->appendChild($category);
}

$chart->appendChild($categories);
//�����
//$starttime = strtotime($start_date);
//$endtime = strtotime($end_date);
//$days = round(($endtime - $starttime) / 3600 / 24);
//if($days <= 30)
//{
//    $categories = $dom->createElement("categories");
//    $categories->setAttribute("bgColor", '009999');
//    for($i = strtotime($start_date); $i <= strtotime($end_date); $i += 86400)
//    {
//        $category = $dom->createElement("category");
//        $category->setAttribute("start",date("Y-m-d", $i));
//        $category->setAttribute("end",date("Y-m-d", $i));
//        $category->setAttribute("label",date("d", $i));
//        $category->setAttribute("fontColor","ffffff");
//        $category->setAttribute("fontSize","10");
//        $categories->appendChild($category);
//    }
//    $chart->appendChild($categories);
//}

//--------------------------------���ڽ���-------------------------

//--------------------------------���ݿ�ʼ-------------------------
$tasks = $dom->createElement("tasks");
$count = 0;
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $count++;
    //�ƻ�
    $task = $dom->createElement("task");
    $task->setAttribute("label",iconv(MYOA_CHARSET,"UTF-8",_("�ƻ�")));
    $task->setAttribute("processId",$TASK_VAL['TASK_ID']);
    $task->setAttribute("start",$TASK_VAL['TASK_START_TIME']);
    $task->setAttribute("end",$TASK_VAL['TASK_END_TIME']);
    $task->setAttribute("id",$count . "-1");
    $task->setAttribute("color","4567aa");
    $task->setAttribute("height","32%");
    $task->setAttribute("topPadding","12%");
    $task->setAttribute("alpha","100");
    if($TASK_VAL['TASK_MILESTONE'] == 1)
    {
        $task->setAttribute("isAnimated","1");
    }
    $tasks->appendChild($task);
    //ʵ��
    if($TASK_VAL['UPDATE_TIME'] != "" || $end_date_act)
    {
      if($TASK_VAL['UPDATE_TIME'] == '')
         $TASK_VAL['UPDATE_TIME'] = $end_date_act;
      if(strtotime($TASK_VAL['UPDATE_TIME']) < strtotime($TASK_VAL['TASK_START_TIME']))
         continue;
        $task = $dom->createElement("task");
        $task->setAttribute("label",iconv(MYOA_CHARSET,"UTF-8",_("ʵ��")));
        $task->setAttribute("processId",$TASK_VAL['TASK_ID']);
        $task->setAttribute("start",$TASK_VAL['TASK_START_TIME']);
        $task->setAttribute("end",$TASK_VAL['UPDATE_TIME']);
        $task->setAttribute("id",$count);
        $task->setAttribute("color","EEEEEE");
        $task->setAttribute("height","32%");
        $task->setAttribute("topPadding","56%");
        $task->setAttribute("alpha","100");
        $tasks->appendChild($task);
    }
}
$chart->appendChild($tasks);

$milestones = $dom->createElement("milestones");
$count = 0;
foreach($TASK_ARR as $TASK_KEY=>$TASK_VAL)
{
    $count++;
    if($TASK_VAL['TASK_MILESTONE'] == 1)
    {
        $milestone = $dom->createElement("milestone");
        $milestone->setAttribute("date",$TASK_VAL['TASK_END_TIME']);
        $milestone->setAttribute("taskId",$count . "-1");
        $milestone->setAttribute("color",'2E4472');
        $milestone->setAttribute("shape",'star');
        $milestone->setAttribute("toolText",iconv(MYOA_CHARSET,"UTF-8",_("��̱�")));
        $milestones->appendChild($milestone);
    }
}
$chart->appendChild($milestones);
//--------------------------------���ݽ���-------------------------
//print_r($TASK_ARR);
$dom->appendChild($chart);
header("Content-Type: text/plain");
echo $dom->saveXML();
?>