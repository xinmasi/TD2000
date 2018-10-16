<?
include_once("inc/auth.inc.php");
foreach($_GET as $k=>$v)
{
    $$k=$v;
}
$INTEGRAL_TYPE=$_GET["INTEGRAL_TYPE"];
$ITEM_TYPE=$_GET["ITEM_TYPE"];
$BEGIN_TIME=$_GET["begin"];
$END_TIME=$_GET["end"];
$USER_ID=$_GET["USER_ID"];

$HTML_PAGE_TITLE = _("积分结果");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");
if($USER_ID=="" || $USER_ID == null)
{
    Message(_("警告"),_("请选择用户"));
    Button_Back();
	exit;
}
$result_arr=array();
$count_oa=$count_in=$count=0;


$where_str_oa=" and USER_ID='$USER_ID' ";
$where_str_in=" and USER_ID='$USER_ID' ";
if($BEGIN_TIME!="")
{
    $where_str_oa.=" and CREATE_TIME>='$BEGIN_TIME.00:00:00' ";
    $where_str_in.=" and INTEGRAL_TIME>='$BEGIN_TIME.00:00:00' ";
}
if($END_TIME!="")
{
    $where_str_oa.=" and CREATE_TIME<='$END_TIME.23:59:59' ";
    $where_str_in.=" and INTEGRAL_TIME<='$END_TIME.23:59:59' ";
}
$has_oa=0;
$has_in=0;
if($INTEGRAL_TYPE!="")
{
    if(find_id($INTEGRAL_TYPE, 1) || find_id($INTEGRAL_TYPE, 2))
        $has_oa=1;
        if($INTEGRAL_TYPE==0 || $INTEGRAL_TYPE==3)
        {
            $has_in=1;
            if($INTEGRAL_TYPE==0)
                $where_str_in.=" and INTEGRAL_TYPE=0 ";
            else if($INTEGRAL_TYPE==3)
                $where_str_in.=" and INTEGRAL_TYPE=3 ";
            else
                $where_str_in.=" (INTEGRAL_TYPE=3 or INTEGRAL_TYPE=0) ";
        }
}
else
{
    $has_oa=1;
    $has_in=1;
}
//查询OA计算项目
if($has_oa==1)
{
    $sum_oa_tem=array();
    $sql_oa="select * from hr_integral_oa where 1=1 ".$where_str_oa."";

    $cursor_oa=db_query($sql_oa, TD::conn());
    while($r_oa=mysql_fetch_assoc($cursor_oa))
    {
        $USER_ID=$r_oa["USER_ID"];
        $USER_NAME=trim(GetUserNameById($USER_ID),",");
        $CREATE_TIME=$r_oa["CREATE_TIME"];
        $RECORD_ID=$r_oa["RECORD_ID"];
        foreach($r_oa as $key => $value)
        {
            if($value==0) continue;
            if(in_array($key,array("RECORD_ID","USER_ID","SUM","MEMO","CREATE_TIME"))) continue;
            if(in_array($key,array("RS001","RS002","RS003","RS004","RS005")))
                $OA_TYPE=2;
            else
                $OA_TYPE=1;
            $ITEM_NAME="";
            if($OA_TYPE == 1)
                $ITEM_NAME=getItemNameByNo($key);
            else
                $ITEM_NAME=$RS_ARR[$key];

            $result_arr[$count]["INTEGRAL_TYPE"]="OA计算项目";
            $result_arr[$count]["ITEM_NAME"]=$ITEM_NAME;
            $result_arr[$count]["USER_NAME"]=$USER_NAME;
            $result_arr[$count]["INTEGRAL_TIME"]=$CREATE_TIME;
            $result_arr[$count]["REASON"]="";
            $result_arr[$count]["POINT"]=$value;
            $count_in++;
            $count++;
        }
    }
}
//查询手动记录的积分项目
if($has_in==1)
{
    $sum_in_tem=array();

    $selected_item_id="";
    if($ITEM_TYPE!="")
    {
        $where_str_sql=" and type_id='$ITEM_TYPE' ";
        $sql="select item_id from hr_integral_item where 1=1".$where_str_sql;
        $cur=exequery(TD::conn(), $sql);
        while($r=mysql_fetch_array($cur))
        {
            $selected_item_id.=$r["item_id"].",";
        }
    }
    if($ITEM_ID!="")
        $selected_item_id.=$ITEM_ID;
    if($selected_item_id!="")
        $where_str_in.=" and find_in_set(item_id,'$selected_item_id') ";

    $sql_in="select * from hr_integral_data where 1=1 ".$where_str_in;
    $cursor_in=db_query($sql_in, TD::conn());
    while($r_in=mysql_fetch_array($cursor_in))
    {
        if($r_in["INTEGRAL_DATA"]==0) continue;
    	$INTEGRAL_TYPE1=$r_in["INTEGRAL_TYPE"];
    	$USER_ID=$r_in["USER_ID"];
    	$USER_NAME=trim(GetUserNameById($USER_ID),",");
    	$INTEGRAL_REASON=$r_in["INTEGRAL_REASON"];
    	$INTEGRAL_DATA=$r_in["INTEGRAL_DATA"];
    	$CREATE_PERSON=$r_in["CREATE_PERSON"];
    	$INTEGRAL_TIME=$r_in["INTEGRAL_TIME"];
    	$ITEM_NO=$r_in["ITEM_ID"];
    	$ITEM_NAME = $ITEM_NO==0 ? _("未定义") : getItemName($ITEM_NO);

    	if($INTEGRAL_TYPE1 == '3')
    	    $INTEGRAL_TYPE1=_("自定义项积分录入");
    	else
    	    $INTEGRAL_TYPE1=_("未定义项积分录入");

    	$result_arr[$count]["INTEGRAL_TYPE"]=$INTEGRAL_TYPE1;
    	$result_arr[$count]["ITEM_NAME"]=$ITEM_NAME;
    	$result_arr[$count]["USER_NAME"]=$USER_NAME;
    	$result_arr[$count]["INTEGRAL_TIME"]=$INTEGRAL_TIME;
    	$result_arr[$count]["REASON"]=$INTEGRAL_REASON;
    	$result_arr[$count]["POINT"]=$INTEGRAL_DATA;
    	$count_in++;
    	$count++;
    }
}

$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
	$start=0;
?>
<script src="/module/DatePicker/WdatePicker.js"></script>
<body class="bodycolor" >

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"><?=trim(getUserNameById($USER_ID),",")?>-<?=_("积分详细")?></span>
    </td>
  </tr>
</table>

<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
   <tr>
      <td valign="bottom" class="small1"><?=sprintf(_("共%s条记录"), '<span class="big4">&nbsp;'.$count.'</span>&nbsp;')?></td>
      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$count,$ITEMS_IN_PAGE)?></td>
   </tr>
</table>

<?
if($count > 0)
{
?>
	<table class="TableList" width="95%" align="center">
    <thead class="TableHeader">
    <tr>
        <td nowrap align="center"><?=_("积分类型")?></td>
        <td nowrap align="center"><?=_("积分项目")?></td>
        <td nowrap align="center"><?=_("积分人")?></td>
        <td nowrap align="center"><?=_("积分时间")?></td>
        <td nowrap align="center"><?=_("积分理由")?></td>
        <td nowrap align="center"><?=_("分值")?></td>
    </tr>
    </thead>
<?
    $end=$start+$ITEMS_IN_PAGE;
    $USER_COUNT=0;
    foreach($result_arr as $key => $value)
    {

        $USER_COUNT++;
        if($USER_COUNT > $end) break;
        if($USER_COUNT< $start+1 || $USER_COUNT > $end)
            continue;

        if($key%2==1)
           $TableLine="TableLine1";
        else
           $TableLine="TableLine2";

	?>
	    <tr class="<?=$TableLine?>" >
	      <td nowrap align="center"><?=$value["INTEGRAL_TYPE"]?></td>
	      <td nowrap align="center"><?=$value["ITEM_NAME"]?></td>
	      <td nowrap align="center"><?=$value["USER_NAME"]?></td>
	      <td nowrap align="center"><?=$value["INTEGRAL_TIME"]?></td>
	      <td align="center"><?=$value["REASON"]?></td>
	      <td nowrap align="center"><?=$value["POINT"]?><?=_("分")?></td>
	    </tr>
<?
    }
?>
    </table>
<?
}
else
    Message("",_("暂无此人所选类型积分记录"));
?>
<br />
<div align="center">
 <input type="button"  value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
</div>

</body>
</html>