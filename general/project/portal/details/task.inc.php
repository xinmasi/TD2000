<?php
/**
*   task.inc.php�ļ�
*
*   �ļ����������� 
*   1����ȡָ������ID�µ�������Ϣ
*   
*   @edit_time  2013/09/20
*
*/
//-------��������ǰ��Ŀ�£�ָ��taskID���������Ϣ-------
$s_query_task = "select * from proj_task where task_id='$i_task_id' and proj_id='$i_proj_id'";
$res_cursor_task = exequery(TD::conn(), $s_query_task);

while($a_task = mysql_fetch_array($res_cursor_task))
{
    $i_task_no = $a_task['TASK_NO'];
    $s_task_name = $a_task['TASK_NAME'];
    $s_task_description = $a_task['TASK_DESCRIPTION'];
    $s_task_user_id = $a_task['TASK_USER'];
    $s_task_start_time = $a_task['TASK_START_TIME'];
    $s_task_end_time = $a_task[TASK_END_TIME];
    $s_task_act_end_time = $a_task[TASK_ACT_END_TIME];
    $i_task_time = $a_task[TASK_TIME];
    $s_task_level = $a_task[TASK_LEVEL];
    $i_task_pre_task = $a_task[PRE_TASK];
    $s_task_remark = $a_task[REMARK];
    $s_task_status = $a_task[TASK_STATUS];
    $i_task_parent_task = $a_task[PARENT_TASK];
    $i_task_percent_complete = $a_task[TASK_PERCENT_COMPLETE];
}

//-----��ȡ����ִ�����û�����-----
if($s_task_user_id)
{
    $s_query_user = "select USER_NAME from user where USER_ID='$s_task_user_id'";
    $res_cursor_user = exequery(TD::conn(), $s_query_user);
    while($a_user = mysql_fetch_array($res_cursor_user))
    {
        $s_task_user = $a_user['USER_NAME'];
    }
}

//-----��ȡ���񼶱�-----
switch ($s_task_level) 
{
    case '0':
        $s_task_level = _("��Ҫ");
        break;
    case '1':
        $s_task_level = _("һ��");
        break;
    case '2':
        $s_task_level = _("��Ҫ");
        break;
    case '3':
        $s_task_level = _("�ǳ���Ҫ");
        break;
    default:
        $s_task_level = _("������δ���ü���!");
        break;
}

//------��ȡ����״̬------
$s_today = time();
$s_task_start_time_int = strtotime($s_task_start_time);
$s_task_end_time_int = strtotime($s_task_end_time);
$s_task_act_end_time_int = strtotime($s_task_act_end_time);
$s_status_str = $s_task_overtime='';
if($s_task_status == 0)
{
    if($s_task_start_time_int > $s_today)
    {
        $s_status_str = _("����δ��ʼ");
    }
    else if($s_task_end_time_int > $s_today)
    {
        $s_status_str = _("������");
    }
    else
    {   
        $s_task_overtime=round(($s_today-$s_task_end_time_int) / 3600 / 24);
        $s_status_str = _("��ʱδ���(��ʱ").$s_task_overtime._("��)");
	}
}
else
{
    if($s_task_act_end_time_int > $s_task_end_time_int)
	{
        $s_task_overtime=round(($s_task_act_end_time_int-$s_task_end_time_int) / 3600 / 24);
        $s_status_str = _("��ʱ���(��ʱ").$s_task_overtime._("��)");
    }
    else
    {
        $s_status_str = _("�����");
    }
}

//-----ʵ�ʽ���ʱ����ʾ�Ż�------
if($s_task_act_end_time == "0000-00-00")
{
    $s_task_act_end_time = _("��δ���");
}

//------��ȡ�ϼ�������------
if($i_task_parent_task == 0)
{
    $s_task_parent_task = "  ��  ";
}
else
{
    $s_query_parent = "select TASK_NAME from proj_task where task_id='{$i_task_parent_task}'";
    $res_cursor_parent = exequery(TD::conn(), $s_query_parent);
    while($a_parent = mysql_fetch_array($res_cursor_parent))
    {
        $s_task_parent_task = $a_parent[0];
    }
}

//------��ȡǰ��������------
if($i_task_pre_task == 0)
{
    $s_task_pre_task = "  ��  ";
}
else
{
    $s_query_pre = "select TASK_NAME from proj_task where task_id='{$i_task_pre_task}'";
    $res_cursor_pre= exequery(TD::conn(), $s_query_pre);
    while($a_pre = mysql_fetch_array($res_cursor_pre))
    {
        $s_task_pre_task=$a_pre[0];
    }
}

?>