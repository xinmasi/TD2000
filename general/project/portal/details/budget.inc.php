<?php
/**
*   budget.inc.php�ļ�
*
*   �ļ�����������
*   1���ɱ���Ԥ�������̨�߼�
*   2����ͼ����ͼ���ݴ���
*
*   @edit_time  2013/09/20
*
*/
$a_type_name_array = array();       //��Ŀ��������
$a_budget_amount_array = array();   //Ԥ���ʽ�����
$a_real_amount_array = array();     //ʵ���ʽ�����
$a_arr = array();                   //��ͼԤ���ʽ�����
$a_arr_real = array();              //��ͼʵ���ʽ�����
$i = 0;	

$s_query_length = "select * from proj_budget_type where CHAR_LENGTH(TYPE_NO) = 6";
$res_cursor_length = exequery(TD::conn(), $s_query_length);              
while($a_type = mysql_fetch_array($res_cursor_length))
{
//******��ȡ��Ŀ��Ϣ********
    $s_type_name = $a_type["type_name"];//��Ŀ����
    $s_type_code = $a_type["id"];//��Ŀid
//*******��ȡԤ����*******    
    $s_query_budget = "select BUDGET_AMOUNT from proj_budget where TYPE_CODE = '$s_type_code' and proj_id='$i_proj_id'";
    $res_cursor_budget = exequery(TD::conn(), $s_query_budget);
    $a_budget = mysql_fetch_array($res_cursor_budget);
    $i_budget_amount = $a_budget["BUDGET_AMOUNT"];//�ÿ�Ŀ�µ�Ԥ���ʽ�
    

//*******��ȡʵ�ʽ��*******  
    $s_query_real = "select sum(REAL_AMOUNT) as real_amount from proj_budget_real where TYPE_CODE ='$s_type_code' and proj_id='$i_proj_id' and status='1,1,1,'";
    $res_cursor_real = exequery(TD::conn(), $s_query_real);
    $a_real = mysql_fetch_array($res_cursor_real);
    $i_real_amount = $a_real["real_amount"];//�ÿ�Ŀ�µ�ʵ���ʽ��ܺ�
    //�������
    if($i_budget_amount||$i_real_amount)
    {
    	
		$a_type_name_array[$i] = $s_type_name;
		$a_budget_amount_array[$i] = floatval($i_budget_amount);
		$a_real_amount_array[$i] = floatval($i_real_amount);
		$i++;
    }
    
}
                    
//*******��״ͼ��̬���*******
$i_count_id = count($a_type_name_array);
$i_width = 750;
if($i_count_id>=5)
{
    $i_width = $i_count_id*150;	
    
}
					
//*******��ͼԤ���ʽ�����ת��*******
if(array_sum($a_budget_amount_array) != 0)
{
    foreach ($a_type_name_array as $key=>$value)
    {
        $a_arr[$key][name] = $value;
        $a_arr[$key][y] = floatval($a_budget_amount_array[$key]);	  
    }
}
else
{
    $a_arr = array(array("name"=>"����","y"=>1));
}

//*******��ͼʵ���ʽ�����ת��*******
if(array_sum($a_real_amount_array) != 0)
{
    foreach ($a_type_name_array as $key=>$value)
    {
        $a_arr_real[$key][name] = $value;
        $a_arr_real[$key][y] = floatval($a_real_amount_array[$key]);	  
    }
}
else
{
    $a_arr_real = array(array("name"=>"����","y"=>1));
}	
?>