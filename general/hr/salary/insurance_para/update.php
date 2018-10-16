<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改保险参数");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
if($YES_OTHER=="on")
   $YES_OTHER=1;
else
   $YES_OTHER=0;
   
$query="update HR_INSURANCE_PARA set 
YES_OTHER='$YES_OTHER',
PENSION_U_PAY='$PENSION_U_PAY',
PENSION_U_PAY_ADD='$PENSION_U_PAY_ADD',
PENSION_P_PAY='$PENSION_P_PAY',
PENSION_P_PAY_ADD='$PENSION_P_PAY_ADD',
HEALTH_P_PAY='$HEALTH_P_PAY',
HEALTH_P_PAY_ADD='$HEALTH_P_PAY_ADD',
HEALTH_U_PAY='$HEALTH_U_PAY',
HEALTH_U_PAY_ADD='$HEALTH_U_PAY_ADD',
UNEMPLOYMENT_P_PAY='$UNEMPLOYMENT_P_PAY',
UNEMPLOYMENT_P_PAY_ADD='$UNEMPLOYMENT_P_PAY_ADD',
UNEMPLOYMENT_U_PAY='$UNEMPLOYMENT_U_PAY',
UNEMPLOYMENT_U_PAY_ADD='$UNEMPLOYMENT_U_PAY_ADD',
HOUSING_P_PAY='$HOUSING_P_PAY',
HOUSING_P_PAY_ADD='$HOUSING_P_PAY_ADD',
HOUSING_U_PAY='$HOUSING_U_PAY',
HOUSING_U_PAY_ADD='$HOUSING_U_PAY_ADD',
INJURY_U_PAY='$INJURY_U_PAY',
INJURY_U_PAY_ADD='$INJURY_U_PAY_ADD',
MATERNITY_U_PAY='$MATERNITY_U_PAY',
MATERNITY_U_PAY_ADD='$MATERNITY_U_PAY_ADD'";
exequery(TD::conn(),$query);

Message(_("提示"),_("保险参数已修改"));
parse_str($_SERVER["HTTP_REFERER"], $tmp_url);
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? isset($tmp_url["connstatus"]) ? $_SERVER["HTTP_REFERER"] : $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";

?>
<center>
		<input type="button" class="BigButton" value="<?=_("返回")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
