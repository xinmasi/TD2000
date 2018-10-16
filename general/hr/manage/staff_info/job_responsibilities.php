<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");


$HTML_PAGE_TITLE = _("岗位职责");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
function add_detail(ADD_ID)
{
    URL="/general/hr/setting/hr_code/func/edit.php?CODE_ID="+ADD_ID;
    myleft=(screen.availWidth-750)/2;
    window.open(URL,"detail","height=620,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function submit_edit()
{
    document.getElementById('iframe_edit').contentWindow.CheckForm();
}
//传递连接
$(function()
{

    $(".edit_add_href").click(function()
    {
        var show_add_id = $(this).attr('info');
        var url="job_edit.php?CODE_ID="+show_add_id;
        $('#iframe_edit').attr("src",url);
    })
})
</script>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" align="absmiddle"><span class="big3"> <?=_("岗位职责")?></span>
        </td>
    </tr>
</table>
<div style="width:85%; padding-bottom: 20px; margin: 0 auto;">
    <button hidefocus="true" type="button" class="btn btn-info " style="float: right;" onclick="window.location.href='job_graph.php'"/><?=_("岗位结构图")?>
    </button>
</div>
<table class="table table-bordered table-hover" id="talklist" name="talklist" style="width:85%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:10%;"><?=_("岗位名称")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("岗位类型")?></th>
        <th nowrap style="text-align: center;width:25%;"><?=_("岗位职责")?></th>
        <th nowrap style="text-align: center;width:25%;"><?=_("岗位描述")?></th>
        <th nowrap style="text-align: center;width:5%;"><?=_("操作")?></th>
    </thead>
<?
//============================ 显示 =======================================
$query = "select * from HR_CODE LEFT JOIN hr_job_responsibilities ON hr_job_responsibilities.JOB_CODE_ID=HR_CODE.CODE_ID where PARENT_NO='POOL_POSITION'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $CODE_ID= $ROW["CODE_ID"];
    $CODE_NAME= $ROW["CODE_NAME"];
    $JOB_CODE_STYLE_ID= $ROW["JOB_CODE_STYLE_ID"];
    $JOB_DESCRIPTION= $ROW["JOB_DESCRIPTION"];
    $JOB_RESPONSIBILITIES= $ROW["JOB_RESPONSIBILITIES"];
    //获取岗位名称
    $JOB_CODE_STYLE_NAME="";
    $JOB_CODE_STYLE_CODE_ID="";
    if($JOB_CODE_STYLE_ID !="")
    {

        $query1 = "select * from HR_CODE where PARENT_NO='JOBS_STYLE' and CODE_NO='$JOB_CODE_STYLE_ID';";
        $cursor1= exequery(TD::conn(),$query1);
        $ROW1=mysql_fetch_array($cursor1);
        $JOB_CODE_STYLE_NAME=$ROW1["CODE_NAME"];
        $JOB_CODE_STYLE_CODE_ID=$ROW1["CODE_ID"];
    }
?>

    <tr class="TableData">
        <td style="text-align: center;"><a href="javascript:add_detail('<?=$CODE_ID?>');"><?=$CODE_NAME;?></a></td>
        <td style="text-align: center;"><a href="javascript:add_detail('<?=$JOB_CODE_STYLE_CODE_ID?>');"><?=$JOB_CODE_STYLE_NAME;?></a></td>
        <td style="text-align: center;"><?=$JOB_RESPONSIBILITIES;?></td>
        <td style="text-align: center;"><?=$JOB_DESCRIPTION;?></td>
        <td style="text-align: center;" width="100">
            <a href="#edit_add" role="button" data-toggle="modal" info="<?=$CODE_ID?>" class="edit_add_href"><?=_("编辑")?></a>
        </td>
    </tr>
<?
}
?>
<!--编辑岗位信息-->
<div id="edit_add" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -400px;width: 800px;">
    <div class="modal-body" style="max-height: 403px; height: 403px;padding: 0px;overflow: hidden;">
        <iframe width="100%" height="100%" id="iframe_edit" name="iframe_edit" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_edit()" ><?=_("保存")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_edit"><?=_("关闭")?></button>
    </div>
</div>
<br>
</body>
</html>
