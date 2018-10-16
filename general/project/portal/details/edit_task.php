<?php
/**
*   edit_task.inc.php文件
*
*   文件内容描述：
*   1、时间管理区块编辑后台逻辑
*   2、项目整体进度逻辑
*
*   @edit_time  2013/09/20
*
*/
include_once("inc/auth.inc.php");
//----提取edit_task_time.php中表单传过来的数据----
if(isset($_POST["sub"]))
{
    $s_value = substr($_POST["task_percent_complete"], 0, -1);
    $i_task_percent_complete=intval($s_value);
    $i_proj_id = intval($_POST["proj_id"]);
    $i_task_id = intval($_POST["task_id"]);
	
    //------更新数据库------
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
    //*******项目进度算法*******
    $I_day = 0;               //当前任务进行时间
    $total_task_time = 0;     //任务总时间（总天数）  
    
    $s_select_task = "select TASK_TIME,TASK_PERCENT_COMPLETE from proj_task where proj_id='$i_proj_id'";
    $res_cursor_task = exequery(TD::conn(), $s_select_task);
    while ($a_task = mysql_fetch_array($res_cursor_task))
    {
        $total_task_time += $a_task["TASK_TIME"];
        $I_day += $a_task["TASK_TIME"] * $a_task["TASK_PERCENT_COMPLETE"];//工期X进度==把百分数转换成天数来表现
    }
    $total = $I_day / $total_task_time;  //总进度
    $i_proj_complete = (int)$total;
    
	$update_proj_complete = "update proj_project set PROJ_PERCENT_COMPLETE = '$i_proj_complete' where PROJ_ID = '$i_proj_id'";
	exequery(TD::conn(), $update_proj_complete);
    
	header("location:proj_time.php?VALUE=3&PROJ_ID=$i_proj_id");//跳转回到时间管理界面	
}
?>