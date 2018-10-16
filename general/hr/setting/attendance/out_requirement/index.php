<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("外出原因填写要求");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<body class="attendance">
<?
$SYS_PARA_ARRAY = get_sys_para("OUT_REQUIREMENT");
$OUT_REQUIREMENT=$SYS_PARA_ARRAY["OUT_REQUIREMENT"];
?>

<h5 class="attendance-title"><?=_("外出原因填写要求")?></h5>
<br>
<form action="submit.php" method="post" name="form1">
<table class="table table-middle table-bordered" align="center">
  <tr>
  	
    <td class="">
      <textarea  name="OUT_REQUIREMENT"  style="width: 600px;height: 300px; " class="" wrap="yes"><?=$OUT_REQUIREMENT?></textarea>
    </td>
  </tr>
  <tr class="">
    <td align="center" valign="top" colspan="2" style="text-align: center;">
      <input type="submit" class="btn btn-primary" value="<?=_("保存")?>">&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" class="btn" value="<?=_("返回")?>" onclick="location='../index.php#inputOut'">
    </td>
  </tr>
</table>
</form>

</body>
</html>
