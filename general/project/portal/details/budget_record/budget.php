<?php
include_once("../details.inc.php");

$TYPE = isset($type) ? $type : 0;

if($TYPE == 0){
$QUERY = "select unpass from proj_budget_real where proj_id='$PROJ_ID' and id='$unpass'";
$CUR = exequery(TD::conn(),$QUERY);
if($ROW = mysql_fetch_array($CUR)){
    $UNPASS = json_decode($ROW['unpass']);
  
}

foreach($UNPASS as $KEY => $VAL){ 
    if($KEY == $step){
        if(empty($VAL)|| $VAL == NULL){
            Message("","该负责人没有留下未通过原因!");
            Button_Close();
        }
       $UN = $VAL;
    }
}
$HTML_PAGE_TITLE = _("项目经理申请资金未通过");
}else{
    $QUERY = "select record from proj_budget_real where proj_id='$PROJ_ID' and id='$unpass'";
    $CUR = exequery(TD::conn(),$QUERY);
    if($ROW = mysql_fetch_array($CUR)){
        $UN = $ROW['record'];
        if(empty($UN)){
            Message("","未设置该项的资金说明!");
            Button_Close();
        }
    }
$HTML_PAGE_TITLE = _("项目资金申请-资金说明");    
}

$UN = urldecode($UN);
?>
<body class="bodycolor">
    <div style="padding:20px; overflow:hidden; table-layout:fixed; word-break: break-all; ">
        <?=_($UN)?>
    </div>
        <?
        Button_Close();
        ?>
<body>
</html>

