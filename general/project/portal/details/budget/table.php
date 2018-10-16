<html>
<body>
<div style="overflow-y:auto;height:90%">
    <table align="center" border="1" class="table table-bordered table-striped time_table">
        <tr class="info time_table_top">
            <td nowrap rowspan='2' >
                <span>
                    <strong><?=_("科目")?></strong>
                </span>
			</td>
            <td colspan="2" align="center" style="text-align:center;">
                <strong><?=_("金额")?></strong>
            </td>
        </tr>
        <tr class="info time_table_top">
            <td width="28%" align="center" style="text-align:center;">
                <strong><?=_("预算(已审核)")?></strong>
            </td>
            <td width="28%" align="center" style="text-align:center;">
                <strong><?=_("实际费用")?></strong>
            </td>
        </tr>
        <?php 
        	$select_budget_type = "select id,type_name from proj_budget_type where char_length(type_no) = 6";
        	$res_cursor_length = exequery(TD::conn(),$select_budget_type);
            while($a_budget_type = mysql_fetch_array($res_cursor_length))
            {
                $s_type_name = $a_budget_type["type_name"];     //科目名称
                $i_type_code = $a_budget_type["id"];            //科目ID
                
            //------提取预算资金信息------
                $s_query_sub= "select  BUDGET_AMOUNT  from proj_budget where TYPE_CODE = '$i_type_code'and PROJ_ID = '$i_proj_id'";
                $res_cursor_sub = exequery(TD::conn(), $s_query_sub);
                $a_sub = mysql_fetch_array($res_cursor_sub);
                $i_budget_amount = $a_sub["BUDGET_AMOUNT"]; 
                
            //------提取实际资金信息------
                $s_query_real="select sum(REAL_AMOUNT) as sum_real_amount from proj_budget_real where TYPE_CODE ='$i_type_code' and PROJ_ID = '$i_proj_id'";
                $res_cursor_real = exequery(TD::conn(), $s_query_real);
                $a_real = mysql_fetch_array($res_cursor_real);
                $i_real_amount = $a_real["sum_real_amount"]; 
                
            //------成本、预算醒目警告色------
                if($i_real_amount > $i_budget_amount)
                {
                    $s_budget_col="red";
                }
                else if($i_real_amount == $i_budget_amount)
                {
                    $s_budget_col="orange";
                }
                else
                {
                    $s_budget_col="green";
                }
               if($i_real_amount!=0||$i_budget_amount!=0)
				{
        ?>
        <tr>
            <td align="center" style="text-align:center;color:<?php echo $s_budget_col;?>"><?=$s_type_name?></td>
            <td align="center" style="text-align:right;color:<?php echo $s_budget_col;?>"><?=number_format($i_budget_amount, 2);?></td>
            <td align="center" style="text-align:right;color:<?php echo $s_budget_col;?>"><?=number_format($i_real_amount, 2);?></td>
        </tr>
        <?php 
				}
        }
        ?>
    </table>
</div>
</body>
</html>
