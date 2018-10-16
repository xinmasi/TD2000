<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor" topmargin="5">
<?

$STR_THE = $FIELDMSG."|".$FIELDMSGNAME;
$STR_COUNT = substr_count($FIELDMSG,',');
if($STR_COUNT < 15)
{
    $PARA_ARRAY=array("HR_MANAGER_ARCHIVES" => "$STR_THE");
    set_sys_para($PARA_ARRAY);
    Message("",_("保存成功"));
    Button_Back();
}
else
{
    Message("",_("添加的字段数目不能大于15个"));
    Button_Back();
}
?>
</body>
</html>