<?php
/**
*   budget.inc.php文件
*
*   文件内容描述：
*   1、成本及预算区块后台逻辑
*   2、饼图、柱图数据处理
*
*   @edit_time  2013/09/20
*
*/
$a_type_name_array = array();       //科目名称数组
$a_budget_amount_array = array();   //预算资金数组
$a_real_amount_array = array();     //实际资金数组
$a_arr = array();                   //饼图预算资金数组
$a_arr_real = array();              //饼图实际资金数组
$i = 0;	

$s_query_length = "select * from proj_budget_type where CHAR_LENGTH(TYPE_NO) = 6";
$res_cursor_length = exequery(TD::conn(), $s_query_length);              
while($a_type = mysql_fetch_array($res_cursor_length))
{
//******获取科目信息********
    $s_type_name = $a_type["type_name"];//科目名称
    $s_type_code = $a_type["id"];//科目id
//*******获取预算金额*******    
    $s_query_budget = "select BUDGET_AMOUNT from proj_budget where TYPE_CODE = '$s_type_code' and proj_id='$i_proj_id'";
    $res_cursor_budget = exequery(TD::conn(), $s_query_budget);
    $a_budget = mysql_fetch_array($res_cursor_budget);
    $i_budget_amount = $a_budget["BUDGET_AMOUNT"];//该科目下的预算资金
    

//*******获取实际金额*******  
    $s_query_real = "select sum(REAL_AMOUNT) as real_amount from proj_budget_real where TYPE_CODE ='$s_type_code' and proj_id='$i_proj_id' and status='1,1,1,'";
    $res_cursor_real = exequery(TD::conn(), $s_query_real);
    $a_real = mysql_fetch_array($res_cursor_real);
    $i_real_amount = $a_real["real_amount"];//该科目下的实际资金总和
    //添加数据
    if($i_budget_amount||$i_real_amount)
    {
    	
		$a_type_name_array[$i] = $s_type_name;
		$a_budget_amount_array[$i] = floatval($i_budget_amount);
		$a_real_amount_array[$i] = floatval($i_real_amount);
		$i++;
    }
    
}
                    
//*******柱状图动态宽度*******
$i_count_id = count($a_type_name_array);
$i_width = 750;
if($i_count_id>=5)
{
    $i_width = $i_count_id*150;	
    
}
					
//*******饼图预算资金数据转换*******
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
    $a_arr = array(array("name"=>"所有","y"=>1));
}

//*******饼图实际资金数据转换*******
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
    $a_arr_real = array(array("name"=>"所有","y"=>1));
}	
?>