<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("��λְ��༭");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script Language="JavaScript">
function CheckForm()
{
    document.form1.submit();
}
</script>

<body class="bodycolor">
<!--<div style="width:600px; margin:0 auto; height:50px; line-height:50px;">
   <span class="big3"> <?=_("��λְ��༭")?></span>
</div>-->
<br/>
<div style="width:600px;border: #EEE0E0 1px solid;margin:0 auto; background-color:#FFF; padding:10px 0 5px 0">
  <form action="job_insert.php"  method="post" name="form1" class="form-horizontal" onSubmit="return CheckForm();">
<?
$CODE_ID=$_GET["CODE_ID"];
$query = "select * from HR_CODE LEFT JOIN hr_job_responsibilities ON hr_job_responsibilities.JOB_CODE_ID=HR_CODE.CODE_ID where PARENT_NO='POOL_POSITION' and CODE_ID='$CODE_ID'";
$cursor= exequery(TD::conn(),$query);
$ROW=mysql_fetch_array($cursor);
$CODE_NAME= $ROW["CODE_NAME"];
$JOB_CODE_STYLE_ID= $ROW["JOB_CODE_STYLE_ID"];
$JOB_DESCRIPTION= $ROW["JOB_DESCRIPTION"];
$JOB_RESPONSIBILITIES= $ROW["JOB_RESPONSIBILITIES"];
?>
    <div class="control-group">
        <label class="control-label"><?=_("��λ���ƣ�")?></label>
        <div class="controls" style="padding-top: 5px;"><?=$CODE_NAME?></div>
    </div>

    <div class="control-group">
        <label class="control-label" for="GROUP_ID"><?=_("��λ���ͣ�")?></label>
        <div class="controls">
            <select name="JOBS_STYLE" title="��λ���Ϳ��ڡ�������Դ��->��������Դ���á�->��HRMS�������á�ģ�����á�">
                <?=hrms_code_list("JOBS_STYLE","$JOB_CODE_STYLE_ID");?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label"><?=_("��λ������")?></label>
        <div class="controls">
            <textarea rows="5" cols="10" name="JOB_DESCRIPTION"><?=$JOB_DESCRIPTION?></textarea>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label"><?=_("��λְ��")?></label>
        <div class="controls">
            <textarea rows="5" cols="10" name="JOB_RESPONSIBILITIES"><?=$JOB_RESPONSIBILITIES?></textarea>
        </div>
    </div>

      <input type="hidden" name="code_id" value="<?=$CODE_ID?>">
</form>
</div>
</body>
</html>