<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/sys_code_field.php");
include_once("../setting/cust/sys/list/proj_list_config.php");
ob_end_clean();
$OUTPUT_ARRAY = array();
$OUTPUT_ARRAY[] = array('xtype'=>'rownumberer');
//if($_SESSION["LOGIN_USER_PRIV"]=="1")
//  $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('ѡ��'),'dataIndex'=>'choose','sortable'=>'false','hideable'=>'false');
//     $FIELDS_ARRAY[] = 'choose';
$OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('����'),'dataIndex'=>'action');
$FIELDS_ARRAY[] = 'action';
//  $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('ϵͳ��'),'dataIndex'=>'PROJ_ID','width'=>'50px','sortable'=>'false','hideable'=>'false');
//     $FIELDS_ARRAY[] = 'PROJ_ID';
//2010-5-10 LP ������Ϣ�Զ���
if($LIST_ARRAY['PROJ_NUM']==1) {
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('��Ŀ���'),'dataIndex'=>'PROJ_NUM');
      $FIELDS_ARRAY[] ='PROJ_NUM';
}
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('��Ŀ����'),'dataIndex'=>'PROJ_NAME');
      $FIELDS_ARRAY[] = 'PROJ_NAME';

if($LIST_ARRAY['PROJ_OWNER_NAME']==1) {
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('��Ŀ������'),'dataIndex'=>'PROJ_OWNER_NAME');
      $FIELDS_ARRAY[] = 'PROJ_OWNER_NAME';
}
if($LIST_ARRAY['PROJ_START_TIME']==1) {
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('��ʼ'),'dataIndex'=>'PROJ_START_TIME');
      $FIELDS_ARRAY[] = 'PROJ_START_TIME';
}
if($LIST_ARRAY['PROJ_END_TIME']==1) {
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('����'),'dataIndex'=>'PROJ_END_TIME','sortable'=>'false','hideable'=>'false');
      $FIELDS_ARRAY[] = 'PROJ_END_TIME';
}
if($LIST_ARRAY['PROJ_ACT_END_TIME']==1) {
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('����(ʵ��)'),'dataIndex'=>'PROJ_ACT_END_TIME','sortable'=>'false','hideable'=>'false');
      $FIELDS_ARRAY[] = 'PROJ_ACT_END_TIME';
}

if($LIST_ARRAY['PROJ_GLOBAL_VAL']==1){
   $ARRAY_SETTINGS = get_settings();
   
	if(is_array($ARRAY_SETTINGS)) {
		foreach($ARRAY_SETTINGS as $key => $value){
   $OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>$value['FIELDNAME'],'dataIndex'=>'extra_'.$key);
   $FIELDS_ARRAY[] = 'extra_'.$key;
		}	
	}
}
$OUTPUT_ARRAY[] = array('xtype'=>'gridcolumn','text'=>_('״̬'),'dataIndex'=>'PROJ_STATUS','sortable'=>'false','hideable'=>'false');
$FIELDS_ARRAY[] = 'PROJ_STATUS';

//$OUTPUT_ARRAY[] = array('text'=>_('����'),'dataIndex'=>'PROJ_TYPE','width'=>'50px','sortable'=>'false','hideable'=>'true');
//$FIELDS_ARRAY[] = 'PROJ_TYPE';
//$newArray = array("column"=>$OUTPUT_ARRAY,"field"=>$FIELDS_ARRAY);
echo "var grid_field = ".array_to_json($FIELDS_ARRAY ).";\r\n";
echo "var grid_column = ".array_to_json($OUTPUT_ARRAY ).";";