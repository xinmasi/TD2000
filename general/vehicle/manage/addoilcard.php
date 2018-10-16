<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("添加油卡信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
    
<?
//----------- 合法性校验 ---------
if($OC_DATE!="")
{
    $TIME_OK=is_date($OC_DATE);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("发卡日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}
//----------- 执行SQL ---------
if($ID=="")
{
   $query="insert into VEHICLE_OIL_CARD (OC_ID,OC_DATE,RECORDER_HANDLED,OC_HANDLED,OC_COMPANY,OC_PASSWORD,OC_STATUS,V_DEPT,V_NUM,V_TYPE,V_ONWER,V_USER,V_ID) values('$OC_ID','$OC_DATE','$RECORDER_HANDLED','$OC_HANDLED','$OC_COMPANY','$OC_PASSWORD','$OC_STATUS','$V_DEPT','$V_NUM','$V_TYPE','$V_ONWER','$V_USER','$V_ID')";
}
else
{
   $query="update VEHICLE_OIL_CARD set OC_ID='$OC_ID',OC_DATE='$OC_DATE',RECORDER_HANDLED='$RECORDER_HANDLED',OC_HANDLED='$OC_HANDLED',OC_COMPANY='$OC_COMPANY',OC_PASSWORD='$OC_PASSWORD',OC_STATUS='$OC_STATUS',V_DEPT='$V_DEPT',V_NUM='$V_NUM',V_TYPE='$V_TYPE',V_ONWER='$V_ONWER',V_USER='$V_USER',V_ID='$V_ID' where ID='$ID'";
}
exequery(TD::conn(),$query);

Message(_("提示"),_("油卡信息保存成功！"));
Button_Back();
?>

</body>
</html>
