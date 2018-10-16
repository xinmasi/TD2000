<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

require_once ('inc/ExcelWriter.php');

if(MYOA_IS_UN == 1)
   $FIELD_ARRAY = array("DEPARTMENT","NAME","ROLE","OTHER_ROLE","ONLINE","SEX","COMPANY_PHONE","DEPARTMENT_NO","MOBIL_NO","EMAIL");
else
   $FIELD_ARRAY = array(_("部门"),_("姓名"),_("角色"),_("辅助角色"),_("在线时长"),_("性别"),_("工作电话"),_("部门电话"),_("手机"),_("电子邮件"));
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("用户信息导出"));
$objExcel->addHead($FIELD_ARRAY);

$PRIV_OP_FLAG=0;
$MODULE_ID='2';
include_once("inc/my_priv.php");

if($DEPT_PRIV == "0")
   $WHERE_STR.=" and USER.DEPT_ID in (".td_trim(GetChildDeptId($_SESSION["LOGIN_DEPT_ID"])).")";
else if($DEPT_PRIV == "2")
   $WHERE_STR.=" and USER.DEPT_ID in (".td_trim($DEPT_ID_STR).")";
else if($DEPT_PRIV == "3")
   $WHERE_STR.=" and find_in_set(USER_ID, '$USER_ID_STR')";

if($ROLE_PRIV == "0")
   $WHERE_STR.=" and USER_PRIV.PRIV_NO>'$MY_PRIV_NO'";
else if($ROLE_PRIV == "1")
   $WHERE_STR.=" and USER_PRIV.PRIV_NO>='$MY_PRIV_NO'";
else if($ROLE_PRIV == "3")
   $WHERE_STR.=" and USER_PRIV.USER_PRIV in (".td_trim($PRIV_ID_STR).")";

//-----------先组织SQL语句-----------

if($DEPT_ID!="")
{
	$DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
	$WHERE_STR.=" and (USER.DEPT_ID in ($DEPT_ID_CHILD) or ".str_replace(",", " in (USER.DEPT_ID_OTHER) or ", $DEPT_ID_CHILD)." in (USER.DEPT_ID_OTHER))";
}

if($USER_PRIV!="")
{
	$WHERE_STR .=" and USER.USER_PRIV=$USER_PRIV";
}

if($MOBIL_NO!="")
{
    $WHERE_STR .= " and MOBIL_NO like '%$MOBIL_NO%'";
}

if($TEL_NO_DEPT!="")
{
    $WHERE_STR .= " and TEL_NO_DEPT like '%$TEL_NO_DEPT%'";
}

if($TEL_NO_HOME!="")
{
    $WHERE_STR .= " and TEL_NO_HOME like '%$TEL_NO_HOME%'";
}

if($OICQ_NO!="")
{
    $WHERE_STR .= " and OICQ_NO like '%$QQ%'";
}

if($EMAIL!="")
{
    $WHERE_STR .= " and EMAIL like '%$EMAIL%'";
}
  

$USER_COUNT=0;

$query = "SELECT * from USER,USER_PRIV,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV  ".$WHERE_STR." order by ";

if($ORDER == 'dept')
{
	$query       .= " DEPT_NO,PRIV_NO,USER_NO,USER_NAME";	
}elseif($ORDER == 'name')
{
	$query       .= " USER_NAME,DEPT_NO,PRIV_NO,USER_NO";
}elseif($ORDER == 'priv')
{
	$query       .= " PRIV_NO,DEPT_NO,USER_NO,USER_NAME";
}

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID              = $ROW["USER_ID"];
  $USER_NAME            = format_cvs($ROW["USER_NAME"]);
  $PRIV_NAME            = format_cvs($ROW["PRIV_NAME"]);
  $DEPT_ID              = $ROW["DEPT_ID"];
  $DEPT_NAME            = format_cvs($ROW["DEPT_NAME"]);
  $SEX                  = $ROW["SEX"];
  $TEL_NO_DEPT          = $ROW["TEL_NO_DEPT"];
  $TEL_NO               = $ROW["TEL_NO"];
  $MOBIL_NO             = $ROW["MOBIL_NO"];
  $EMAIL                = $ROW["EMAIL"];
  $ONLINE               = $ROW["ONLINE"];
  $USER_PRIV_OTHER      = $ROW["USER_PRIV_OTHER"];
  $USER_PRIV_OTHER_NAME = format_cvs(GetPrivNameById($USER_PRIV_OTHER));
  $MOBIL_NO_HIDDEN      = $ROW["MOBIL_NO_HIDDEN"];
  $HOUR                 = round($ONLINE/3600,1);
  $USER_COUNT++;

  if($DEPT_ID==0)
     $DEPT_NAME=_("离职人员/外部人员");

  if($SEX=="")
    $SEX="0";
  if($SEX=="0")
     $SEX=_("男");
  else
     $SEX=_("女");

  $DEPT_LONG_NAME=format_cvs(dept_long_name($DEPT_ID));
  $objExcel->addRow(array($DEPT_NAME,$USER_NAME,$PRIV_NAME,$USER_PRIV_OTHER_NAME,$HOUR,$SEX,$TEL_NO_DEPT,$TEL_NO,$MOBIL_NO,$EMAIL));
}
$objExcel->Save();
?>