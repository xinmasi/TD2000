<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$query = "select * from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
	$PROJ_NUM = $ROW["PROJ_NUM"];
	$PROJ_NAME = $ROW["PROJ_NAME"];
	$PROJ_TYPE = $ROW["PROJ_TYPE"];
	$PROJ_DEPT = $ROW["PROJ_DEPT"];
	$PROJ_START_TIME = $ROW["PROJ_START_TIME"];
	$PROJ_END_TIME = $ROW["PROJ_END_TIME"];
	$PROJ_DESCRIPTION = $ROW["PROJ_DESCRIPTION"];
	$PROJ_OWNER = $ROW["PROJ_OWNER"];
	$PROJ_OWNER = $ROW["PROJ_OWNER"];
  $PROJ_USER = $ROW["PROJ_USER"];
  $PROJ_PRIV = $ROW["PROJ_PRIV"];
}

if($PROJ_END_TIME && $PROJ_START_TIME)
  $DIFF_DAY = floor((strtotime($ROW["PROJ_END_TIME"]) - strtotime($ROW["PROJ_START_TIME"]))/86400);

$query = "select USER_NAME from USER WHERE USER_ID='$PROJ_OWNER'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
   $PROJ_OWNER_NAME = $ROW["USER_NAME"];

if($PROJ_DEPT)
{
	if($PROJ_DEPT=="ALL_DEPT")
	   $PROJ_DEPT_NAME=_("全体部门");
	else
	{
  	$query = "select DEPT_NAME from DEPARTMENT WHERE FIND_IN_SET(DEPT_ID,'$PROJ_DEPT')";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
       $PROJ_DEPT_NAME .= $ROW["DEPT_NAME"].",";
  }
} 

  $PROJ_USER_ARRAY = explode("|",$PROJ_USER);
  $PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);
?>

<table class="TableList" width="95%" align="center">
	<tr>
  	<td nowrap class="TableContent"><?=_("项目编号：")?></td>
  	<td class="TableData"><?=$PROJ_NUM?></td>
  </tr>
	<tr>
  	<td nowrap class="TableContent" width=100><?=_("项目名称：")?></td>
  	<td class="TableData"> <?=$PROJ_NAME?></td>  	  	
  </tr>
	<tr>
  	<td nowrap class="TableContent" width=100><?=_("创建者：")?></td>
  	<td class="TableData"> <?=$PROJ_OWNER_NAME?></td>  	  	
  </tr>
  <tr>
    <td nowrap class="TableContent"><?=_("项目类别：")?></td>
  	<td class="TableData"><?=get_code_name($PROJ_TYPE,"PROJ_TYPE")?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("参与部门：")?></td>
  	<td class="TableData"><?=$PROJ_DEPT_NAME?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("项目计划周期：")?></td>
  	<td class="TableData"><?=$PROJ_START_TIME?>&nbsp;<?=_("至")?>&nbsp;<?=$PROJ_END_TIME?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("项目工期：")?></td>
  	<td class="TableData"><?=$DIFF_DAY?>&nbsp;<?=_("天")?></td>
  </tr>
  <tr>
    <td nowrap class="TableContent"><?=_("项目描述：")?></td>
  	<td class="TableData"><?=$PROJ_DESCRIPTION?></td>
  </tr>
  <tr>
    <td nowrap class="TableContent"><?=_("项目成员信息：")?></td>
    <td class="TableData">
<?
  for($i=0; $i < count($PROJ_PRIV_ARRAY); $i++)
  {
  	 if($PROJ_PRIV_ARRAY[$i]=='')
  	   continue;  	 
  	 $PROJ_USER_NAME="";
  	 $PRIV_NAME = get_code_name($PROJ_PRIV_ARRAY[$i],"PROJ_ROLE");
  	 $query = "select USER_NAME from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER_ARRAY[$i]')";
     $cursor = exequery(TD::conn(), $query);
     while($ROW = mysql_fetch_array($cursor))
  	    $PROJ_USER_NAME .= $ROW["USER_NAME"].",";
  	    
  	 echo '['.$PRIV_NAME.']: '.$PROJ_USER_NAME."<br>";
  }
?> 
  </td> 
  </tr>
</table>
</body>
</html>
	