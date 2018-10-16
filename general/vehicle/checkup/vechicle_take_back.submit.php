<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
$BACK_TYPE=$_POST['BACK_TYPE'];
if($BACK_TYPE=="")
{
    Message(_("错误"),_("请选择车辆收回方式"));
    Button_Back();
    exit;
}

set_sys_para(array("VECHICLE_TAKE_BACK" => "$BACK_TYPE"));

header("location: vehicle_take_back.php");
?>

</body>
</html>
