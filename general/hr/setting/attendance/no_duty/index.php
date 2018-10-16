<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置免签人员");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<style type="text/css"> 
  .table{
    width: 60% !important;
  }
</style>
<body class="attendance">
<?
$SYS_PARA_ARRAY = get_sys_para("NO_DUTY_USER");
$USER_ID=$SYS_PARA_ARRAY["NO_DUTY_USER"];

$query = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$USER_ID."')";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $USER_NAME.=$ROW["USER_NAME"].",";
?>

<h5 class="attendance-title"><?=_("设置免签人员")?></h5>
<br>
  <form action="submit.php" method="post" name="form1">
  <table class="table table-bordered" align="center">
    <tr bgcolor="">
      <td >
      <input type="hidden" name="COPY_TO_ID" value="<?=$USER_ID?>">
      <textarea cols="900" name="COPY_TO_NAME" rows="6" style="width:98%;" wrap="yes" readonly><?=$USER_NAME?></textarea>
      <br>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td>
        <input type="submit" class="btn btn-primary" value="<?=_("保存")?>" style="margin-left:30%">
        <input type="button" class="btn" value="<?=_("返回")?>" onclick="location='../index.php#dutyOrno'" style="margin-left:20%">
      </td>
    </tr>
  </table>
  </form>
</body>
</html>
