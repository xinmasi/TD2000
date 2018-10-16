<?php
include_once("details.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?= MYOA_JS_SERVER ?>/static/js/bootstrap/css/bootstrap.min.css<?= $GZIP_POSTFIX ?>" />
<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/modules/project/css/project.css" />
<script src="<?= MYOA_JS_SERVER ?>/static/js/jquery-1.10.2/jquery.min.js<?= $GZIP_POSTFIX ?>"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/bootstrap/js/bootstrap.min.js<?= $GZIP_POSTFIX ?>"></script>
<script src="<?= MYOA_STATIC_SERVER ?>/static/modules/project/js/jquery.stickytableheaders.js"></script>
<html>
    <body onload="window.print('Print');">
        <div style="overflow-y:auto;height:99%" class="print">
            <table class="table table-bordered table-striped time_table" width="100%" align="center" >
                <thead>
                    <tr class="time_table_top">
                        <td nowrap rowspan='2' >
                            <span>
                                <strong><?= _("��������") ?></strong>
                            </span>
                        </td>

                        <td nowrap rowspan='2'>
                            <span>
                                <strong><?= _("��ǰ״̬") ?>
                                </strong>
                            </span>
                        </td>
                        <td nowrap colspan='2'><strong>
                                <?= _("��ʼ����") ?>
                            </strong></td>
                        <td nowrap colspan='2'><strong>
                                <?= _("�������") ?>
                            </strong></td>
                    </tr>
                    <tr class="time_table_top">
                        <td nowrap width="15%"><strong>
                                <?= _("�ƻ�") ?>
                            </strong></td>
                        <td nowrap width="15%"><strong>
                                <?= _("ʵ��") ?>
                            </strong></td>
                        <td nowrap width="15%"><strong>
                                <?= _("�ƻ�") ?>
                            </strong></td>
                        <td nowrap width="15%"><strong>
                                <?= _("ʵ��") ?>
                            </strong></td>
                    </tr>
                </thead>
                </tbody>
                <?php
                /**
                 * 
                 * �ѳ�ʱ > ������ > δ��ʼ  > ��ʱ��� > ����ɣ�ʵ�ʽ���ʱ��TASK_ACT_END_TIME����
                 * 
                 */
                $time =date("Y-m-d",time());
                $select_task = "SELECT * from proj_task where proj_id = '$i_proj_id' ORDER BY TASK_ACT_END_TIME ,TASK_END_TIME>'$time' asc,TASK_START_TIME asc";
                $res_cursor_time = exequery(TD::conn(),$select_task);
                while($a_row_task = mysql_fetch_array($res_cursor_time))
                {
                //------��ȡ���������Ϣ------
                    $i_task_id = $a_row_task["TASK_ID"];
                    $s_task_name = $a_row_task["TASK_NAME"];
                    $s_task_start_time = $a_row_task["TASK_START_TIME"];  
                    $s_task_act_start_time = $a_row_task["TASK_START_TIME"];
                    $s_task_end_time = $a_row_task["TASK_END_TIME"];  
                    $s_task_status = $a_row_task["TASK_STATUS"];
                    $s_task_user = $a_row_task["TASK_USER"];
                    $i_task_parent_task = $a_row_task["PARENT_TASK"];
 
                //------�ж������Ƿ����------
                    if($a_row_task["TASK_ACT_END_TIME"] == "0000-00-00")
                    {
                        $s_task_act_end_time = _("δ���");
                    }else
                    {
                        $s_task_act_end_time = $a_row_task["TASK_ACT_END_TIME"]; 
                    }

                //------�ж�����ǰ״̬------
                    $s_today = time();
                    $s_task_start_time_int = strtotime($s_task_start_time);
                    $s_task_end_time_int = strtotime($s_task_end_time);
                    $s_task_act_end_time_int = strtotime($s_task_act_end_time);
                    $s_status_str = $s_status_color = $s_task_overtime='';
                
                    if($s_task_status == "0")
                    {
                        if($s_task_start_time_int > $s_today)
                        {
                            $s_status_str = _("δ��ʼ");
                            $s_status_color = 'blue';
                        }else if($s_task_end_time_int > $s_today)
                        {
                            $s_status_str = _("������");
                            $s_status_color = 'green';
                        }else 
                        {   
                            $s_task_overtime=round(($s_today-$s_task_end_time_int) / 3600 / 24);
                            $s_status_str =  _("��ʱδ���(��ʱ").$s_task_overtime._("��)");
                            $s_status_color = 'red';
                        }
                    }else
                    {
                        if($s_task_act_end_time_int > $s_task_end_time_int)
                        {
                            $s_task_overtime=round(($s_task_act_end_time_int-$s_task_end_time_int) / 3600 / 24);
                            $s_status_str = _("��ʱ���(��ʱ").$s_task_overtime._("��)");
                            $s_status_color = 'orange';
                        }else
                        {
                            $s_status_str = _("�����");
                            $s_status_color = '#81b71b';
                        }
                    }
                    ?>
                    <tr>
                        <td nowrap style="color:<?= $s_status_color ?>;text-align:left"><?= $s_task_name ?></td>
                        <td nowrap style="color:<?= $s_status_color ?>;"><?= $s_status_str ?></td>
                        <td nowrap style="color:<?= $s_status_color ?>;"><?= $s_task_start_time ?></td>
                        <td nowrap style="color:<?= $s_status_color ?>;"><?= $s_task_act_start_time ?></td>
                        <td nowrap style="color:<?= $s_status_color ?>;"><?= $s_task_end_time ?></td>
                        <td nowrap style="color:<?= $s_status_color ?>;"><?= $s_task_act_end_time ?></td>
                    </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
