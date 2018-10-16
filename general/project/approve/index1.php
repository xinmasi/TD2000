<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
include_once("inc/utility_all.php");
include_once("inc/utility_project.php");
$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/theme/<?= $_SESSION["LOGIN_THEME"] ?>/dialog.css">
<script src="<?= MYOA_JS_SERVER ?>/static/js/dialog.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
//------zfc-------------
var showProj1 = false;
function showProj(PROJ_ID)
{
    if(showProj1)
        showProj1.close();
    myleft = (screen.availWidth - 1000) / 2;
    showProj1 = window.open("../portal/details/?PROJ_ID=" + PROJ_ID, "", "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=1000,height=600,left=" + myleft + ",top=50");
}
function approve(PROJ_ID, PASS)
{
    $("#PROJ_ID").val(PROJ_ID);
    $("#PASS").val(PASS);

    if (PASS == 1)
    {
        var msg = "<?= _("ȷ��Ҫ����<b style='color:red'>ͨ��</b>����Ŀ����������д���������") ?>";
    }
    else
    {
        var msg = "<?= _("ȷ��Ҫ<b style='color:red'>����</b>����Ŀ����������д�������ɣ�") ?>";
    }
    $(".modal-body p").html(msg);
    $('#myModal').modal('show');
}
function check_form()
{
    if ($("#content").innerText == "")
    {
        alert("<?= _("����д���������") ?>");
        return(false);
    }
    return(true);
}

var form_v = false;
function form_view(RUN_ID)
{
    if(form_v)
        form_v.close();
    form_v = window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

var p_c_a = false;
function project_change_approve(PROJ_ID)
{
    if(p_c_a)
        p_c_a.close();
    p_c_a = window.open("/general/project/approve/project_change_record.php?PROJ_ID="+PROJ_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
</script>
<body class="bodycolor" style="padding:10px;" >

<table class="table table-bordered table-hover" width="100%" align="center" >

<?
//�޸���������״̬--yc
update_sms_status('42',$PROJ_ID);

$query = "select a.*,b.USER_NAME FROM PROJ_PROJECT AS a LEFT JOIN USER AS b ON (a.PROJ_OWNER=b.USER_ID) where PROJ_STATUS=1 AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "' ORDER BY PROJ_UPDATE_TIME DESC";
$cursor = exequery(TD::conn(), $query);
$count = 0;
while ($ROW = mysql_Fetch_array($cursor))
{
    $count++;
    if($count == 1){
        echo '
              <tr class="info" style="color:#2a70e9; text-align:center;">
                <td style="text-align:center;"><span><strong>��Ŀ���</strong></span></td>
                <td style="text-align:center;"><span><strong>��Ŀ����</strong></span></td>
                <td style="text-align:center;"><span><strong>������</strong></span></td>
                <td style="text-align:center;"><span><strong>��ʼ����</strong></span></td>
                <td style="text-align:center;"><span><strong>����</strong></span></td>
                <td style="text-align:center;"><span><strong>��������</strong></span></td>
                <td style="text-align:center;"><span><strong>����</strong></span></td>
              </tr>
        ';
    }
    $DIFF_DAY = floor((strtotime($ROW["PROJ_END_TIME"]) - strtotime($ROW["PROJ_START_TIME"])) / (3600 * 24) ) + 1;
    $color = "";
    if(isset($PROJ_ID)){
        if($ROW['PROJ_ID'] == $PROJ_ID)
            $color = " warning";
    }
    
    
    $run_hook = get_run_id("PROJ_ID",$ROW["PROJ_ID"]);//is_run_hook("PROJ_ID",$ROW["PROJ_ID"]);
    if($run_hook)
        $run_hook = $run_hook[count($run_hook) - 1];
    //����ǰû�������̵ı���ԭ��
    if(!project_build($run_hook) && $run_hook > 0){
        $op = '<a href="#" onclick="form_view(' . $run_hook . ')">�鿴����</a>&nbsp;';
    }else{
        $op = '<a href="#" onclick=approve("' . $ROW["PROJ_ID"] . '","1")>ͨ��</a>&nbsp;
              <a href="#" onclick=approve("' . $ROW["PROJ_ID"] . '","0")>�ܾ�</a>&nbsp;
              ';
    }
    
    ?>
        <tr proj_id='<?= $ROW["PROJ_ID"]?>' class="<?= $color?>">
            <td style="text-align:center;"><?= $ROW["PROJ_NUM"]?></td>
            <td style="text-align:center;"><a href="#" onclick="showProj('<?= $ROW["PROJ_ID"]?>')"><?= $ROW["PROJ_NAME"]?></a></td>
            <td style="text-align:center;"><?= $ROW["USER_NAME"]?></td>
            <td style="text-align:center;"><?= $ROW["PROJ_START_TIME"]?></td>
            <td style="text-align:center;"><?= $DIFF_DAY._('��');?></td>
            <td style="text-align:center;"><?= $ROW["PROJ_END_TIME"]?></td>
            <td style="text-align:center;"><?= $op;?></td>
        </tr>
<?
}
if($count == 0)
{
    Message(_("��ʾ"), _("û��������¼��"));
}
?>
</table>


<!--�Ի�-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
    <h3 id="myModalLabel"><?= _("��Ŀ����");?></h3>
  </div>
  <div class="modal-body">
    <p></p>
    <textarea id="CONTENT" style="width:97%; height:100px; resize:none;"></textarea>
    <input type="hidden" name="PROJ_ID" id="PROJ_ID">
    <input type="hidden" name="PASS" id="PASS">
  </div>
  <div class="modal-footer">
    <button id="app_sure" class="btn btn-primary"><?= _("ȷ��")?></button>
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?= _("�ر�")?></button>
  </div>
</div>
<script>
$(function(){
    $("#app_sure").click(function(){
        $.post("approve.php",{PROJ_ID:$("#PROJ_ID").val(),PASS:$("#PASS").val(),CONTENT:$("#CONTENT").val()},function(data){
            if(data == '1'){
                $('#myModal').modal('hide');
                $("tr[proj_id='"+ $("#PROJ_ID").val() +"']").remove();
                $("#PROJ_ID").val('');
                $("#PASS").val('');
                $("#CONTENT").val('');
            }else{
                alert("����ʧ��������!")
            }
                
        });
    })    
})
</script>
</body>
</html>