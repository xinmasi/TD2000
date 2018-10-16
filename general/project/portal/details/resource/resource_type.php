<html>
<body>

<div style="overflow-y:auto;height:100%">
    <table class="table table-bordered table-striped" width="100%" align="center" border='1' >
        <tr class="info time_table_top">
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("资源名称")?>
            </strong></td>
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("资源类型")?>
            </strong></td>
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("资源数量")?>
            </strong></td>
            <td nowrap align="center" style="text-align:center;"><strong>
            <?=_("资源花费")?>
            </strong></td>
        </tr>
        <?php
        $s_query_colection = "SELECT * FROM proj_source WHERE proj_id='{$i_proj_id}' ORDER BY source_type ";
        $res_cursor_colection = exequery(TD::conn(), $s_query_colection);
            while($a_colection = mysql_fetch_array($res_cursor_colection))
            {
                $s_source_name_colection = $a_colection['source_name'];
                $s_source_type_colection = $a_colection['source_type'];
                $s_source_count_colection = $a_colection['source_count'];
                $s_source_money_colection = $a_colection['source_money'];
        ?>
        <tr>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_name_colection?></td>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_type_colection?></td>
            <td nowrap align="center" style="text-align:center;"><?=$s_source_count_colection?></td>
            <td nowrap align="center" style="text-align:center;"><?=number_format($s_source_money_colection,2)?><?=_("元")?></td>
        </tr>
        <?php
            }
        ?>
    </table>
</div>

</body>
</html>
