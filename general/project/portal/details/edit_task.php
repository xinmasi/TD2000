<?php
/**
*   edit_task.inc.php�ļ�
*
*   �ļ�����������
*   1��ʱ���������༭��̨�߼�
*   2����Ŀ��������߼�
*
*   @edit_time  2013/09/20
*
*/
include_once("inc/auth.inc.php");
//----��ȡedit_task_time.php�б�������������----
if(isset($_POST["sub"]))
{
    $s_value = substr($_POST["task_percent_complete"], 0, -1);
    $i_task_percent_complete=intval($s_value);
    $i_proj_id = intval($_POST["proj_id"]);
    $i_task_id = intval($_POST["task_id"]);
	
    //------�������ݿ�------
    if($i_task_percent_complete != "")
    {
        if($i_task_percent_complete == 100)
        {
            $s_nowtime = date("Y-m-d",time());
            $s_query_edit_task = "update proj_task set TASK_PERCENT_COMPLETE='{$i_task_percent_complete}',TASK_ACT_END_TIME='{$s_nowtime}',TASK_STATUS='1' where task_id='{$i_task_id}'";
            exequery(TD::conn(), $s_query_edit_task);
        }
        else
        {
            $s_nowtime = "0000-00-00";
            $s_query_edit_task = "update proj_task set TASK_PERCENT_COMPLETE='{$i_task_percent_complete}',TASK_ACT_END_TIME='{$s_nowtime}',TASK_STATUS='0' where task_id='{$i_task_id}'";
            exequery(TD::conn(), $s_query_edit_task);
        }   
    }
    //*******��Ŀ�����㷨*******
    $I_day = 0;               //��ǰ�������ʱ��
    $total_task_time = 0;     //������ʱ�䣨��������  
    
    $s_select_task = "select TASK_TIME,TASK_PERCENT_COMPLETE from proj_task where proj_id='$i_proj_id'";
    $res_cursor_task = exequery(TD::conn(), $s_select_task);
    while ($a_task = mysql_fetch_array($res_cursor_task))
    {
        $total_task_time += $a_task["TASK_TIME"];
        $I_day += $a_task["TASK_TIME"] * $a_task["TASK_PERCENT_COMPLETE"];//����X����==�Ѱٷ���ת��������������
    }
    $total = $I_day / $total_task_time;  //�ܽ���
    $i_proj_complete = (int)$total;
    
	$update_proj_complete = "update proj_project set PROJ_PERCENT_COMPLETE = '$i_proj_complete' where PROJ_ID = '$i_proj_id'";
	exequery(TD::conn(), $update_proj_complete);
    
	header("location:proj_time.php?VALUE=3&PROJ_ID=$i_proj_id");//��ת�ص�ʱ��������	
}
?>