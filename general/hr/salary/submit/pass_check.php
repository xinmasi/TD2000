<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$SYS_PARA_ARRAY = get_sys_para("SALARY_PASS",false);
$SUPER_PASS2=$SYS_PARA_ARRAY["SALARY_PASS"]; 

if(crypt($SUPER_PASS, $SUPER_PASS2)!=$SUPER_PASS2)
{
   echo "-ERR";
}
else
{
   //��������ȷ�ı��д��session
   if(!isset($_SESSION["SALARY_PASS_FLAG"]))
   {
      $_SESSION["SALARY_PASS_FLAG"] = "Y";//��������������ȷ
   }
   
   echo "+OK";
}
?>


