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
            Message("","�ø�����û������δͨ��ԭ��!");
            Button_Close();
        }
       $UN = $VAL;
    }
}
$HTML_PAGE_TITLE = _("��Ŀ���������ʽ�δͨ��");
}else{
    $QUERY = "select record from proj_budget_real where proj_id='$PROJ_ID' and id='$unpass'";
    $CUR = exequery(TD::conn(),$QUERY);
    if($ROW = mysql_fetch_array($CUR)){
        $UN = $ROW['record'];
        if(empty($UN)){
            Message("","δ���ø�����ʽ�˵��!");
            Button_Close();
        }
    }
$HTML_PAGE_TITLE = _("��Ŀ�ʽ�����-�ʽ�˵��");    
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

