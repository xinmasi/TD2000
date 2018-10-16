<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
while(list($DEPT_ID1, $DEPT) = each($DEPARTMENT_ARRAY))
{
   if($DEPT["DEPT_PARENT"]!=$PARENT_ID)
      continue;
   $DEPT_ID = $DEPT_ID1;
   $DEPT_NAME1=$DEPT["DEPT_NAME"];
   break;
}

$HTML_PAGE_TITLE = _("员工薪酬基数设置");

$MENU_LEFT_CONFIGS['href'] = 'wage_list.php?DEPT_ID='.$DEPT_ID.'&DEPT_NAME='.urlencode($DEPT_NAME1);
$MENU_LEFT_CONFIGS['framename'] = 'wage_info';
$MENU_LEFT_CONFIGS['offset'] = '200px';
include_once("left_tree.php");
?>