<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- �Ϸ���У�� ---------
if($VM_REQUEST_DATE!="")
{
    $TIME_OK=is_date($VM_REQUEST_DATE);

    if(!$TIME_OK)
    {
        Message(_("����"),_("ά�����ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($VM_FEE!=""&&!is_numeric($VM_FEE))
{
    Message(_("����"),_("ά������ӦΪ���֣�"));
    Button_Back();
    exit;
}

if($VM_ID=="")
    $query="insert into VEHICLE_MAINTENANCE (V_ID,VM_REQUEST_DATE,VM_TYPE,VM_REASON,VM_FEE,VM_PERSON,VM_REMARK) values('$V_ID','$VM_REQUEST_DATE','$VM_TYPE','$VM_REASON','$VM_FEE','$VM_PERSON','$VM_REMARK')";
else
    $query="update VEHICLE_MAINTENANCE set V_ID='$V_ID',VM_REQUEST_DATE='$VM_REQUEST_DATE',VM_TYPE='$VM_TYPE',VM_REASON='$VM_REASON',VM_FEE='$VM_FEE',VM_PERSON='$VM_PERSON',VM_REMARK='$VM_REMARK' where VM_ID='$VM_ID'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("����ά����¼����ɹ���"));

if($from == 'manage')
{
    echo '<center><input type="button" value="'._("����").'" class="BigButton" onclick="location=\'../manage/maintenance.php?V_ID='.$FROM_V_ID.'\'"></center>';
}
else
{
    Button_Back();
}
?>

</body>
</html>
