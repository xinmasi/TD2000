<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query = "select * from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
	 $PROJ_USER = $ROW["PROJ_USER"];
   $PROJ_PRIV = $ROW["PROJ_PRIV"];
   $PROJ_USER_ARRAY = explode("|",$PROJ_USER);
   $PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);
}

$HTML_PAGE_TITLE = _("项目成员信息");
include_once("inc/header.inc.php");
?>


<style>
span{cursor:pointer;text-decoration:underline;}
</style>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"/>
	<span class="big3"> <?=_("项目成员信息")?></span>
	<td></tr>
</table>
<table class="TableList" width="100%" align="left" topmargin="5">
<?
  $DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
  for($i=0; $i < count($PROJ_PRIV_ARRAY); $i++)
  {
  	 if($PROJ_PRIV_ARRAY[$i]=='')
  	   continue;  	 
  	 $PROJ_USER_NAME="";
  	 $PRIV_NAME = get_code_name($PROJ_PRIV_ARRAY[$i],"PROJ_ROLE");
  	 $query = "select USER_NAME,DEPT_ID from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER_ARRAY[$i]')";
     $cursor = exequery(TD::conn(), $query);
     while($ROW = mysql_fetch_array($cursor))
  	    $PROJ_USER_NAME .= '<span title="'.$DEPARTMENT_ARRAY[$ROW["DEPT_ID"]]["DEPT_LONG_NAME"].'">'.$ROW["USER_NAME"].'</span>,';
  	    
  	 echo '
  	      <tr>
            <td class="TableContent" nowrap width=120>'.$PRIV_NAME.'：</td>
            <td class="TableData">'.$PROJ_USER_NAME.'</td>  	
          </tr>';
  }
?>
</table>
</body>
</html>
	