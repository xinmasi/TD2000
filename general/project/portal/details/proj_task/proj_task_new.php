<?php
//��ʾ�������̽���������Ϣ
//  by  zfc  
// 2013-12-27

//NEW��ʾ����  ALL��ʾ����
$SHOW = "NEW";

$QUERY = "SELECT TASK_NAME,FLOW_ID_STR,RUN_ID_STR FROM PROJ_TASK WHERE PROJ_ID='".$_GET['PROJ_ID']."' AND TASK_ID='".$_GET['TASK_ID']."'";
$CUR = exequery(TD::conn(),$QUERY);
if($ROW = mysql_fetch_array($CUR)){
    $FLOW_ID = rtrim($ROW['FLOW_ID_STR'],",");
    $RUN_ID = rtrim($ROW['RUN_ID_STR'],",");
    $TASK_NAME = $ROW['TASK_NAME'];
    $FLOW_ID_ARR = explode(",",$FLOW_ID);
    $RUN_ID_ARR = explode(",",$RUN_ID);
    
    if(!empty($RUN_ID)){
    $T = str_replace(',',"' OR RUN_ID ='", $RUN_ID );
    $QUERY = "SELECT RUN_NAME FROM FLOW_RUN WHERE RUN_ID = '" . $T . "'";
    $CUR = exequery(TD::conn(),$QUERY);
    $ARRNAME = array();
    $I = 0;
    while($ROW = mysql_fetch_array($CUR)){
        $ARRNAME[$I] = $ROW['RUN_NAME'];
        $I++;
    }
    if($I > 0){
    ?>
    <tr>
        <td><strong><?echo $TASK_NAME , "�������";?></strong></td>
        <td colspan="3" style="background:#eee;">
        <?PHP
        //��������������
        foreach($RUN_ID_ARR as $KEY => $VAL){
            echo $ARRNAME[$KEY] ."</br>";
            $Q = ($SHOW == "NEW") ? " ORDER BY PRCS_ID DESC LIMIT 0,1" : "";
            $QUERY = "SELECT * FROM FLOW_RUN_PRCS WHERE RUN_ID = '$VAL'" .$Q;
            $CUR = exequery(TD::conn(),$QUERY);
            while($ROW = mysql_fetch_array($CUR)){
                $T = "";
                $T1 ="";
                if($ROW['PRCS_TIME'] != NULL && $ROW['PRCS_TIME']!="0000-00-00 00:00:00" && ($ROW['DELIVER_TIME'] == NULL || $ROW['DELIVER_TIME']  =="0000-00-00 00:00:00")){
                    $T = "ִ����";
                    $T1 = "��ʼ��" . $ROW['PRCS_TIME'];
                }else if($ROW['DELIVER_TIME'] != NULL && $ROW['DELIVER_TIME']  !="0000-00-00 00:00:00"){
                    $T = "ִ�����";
                    $T1 = "������" .$ROW['DELIVER_TIME'];
                }else if(($ROW['PRCS_TIME'] == NULL || $ROW['PRCS_TIME']=="0000-00-00 00:00:00") && ($ROW['DELIVER_TIME'] == NULL || $ROW['DELIVER_TIME']  =="0000-00-00 00:00:00"))
                    $T = "δ����";
                ?>
                
                &nbsp;&nbsp;&nbsp;&nbsp;��ǰ����:<b style="color:red;">��<?= $ROW['PRCS_ID']?>�� </b><?= rtrim(GetUserNameById($ROW['USER_ID']),",") . '<b style="color:red;">' . $T .'</b> '. $T1 ."<BR/>"?>
                
                <?PHP
            
            }
            ECHO "</BR>";
        }
        ?>
        </td>
    <tr/>
    <?PHP
        }
    }else if(!empty($FLOW_ID)){
    ?>
     <tr>
        <td><strong><?echo $TASK_NAME , "�������";?></strong></td>
        <td colspan="3" style="background:#eee;">
    <?PHP
        echo "<b style='color:red;'>�ù�������δ����</b>";
    ?>
        </td>
    <tr/>
    <?PHP    
    
    }
}
?>