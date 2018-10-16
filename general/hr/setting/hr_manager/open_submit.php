<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?

$STR_THE = $FIELDMSG."|".$FIELDMSGNAME;
$PARA_ARRAY=array("HRMS_OPEN_FIELDS" => "$STR_THE");
set_sys_para($PARA_ARRAY);

Message("",_("±£´æ³É¹¦"));
Button_Back();
?>
</body>
</html>