<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="attendance">

<h5 class="attendance-title"><?=_("人员考勤记录")?></h5>
<br>

<?
Message(_("提示"),_("请选择人员"));
?>

</body>
</html>
