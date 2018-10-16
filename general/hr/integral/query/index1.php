<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");

$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
    $start=0;
$search_str="";
foreach($_GET as $k=>$v)
{
    $$k=$v;
    if($k!="TOTAL_ITEMS" && $k!="PAGE_SIZE" && $k!="start")
        $search_str.="&$k=".$v;
}
$FROM_DEPT_ID=$DEPT_ID;//存部门
$NEW_DEPT_ID=intval($NEW_DEPT_ID);
$WHERE_STR = "";
if($TO_ID!="")
	$WHERE_STR.=" and find_in_set(USER_ID,'$TO_ID') ";

if($DEPT_ID!="")//部门
 {
    if($DEPT_ID!="0")// 查询全部人员
    {
       $DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
       $WHERE_STR.=" and DEPT_ID in ($DEPT_ID_CHILD)";
    }
    else
    {
       $WHERE_STR.=" and DEPT_ID='0'";
    }
 }
if($SEX!="")// 性别
    $WHERE_STR.=" and SEX='$SEX'";
if($USER_PRIV!="")//角色
    $WHERE_STR.=" and USER_PRIV='$USER_PRIV'";
$limit_str=" limit $start,$ITEMS_IN_PAGE";
$query="select user_id from user where 1=1 and dept_id!=0".$WHERE_STR;
$cursor=exequery(TD::conn(), $query);
$user_ids="";
while($row=mysql_fetch_array($cursor)){
    $user_ids.=$row["user_id"].",";
}

$THE_FOUR_VAR = "TO_ID=$TO_ID&DEPT_ID=$DEPT_ID&SEX=$SEX&USER_PRIV=$USER_PRIV&INTEGRAL_TYPE=$INTEGRAL_TYPE&ITEM_TYPE=$ITEM_TYPE&begin=$begin&end=$end&"."start";

$HTML_PAGE_TITLE = _("积分结果");
ob_end_clean();
include_once("inc/header.inc.php");
?>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
function show_reader(USER_ID)
{
 URL="point_specific.php?USER_ID="+USER_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_notify","height=350,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>


<body class="bodycolor" >
  
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"> <?=_("查询结果")?></span>
    </td>
  </tr>
  
</table>
<?
$rank_info = array();
$rank_info=get_integrals($user_ids,$begin,$end,$INTEGRAL_TYPE,$ITEM_TYPE);
$count=count($rank_info);
?>
<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
   <tr>
      <td valign="bottom" class="small1"><?=sprintf(_("共%s条记录"), '<span class="big4">&nbsp;'.$count.'</span>&nbsp;')?></td>
      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$count,$ITEMS_IN_PAGE,$THE_FOUR_VAR)?></td>
   </tr>
</table>
<?

 $end=$start+$ITEMS_IN_PAGE;
 $USER_COUNT=0;
foreach($rank_info as $rank_key=>$rank_value)
 {
 	$USER_COUNT++;
 	
 	if($USER_COUNT > $end) break;
 	
 	if($USER_COUNT< $start+1 || $USER_COUNT > $end)
 		continue;
 	$USER_ID=$rank_key;
 	$SUMALL=round($rank_value,2);
 	
 	$query_user="select USER_NAME,DEPT_ID,USER_PRIV,SEX from USER where USER_ID='$USER_ID'";
 	$cursor_user=exequery(TD::conn(),$query_user);
 	if($ROW_USER=mysql_fetch_array($cursor_user))
 	{
 		$USER_NAME=$ROW_USER["USER_NAME"];
 		$DEPT_ID1=$ROW_USER["DEPT_ID"];
 		$USER_PRIV=$ROW_USER["USER_PRIV"];
 		$SEX=$ROW_USER["SEX"];
 	}
 	
    if($DEPT_ID1 != '0' && $DEPT_ID1 !="")
    {
    	$query1 = "SELECT * from DEPARTMENT where DEPT_ID='".$DEPT_ID1."'";
	    $cursor1= exequery(TD::conn(),$query1);
	    if($ROW=mysql_fetch_array($cursor1))
	      $DEPT_NAME=$ROW["DEPT_NAME"];
	    else
	      $DEPT_NAME=_("离职人员/外部人员");
    }
    else
    		$DEPT_NAME=_("离职人员/外部人员");

    $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
         $USER_PRIV=$ROW["PRIV_NAME"];

    $DEPT_LONG_NAME=dept_long_name($DEPT_ID1);
    if($USER_COUNT==$start+1)
    {
?>
<table class="TableList" width="100%">
<?
    }
    if($USER_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>" title="<?=$TR_TITLE?>" style="<?=$STYLE_STR?>">
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
      <td nowrap align="center"><?=$USER_PRIV?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$SUMALL?><?=_("分")?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="show_reader('<?=$USER_ID?>')"><?=_("查看详细")?></a>
      </td>
    </tr>
<?
 }
 if($USER_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>  
      <td nowrap align="center"><?=_("角色")?></td>    
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("总积分数")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("没有符合条件的数据"));
?>

<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">
</div>

</body>
</html>
