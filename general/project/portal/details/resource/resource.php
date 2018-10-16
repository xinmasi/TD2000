<html>
<body>

<div style="overflow-y:auto;height:100%">
    <table class="table table-bordered table-striped" width="100%" align="center" border='1' >
        <tr class="info time_table_top">
           <td nowrap rowspan='2' style="text-align:center" >
			<span>
			<strong>
            <?=_("资源名称")?>
            </strong>
			</span>
			</td>
            <td nowrap rowspan='2' style="text-align:center" >
			<span>
			<strong>
            <?=_("资源类型")?>
            </strong>
			</span>
			</td>
            <td nowrap align="center" colspan="2" style="text-align:center;"><strong>
            <?=_("开始时间")?>
            </strong></td>
            <td nowrap rowspan='2' style="text-align:center" >
			<span>
			<strong>
            <?=_("资源数量")?>
            </strong>
			</span>
			</td>
            <td nowrap rowspan='2' style="text-align:center" >
			<span>
			<strong>
            <?=_("资源单价")?>
            </strong>
			</span>
			</td>
            <td nowrap rowspan='2' style="text-align:center" >
			<span>
			<strong>
            <?=_("资源花费")?>
            </strong>
			</span>
			</td>
        </tr>
        <tr class="info time_table_top">
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("开始时间")?>
            </strong></td>
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("结束时间")?>
            </strong></td>
        </tr>
        <?php
        $s_query_particular = "SELECT * FROM proj_source WHERE proj_id='$i_proj_id'";
        $res_cursor_particular = exequery(TD::conn(), $s_query_particular);
            while($a_particular = mysql_fetch_array($res_cursor_particular))
            {
                $s_source_name = $a_particular['source_name'];
                $s_source_type = $a_particular['source_type'];
                $s_source_start_time = $a_particular['source_start_time'];
                $s_source_end_time = $a_particular['source_end_time'];
                $s_source_count = $a_particular['source_count'];
                $s_source_price = $a_particular['source_price'];
                $s_source_money = $a_particular['source_money'];
        ?>
        <tr>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_name?></td>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_type?></td>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_start_time?></td>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_end_time?></td> 
            <td nowrap align="center" style="text-align:center;"><?=$s_source_count?></td>
            <td nowrap align="center" style="text-align:center;"><?=number_format($s_source_price,2)?><?=_("元")?></td>
            <td nowrap align="center" style="text-align:center;"><?=number_format($s_source_money,2)?><?=_("元")?></td>
        </tr>
        <?php
            }
        ?>
    </table>
</div>

</body>
</html>
