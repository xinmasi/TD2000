<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- �Ϸ���У�� ---------
$BACK_TYPE=$_POST['BACK_TYPE'];
if($BACK_TYPE=="")
{
    Message(_("����"),_("��ѡ�����ջط�ʽ"));
    Button_Back();
    exit;
}

set_sys_para(array("VECHICLE_TAKE_BACK" => "$BACK_TYPE"));

header("location: vehicle_take_back.php");
?>

</body>
</html>
