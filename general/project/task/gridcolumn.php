<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/sys_code_field.php");
include_once("../setting/cust/sys/list/proj_list_config.php");
$OUTPUT_ARRAY = array();
$OUTPUT_ARRAY[] = array('xtype'=>'rownumberer');
$OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('操作'),'dataIndex'=>'action');
$FIELDS_ARRAY[] = 'action';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('项目编号'),'dataIndex'=>'PROJ_NUM');
      $FIELDS_ARRAY[] ='PROJ_NUM';

   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('项目名称'),'dataIndex'=>'PROJ_NAME');
      $FIELDS_ARRAY[] = 'PROJ_NAME';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('任务名称'),'dataIndex'=>'TASK_NAME');
      $FIELDS_ARRAY[] = 'TASK_NAME';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('任务等级'),'dataIndex'=>'TASK_LEVEL');
      $FIELDS_ARRAY[] = 'TASK_LEVEL';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('开始日期'),'dataIndex'=>'TASK_START_TIME');
      $FIELDS_ARRAY[] = 'TASK_START_TIME';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('工期'),'dataIndex'=>'TASK_TIME');
      $FIELDS_ARRAY[] = 'TASK_TIME';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('结束日期'),'dataIndex'=>'TASK_END_TIME');
      $FIELDS_ARRAY[] = 'TASK_END_TIME';
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('项目结束日期'),'dataIndex'=>'PROJ_END_TIME');
      $FIELDS_ARRAY[] = 'PROJ_END_TIME';

$OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('状态'),'dataIndex'=>'TASK_STATUS','sortable'=>'false','hideable'=>'false');
$FIELDS_ARRAY[] = 'TASK_STATUS';

ob_end_clean();
echo "var grid_field = ".array_to_json($FIELDS_ARRAY ).";\r\n";
echo "var grid_column = ".array_to_json($OUTPUT_ARRAY ).";";