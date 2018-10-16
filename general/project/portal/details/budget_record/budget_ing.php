<table  class="table table-bordered table-striped time_table">
<tbody>
    <tr class="info time_table_top">
        <td>项目名称</td>
        <td>申请人</td>
        <td>申请类型</td>
        <td>申请金额</td>
        <td>资金说明</td>
        <td>进度</td>
    </tr>
	<?php
		$query = "select * from proj_budget_real where status = '' and proj_id = '$PROJ_ID' and real_id = '" . $LOGIN_USER_ID . "'";
		//项目负责人经理创建者可以查看全部资金申请
		if(project_view_priv($PROJ_ID) == 2){
			$query = "select * from proj_budget_real where status = '' and proj_id = '$PROJ_ID'";
		 }		
		$cur = exequery(TD::conn(),$query);
		while($row = mysql_fetch_array($cur)){
			$run_id = get_run_id("project_money_id",$row['id']);
			?>
			<tr>
				<td><?= $PROJ_NAME_ARR[$row['proj_id']]?></td>
				<td><?=_(rtrim(GetUserNameById($row['real_id']),','))?></td>
				<td><?= $PROJ_TYPE_ARR[$row['type_code']]?></td>
				<td><?=number_format($row['real_amount'],2)?><?=_("元")?></td>
				<td><a href="#" onclick="record('<?=_($row['id'])?>','');"><?=_(csubstr($row['record'],0,30))?></a></td>
				<td><a href="#" onclick="open_flow(<?= $run_id[0]?>)">查看流程</a></td>
			</tr>
			<?
		}
	?>

</tbody>
</table>
<script type="text/javascript">

var rec = false;
function record(un,step){
    if(rec)
        rec.close();
    URL = "budget_record/budget.php?unpass="+un+"&step="+step+"&PROJ_ID=<?=_($PROJ_ID)?>&type=1";
    rec = window.open (URL, "_blank", "height=400, width=500, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=150,left=200");
}

//general/workflow/list/print/?RUN_ID=571
var of = false;
function open_flow(RUN_ID){
	if(of)
		of.close();
	URL = "/general/workflow/list/print/?RUN_ID=" + RUN_ID;
	of = window.open (URL, "_blank", "height=600, width=1000, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=150,left=200");
}
</script>