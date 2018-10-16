
<table class="table table-bordered table-striped time_table">
<tbody>
    <tr class="info time_table_top">
        <td>项目名称</td>
        <td>申请人</td>
        <td>申请类型</td>
        <td>申请金额</td>
        <td>资金说明</td>
    </tr>
<?php
$query = "select * from proj_budget_real where real_id = '".$LOGIN_USER_ID."' and proj_id = '".$PROJ_ID."' and STATUS = '1,1,1,'";
  //项目负责人经理创建者可以查看全部资金申请
if(project_view_priv($PROJ_ID) == 2){
    $query = "select * from proj_budget_real where proj_id = '".$PROJ_ID."' and STATUS = '1,1,1,'";
 }   
$cur = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cur)){
    $PROJ_NAME = "NaN";
    if(isset($PROJ_NAME_ARR[$PROJ_ID])){
        $PROJ_NAME = $PROJ_NAME_ARR[$PROJ_ID];
    }
    
    $TYPE_NAME = $PROJ_TYPE_ARR[$ROW['type_code']];
    
    ?>
        <tr>
            <td><?=_($PROJ_NAME)?></td>
            <td><?=_(rtrim(GetUserNameById($ROW['real_id']),','))?></td>
            <td><?=_($TYPE_NAME)?></td>
            <td><?=number_format($ROW['real_amount'],2)?><?=_("元")?></td>
            <td><a href="#" onclick="record('<?=_($ROW['id'])?>','');"><?=_(csubstr($ROW['record'],0,30))?></a></td>
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
</script>