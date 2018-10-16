<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_project.php");

$UID = $LOGIN_USER_ID;
$NOW = date("Y-m-d",time());

//�޸���������״̬--yc
update_sms_status('42',0);

$ROLE = array(
    "PROJ_OWNER"=>_("�Ҵ�����"),
    "PROJ_LEADER"=>_("�Ҹ����"),
    "PROJ_VIEWER"=>_("�Ҳ鿴��"),
    "PROJ_USER"=>_("�Ҳ����"),
    "PROJ_MANAGER"=>_("��������")
);

$STATUS = array(
    "0" => _("������"),
    "1" => _("������"),
    "2" => _("������"),
    "3" => _("�ѽ���"),
    "4" => _("������"),
    "9" => _("������")
);

$QUERY = "select * FROM PROJ_PROJECT WHERE (FIND_IN_SET('$UID',PROJ_OWNER) OR FIND_IN_SET('$UID',PROJ_LEADER) OR FIND_IN_SET('$UID',PROJ_VIEWER) OR FIND_IN_SET('$UID',PROJ_USER) OR FIND_IN_SET('$UID',PROJ_MANAGER)) AND '$NOW' > PROJ_END_TIME AND PROJ_STATUS != 3" ;
$CUR = exequery(TD::conn(),$QUERY);
$DATA_ARRAY = array();
while($ROW = mysql_fetch_assoc($CUR)){
    if(strpos($ROW['PROJ_OWNER'],$UID) !== false)
        $DATA_ARRAY[$ROW['PROJ_ID']] .= $ROLE['PROJ_OWNER'] . ',';
    if(strpos($ROW['PROJ_LEADER'],$UID) !== false)
        $DATA_ARRAY[$ROW['PROJ_ID']] .= $ROLE['PROJ_LEADER'] . ',';
    if(strpos($ROW['PROJ_VIEWER'],$UID) !== false)
        $DATA_ARRAY[$ROW['PROJ_ID']] .= $ROLE['PROJ_VIEWER'] . ',';
    if(strpos($ROW['PROJ_USER'],$UID) !== false)
        $DATA_ARRAY[$ROW['PROJ_ID']] .= $ROLE['PROJ_USER'] . ',';
    if(strpos($ROW['PROJ_MANAGER'],$UID) !== false)
        $DATA_ARRAY[$ROW['PROJ_ID']] .= $ROLE['PROJ_MANAGER'] . ',';
    $DATA_ARRAY1[$ROW['PROJ_ID']] = $ROW;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
    <title><?= _("��ʱ��Ŀ����")?></title>
    <link rel="stylesheet" type="text/css" href="<?= MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?= $GZIP_POSTFIX?>">
    <script src="<?= MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?= $GZIP_POSTFIX?>"></script>
    <style>
        body{font-family: "ff-tisa-web-pro-1","ff-tisa-web-pro-2","Lucida Grande","Helvetica Neue",Helvetica,Arial,"Hiragino Sans GB","Hiragino Sans GB W3","Microsoft YaHei UI","Microsoft YaHei","WenQuanYi Micro Hei",sans-serif;}
        .table tr td{ text-align:center;}
    </style>
    <script>
        window.resizeTo(screen.availWidth, screen.availHeight);
        function open_project(ID){
            window.open("details/?PROJ_ID=" + ID, "_blank", "height=550, width=950, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");        
        }
    </script>
</head>
<body style="padding:10px;">
    <table class="table table-bordered table-hover" style="margin-bottom:0px;">
        <tr class="info" style="color:#2a70e9;">
            <td><strong><?= _("��Ŀ����");?></strong></td>
            <td><strong><?= _("�� ɫ");?></strong></td>
            <td><strong><?= _("��ʼʱ��");?></strong></td>
            <td><strong><?= _("����ʱ��");?></strong></td>
            <td><strong><?= _("״ ̬");?></strong></td>
            <td><strong><?= _("���۽���");?></strong></td>
            <td><strong><?= _("�� ��");?></strong></td>
        </tr>
        
        <?
            foreach((array)$DATA_ARRAY1 as $KEY => $V){
                ?>
                    <tr>
                        <td><?= $V['PROJ_NAME']?></td>
                        <td><?= rtrim($DATA_ARRAY[$KEY],",")?></td>
                        <td><?= $V['PROJ_START_TIME']?></td>
                        <td><?= $V['PROJ_END_TIME']?></td>
                        <td><?= $STATUS[$V['PROJ_STATUS']]?></td>
                        <td><?= $V['PROJ_PERCENT_COMPLETE'] . '%';?></td>
                        <td>
                        <?
                            if(project_update_priv($KEY)){
                                ?>
                                    <a href="javascript:;" onclick="open_project(<?= $KEY?>)"><?= _("����")?></a>
                                <?
                            }else{
                                ?>
                                    <a href="javascript:;" onclick="open_project(<?= $KEY?>)"><?= _("�鿴")?></a>
                                <?
                            }
                        ?>
                        </td>
                    </tr>
                <?
            }
        ?>
    </table>
</body>
</html>