<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$prompt_value="";
if($PROMPT==1)
{
    $prompt_value .=$TRIAL_DAY_VALUE;
}   
else 
{
   $prompt_value .="";
}

if($LABOR==1)
{
    $prompt_value .=",".$LABOR_DAY_VALUE;
}   
else 
{
   $prompt_value .=",";
}
$query="update SYS_PARA set PARA_VALUE='$prompt_value' where PARA_NAME='TRIAL_LABOR_DAY'";
exequery(TD::conn(),$query);

Message("", _("保存成功"));
Button_Back();
?>
</body>
</html>
