<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("运行");
include_once("inc/header.inc.php");

if(strstr($PROG,"format"))
{
   Message("",_("非法程序"));
   exit;
}

$PROG=str_replace("/","\\\\",$PROG);
?>

<script>
function win_run()
{
  CoolRun.Path="<?=$PROG?>";
  CoolRun.RunPath();
  window.setTimeout(' window.close();',3000);
}
</script>



<body class="bodycolor" onLoad="win_run()">
<br>
<div align="center" class="big1">
<h1><b><?=$NAME?></b></h1>
</div>
<object classid="clsid:4AB8AC1A-AE97-49ff-A74C-1F3C0CFC9870" id="CoolRun" codebase="<?=MYOA_JS_SERVER?>/static/js/CoolRun.cab#version=1,0,0,0"></object>

</body>
</html>