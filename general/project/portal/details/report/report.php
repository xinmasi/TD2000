
<style>
.message{
    margin:auto;
    width: 600px;
    padding: 10px;
    border: 1px solid #B0B0B0;
    margin-top: 0px;
    margin-right: auto;
    margin-bottom: 0px;
    margin-left: auto;
    background-color:#F2F2F2;
    <!--text-align: center;-->
}
li{
                text-align:left;
                font-size:13px;
                line-height:180%;
            }

</style>
<h3 align="center"><?=$s_name?><?=_("��Ŀ����")?></h3>
    <p align="center"><?=_("������")?>:&nbsp<?=$s_leader?></p>
<div class="message">
    <h4 style="text-align:left; margin:0px"><?=_("������Ϣ")?></h4>
    <ul style="margin:8px">
       <li><?=_("��Ŀ���")?>��&nbsp;<?=$s_num?>;</li>
       <li><?=_("��Ŀ���")?>��&nbsp;<?=$s_type?>;</li>
       <li><?=_("��Ŀ����")?>��&nbsp;<?=$s_level_name?>;</li>
       <li><?=_("���벿��")?>��&nbsp;<?=td_trim($s_dept);?>;</li>
       <li><?=_("��Ŀ�ƻ�����")?>��&nbsp;<?=$s_start_time?><?=_("��")?>&nbsp;<?=_("��")?>&nbsp;<?=$s_end_time?><?=_("��")?>;</li>
    </ul>
    <hr>
    <h4 style="text-align:left; margin:0px"><?=_("��ϵ��")?></h4>
    <ul style="margin:8px">
      <li><?=_("��Ŀ������")?>��&nbsp;<?=$s_owner?>;</li>
      <li><?=_("��Ŀ������")?>��&nbsp;<?=$s_manager == "" ? _("��") : $s_manager?>;</li>
       <?php
        $query_select="SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_ROLE'";
        $cursor_code = exequery(TD::conn(), $query_select);
		while($ROW = mysql_fetch_array($cursor_code))
       {
			$CODE_NO=$ROW['CODE_NO'];
			$CODE_NAME=$ROW['CODE_NAME'];
			$s_user_id = $print_user_priv[$CODE_NO];
			$s_user_name = td_trim(GetUserNameById($s_user_id));
			if($s_user_name !="")
			{
       ?>
       <li><?=$CODE_NAME?>��<?=$s_user_name?>;</li>
       <?php 
			}
       }
       ?>
    </ul>
    
    <hr>
    <h4 style="text-align:left; margin:0px"><?=_("��Ԥ���ʽ�")?></h4>
    <ul style="margin:8px">
    <li>
        <?
        if($f_cost_money!=0 && $f_cost_money!="")
        {
            $cost_money = number_format($f_cost_money,2);
        }
        else
        {
            $cost_money = 0;
        }
        ?>
        <?=$cost_money?>&nbsp;<?=_("Ԫ")?>
    </li>
    </ul>
   
    <hr>
    <h4 style="text-align:left; margin:0px"><?=_("������Ϣ")?></h4>
    <br>	
    <?php
    $s_query_task="SELECT * FROM proj_task WHERE proj_id='$i_proj_id'";
    $res_cursor_task= exequery(TD::conn(), $s_query_task);
    while($a_task = mysql_fetch_array($res_cursor_task))
    {
        $s_task_name = $a_task['TASK_NAME'];
        $s_task_user_id = $a_task['TASK_USER'];
        $s_task_start_time = $a_task['TASK_START_TIME'];
        $s_task_end_time = $a_task['TASK_END_TIME'];
        $s_task_act_end_time = $a_task['TASK_ACT_END_TIME'];
        $i_task_time = $a_task['TASK_TIME'];
        $s_task_level = $a_task['TASK_LEVEL'];
        $s_task_status = $a_task['TASK_STATUS'];
        $s_query_task_user = "select USER_NAME from user where USER_ID='$s_task_user_id'";
        $res_cursor_task_user = exequery(TD::conn(),$s_query_task_user);
        while($a_task_user = mysql_fetch_array($res_cursor_task_user))
        {
            $s_task_user = $a_task_user['USER_NAME'];
        }
        switch($s_task_level)
        {
        case 0: 
            $s_task_level = _("��Ҫ");
            break;
        case 1: 
            $s_task_level = _("һ��");
            break;
        case 2: 
            $s_task_level = _("��Ҫ");
            break;
        case 3: 
            $s_task_level = _("�ǳ���Ҫ");
            break;
        }
        //����״̬
        $s_today = time();
        $s_task_start_time_int = strtotime($s_task_start_time);
        $s_task_end_time_int = strtotime($s_task_end_time);
        $s_task_act_end_time_int = strtotime($s_task_act_end_time);
        $s_status_str = '';
        if($s_task_status == "0")
        {
        	if($s_task_start_time_int > $s_today)
        	{
        		$s_status_str = _("δ��ʼ");
        	}else if($s_task_end_time_int > $s_today)
        	{
        		$s_status_str = _("������");
        	}else
        	{
        		$s_task_overtime=round(($s_today-$s_task_end_time_int) / 3600 / 24);
        		$s_status_str =  _("��ʱδ���(��ʱ").$s_task_overtime._("��)");
        	}
        }else
        {
        	if($s_task_act_end_time_int > $s_task_end_time_int)
        	{
        		$s_task_overtime=round(($s_task_act_end_time_int-$s_task_end_time_int) / 3600 / 24);
        		$s_status_str = _("��ʱ���(��ʱ").$s_task_overtime._("��)");
        	}else
        	{
        		$s_status_str = _("�����");
        	}
        }
    ?>
    <ul>
    <li><strong><?=_("��������")?>��&nbsp;<?=$s_task_name?>;</strong></strong></li>
    </ul>
    <ul>
    <li><?=_("����ִ����")?>��&nbsp;<?=$s_task_user?>;</li>
    <li><?=_("��ʼʱ��")?>��&nbsp;<?=$s_task_start_time?>;
        <?=_("����ʱ")?>��&nbsp;<?=$i_task_time?><?=_("��")?>;</li>
    <li><?=_("����ʱ��")?>��&nbsp;<?=$s_task_end_time?>;
        <?=_("����ȼ�")?>��&nbsp;<?=$s_task_level?>;</li>
    <li><?=_("����״̬")?>��&nbsp;<?=$s_status_str?>;</li>
    </ul>
    <hr>
    <?php
    }
    ?>
    <h4><?=_("����׷��")?></h4>
    <?php
    $s_query_follow = "select * from proj_bug where proj_id='{$i_proj_id}'";
    $res_cursor_follow = exequery(TD::conn(), $s_query_follow);
    
    while($a_bug =mysql_fetch_array($res_cursor_follow))
    {
        $i_task_id = $a_bug['TASK_ID'];
        $s_deal_user = $a_bug['DEAL_USER'];
        $s_begin_user = $a_bug['BEGIN_USER'];
        $s_dead_line = $a_bug['DEAD_LINE'];
        $s_creat_time = $a_bug['CREAT_TIME'];
        $s_level = $a_bug['LEVEL'];
        $s_bug_name = $a_bug['BUG_NAME'];
        $s_bug_desc = $a_bug['BUG_DESC'];
        $s_status = $a_bug['STATUS'];
        $s_result = $a_bug['RESULT'];
        $s_deal_user = GetUserNameById($s_deal_user);
        $s_begin_user = GetUserNameById($s_begin_user);
        switch($s_status){
        case 0: 
            $s_status = _("δ�ύ");
            break;
        case 1: 
            $s_status = _("δ����");
            break;
        case 2: 
            $s_status = _("������");
            break;
        case 3: 
            $s_status = _("�ѷ���");
            break;
        }
    ?>
    <ul style="margin:8px">
    <li><?=_("�ύ��")?>��<?=$s_begin_user?>;</li>
    <li><?=_("������")?>��<?=$s_deal_user?>;</li>
    <li><?=_("��������")?>��<?=$s_bug_name?>;</li>
    <li><?=_("����״̬")?>��<?=$s_status?>;</li>
    <li><?=_("�������ʱ��")?>��<?=$s_creat_time?>;</li>
    <li><?=_("�������")?>��<?=$s_dead_line?>;</li>
    <li><?=_("��������")?>��<?=$s_bug_desc?>;</li>
    <li><?=_("������")?>��<br/>
		<?php
			$s_result = rtrim($s_result,"|*|");
			$data = explode("|*|",$s_result);
			foreach($data as  $datas){
				echo $datas . "<br/><br/>";
			}
		?>	
	</li>
    </ul>
    <hr>
    <?php
    }
    ?>
    <hr>
    <h4><?=_("��Ŀ��ע")?></h4>
    <?php
    $s_query_annotation = "select * from proj_comment where proj_id='$i_proj_id'";
	$res_cursor_annotation = exequery(TD::conn(), $s_query_annotation);
    while($a_comment = mysql_fetch_array($res_cursor_annotation))
    {
        $s_writer = $a_comment['WRITER'];
        $s_writer = GetUserNameById($s_writer);
        $s_write_time = $a_comment['WRITE_TIME'];
        $s_content = $a_comment['CONTENT'];
    ?>
    <ul style="margin:8px">
        <li><?=_("��ע��")?>��<?=$s_writer?>;</li>
        <li><?=_("��עʱ��")?>��<?=$s_write_time?>;</li>
        <li><?=_("��ע����")?>��<?=$s_content?>;</li>
    </ul>
    <?php
    }
    ?>
    <hr>
    
</div>
    <br>
    <br>
