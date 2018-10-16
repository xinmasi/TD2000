<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
if(!isset($TYPE))
   $TYPE="0";
if(!isset($PAGE_SIZE))
   $PAGE_SIZE =10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("固定资产查询");
include_once("inc/header.inc.php");
?>


<script>
function check_all()
{
	for (i=0;i<document.getElementsByName("source_select").length;i++)
	{
   	if(document.getElementsByName("allbox")[0].checked)
      	document.getElementsByName("source_select").item(i).checked=true;
		else
      	document.getElementsByName("source_select").item(i).checked=false;
 	}
	if(i==0)
	{
		if(document.getElementsByName("allbox")[0].checked)
      	document.getElementsByName("source_select").checked=true;
		else
      	document.getElementsByName("source_select").checked=false;
	}
}
function check_one(el)
{
	if(!el.checked)
		document.getElementsByName("allbox")[0].checked=false;
}
function get_checked(el)
{
	delete_str="";
	for(i=0;i<document.getElementsByName("source_select").length;i++)
	{
		el=document.getElementsByName("source_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
   }
	if(i==0)
	{
		el=document.getElementsByName("source_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
	}
   return delete_str;
}
function delete_item()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("要删除短消息，请至少选择其中一条。")?>");
		return;
	}
	msg='<?=_("确认要删除所选短消息吗？")?>';
	if(window.confirm(msg))
	{
		url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
    	location=url;
  	}
}
function cptl_detail(CPTL_ID)
{
	URL="cptl_detail.php?CPTL_ID="+CPTL_ID;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_notify","height=470,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function dcr_cptl(CPTL_ID)
{
	URL="decrease/?CPTL_ID="+CPTL_ID;
	myleft=(screen.availWidth-200)/2;
	window.open(URL,"read_notify","height=150,width=300,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=250,left="+myleft+",resizable=yes");
}
function dpct_detail(CPTL_ID)
{
	URL="dpct_detail.php?CPTL_ID="+CPTL_ID;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_notify","height=400,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function del_detail(CPTL_ID)
{
	URL="delete.php?start=<?=$start?>&CPTL_ID="+CPTL_ID;
	if(window.confirm("<?=_("确认要删除该资产吗？")?>"))
   	location=URL;
}
</script>

<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" width="24" height="24"><span class="big3"> <?=_("固定资产查询结果")?></span></td>
		</tr>
	</table>
<?
$CUR_DATE=date("Y-m-d",time());
  //----------- 合法性校验 ---------
if($CREATE_DATE_MIN!="")
{
	$TIME_OK=is_date($CREATE_DATE_MIN);
	if(!$TIME_OK)
	{
		Message(_("错误"),sprintf(_("增加日期的开始日期格式不对，应形如 %s"),$CUR_DATE));
		Button_Back();
		exit;
    }
 }
 if($CREATE_DATE_MAX!="")
 {
    $TIME_OK=is_date($CREATE_DATE_MAX);
    if(!$TIME_OK)
    {
		Message(_("错误"),sprintf(_("增加日期的结束日期格式不对，应形如 %s"),$CUR_DATE));
		Button_Back();
      exit;
    }
 }
if($DCR_DATE_MIN!="")
{
	$TIME_OK=is_date($DCR_DATE_MIN);
   if(!$TIME_OK)
	{ 
		Message(_("错误"),sprintf(_("减少日期的开始日期格式不对，应形如 %s"),$CUR_DATE));
      Button_Back();
      exit;
    }
}
if($DCR_DATE_MAX!="")
{
	$TIME_OK=is_date($DCR_DATE_MAX);
	if(!$TIME_OK)
   {
    	Message(_("错误"),sprintf(_("减少日期的结束日期格式不对，应形如 %s"),$CUR_DATE));
      Button_Back();
      exit;
   }
}
if($FROM_YYMM_MIN!="")
{
	$TIME_OK=is_date($FROM_YYMM_MIN);
   if(!$TIME_OK)
   { 
    	Message(_("错误"),sprintf(_("启用日期的开始日期格式不对，应形如 %s"),$CUR_DATE));
      Button_Back();
      exit;
   }
}
if($FROM_YYMM_MAX!="")
{
    $TIME_OK=is_date($FROM_YYMM_MAX);
    if(!$TIME_OK)
    { 
    	Message(_("错误"),sprintf(_("启用日期的结束日期格式不对，应形如 %s"),$CUR_DATE));
      Button_Back();
      exit;
    }
 }
 //------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($CPTL_NO!="")
	$CONDITION_STR.=" and CPTL_NO like '%".$CPTL_NO."%'";
if($CPTL_NAME!="")
	$CONDITION_STR.=" and CPTL_NAME like '%".$CPTL_NAME."%'";
if($TYPE_ID!="")
	$CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
if($DEPT_ID!="")
	$CONDITION_STR.=" and CP_CPTL_INFO.DEPT_ID='$DEPT_ID'";
if($CPTL_KIND!="")
	$CONDITION_STR.=" and CPTL_KIND='$CPTL_KIND'";
if($PRCS_ID!="")
   $CONDITION_STR.=" and PRCS_ID='$PRCS_ID'";
if($DCR_PRCS_ID!="")
   $CONDITION_STR.=" and DCR_PRCS_ID='$DCR_PRCS_ID'";
if($FINISH_FLAG!="")
   $CONDITION_STR.=" and FINISH_FLAG='$FINISH_FLAG'";
if($CPTL_VAL_MIN!="")
   $CONDITION_STR.=" and CPTL_VAL>='$CPTL_VAL_MIN'";
if($CPTL_VAL_MAX!="")
   $CONDITION_STR.=" and CPTL_VAL<='$CPTL_VAL_MAX'";
if($CPTL_BAL_MIN!="")
   $CONDITION_STR.=" and CPTL_BAL>='$CPTL_BAL_MIN'";
if($CPTL_BAL_MAX!="")
	$CONDITION_STR.=" and CPTL_BAL<='$CPTL_BAL_MAX'";
if($CREATE_DATE_MIN!="")
   $CONDITION_STR.=" and CREATE_DATE>='$CREATE_DATE_MIN'";
if($CREATE_DATE_MAX!="")
   $CONDITION_STR.=" and CREATE_DATE<='$CREATE_DATE_MAX'";
if($DCR_DATE_MIN!="")
   $CONDITION_STR.=" and DCR_DATE>='$DCR_DATE_MIN'";
if($DCR_DATE_MAX!="")
   $CONDITION_STR.=" and DCR_DATE<='$DCR_DATE_MAX'";
if($FROM_YYMM_MIN!="")
   $CONDITION_STR.=" and FROM_YYMM>='$FROM_YYMM_MIN'";
if($FROM_YYMM_MAX!="")
   $CONDITION_STR.=" and FROM_YYMM<='$FROM_YYMM_MAX'";
if($TO_NAME!="")
   $CONDITION_STR.=" and KEEPER like '%".$TO_NAME."%'";
if($REMARK!="")
   $CONDITION_STR.=" and REMARK like '%".$REMARK."%'";
?>
<?
if(!isset($TOTAL_ITEMS))
{
	$query = "SELECT count(*) from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")." order by DEPT_ID,CREATE_DATE,CPTL_NO";
	$cursor= exequery(TD::conn(),$query);
	$TOTAL_ITEMS=0;
	if($ROW=mysql_fetch_array($cursor))
		$TOTAL_ITEMS=$ROW[0];
}
$query="select * from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")." order by CPTL_ID desc";
$query .= " limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$CPTL_COUNT=0;
$CPTL_VAL_COUNT=0;
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
</tr>
</table>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $CPTL_COUNT++;
   if($CPTL_COUNT>200)
      break;
   $CPTL_ID=$ROW["CPTL_ID"];
   $CPTL_NO=$ROW["CPTL_NO"];
   $CPTL_NAME=$ROW["CPTL_NAME"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $CPTL_VAL=$ROW["CPTL_VAL"];
   $CPTL_BAL=$ROW["CPTL_BAL"];
   $DPCT_YY=$ROW["DPCT_YY"];
   $MON_DPCT=$ROW["MON_DPCT"];
   $SUM_DPCT=$ROW["SUM_DPCT"];
   $CPTL_KIND=$ROW["CPTL_KIND"];
   $PRCS_ID=$ROW["PRCS_ID"];
   $FINISH_FLAG=$ROW["FINISH_FLAG"];
   $CREATE_DATE=$ROW["CREATE_DATE"];
   $FROM_YYMM=$ROW["FROM_YYMM"];
   $CPTL_VAL_COUNT+=$CPTL_VAL;
   if($CPTL_KIND=="01")
      $CPTL_KIND_DESC=_("资产");
   else if($CPTL_KIND=="02")
      $CPTL_KIND_DESC=_("费用");
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
       $DEPT_NAME=$ROW1["DEPT_NAME"];
   if($CPTL_COUNT==1)
   {
?>
<table class="TableList" width="90%" align="center">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("资产编号")?></td>
      <td nowrap align="center"><?=_("资产名称")?></td>
      <td nowrap align="center"><?=_("资产原值")?></td>
      <td nowrap align="center"><?=_("资产性质")?></td>
      <td nowrap align="center"><?=_("增加类型")?></td>
      <td nowrap align="center"><?=_("增加日期")?><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
   if($CPTL_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
<tr class="<?=$TableLine?>">
	<td nowrap  width=40><input type="checkbox" name="source_select" value="<?=$CPTL_ID?>" onClick="check_one(self);"></td>
   <td nowrap align="center"><?=$DEPT_NAME?></td>
   <td nowrap align="center"><?=$CPTL_NO?></td>
   <td><a href="javascript:cptl_detail('<?=$CPTL_ID?>');"><?=$CPTL_NAME?></a></td>
   <td nowrap align="center"><?=$CPTL_VAL?></a></td>
	<td nowrap align="center"><?=$CPTL_KIND_DESC?></td>
	<td nowrap align="left"><?=$PRCS_LONG_DESC?></td>
	<td nowrap align="center"><?=$CREATE_DATE?></td>
	<td nowrap align="center">
      <a href="javascript:cptl_detail('<?=$CPTL_ID?>');"> <?=_("详情")?></a>
      <a href="modify.php?CPTL_ID=<?=$CPTL_ID?>"> <?=_("修改")?></a>
      <a href="keep.php?CPTL_ID=<?=$CPTL_ID?>"> <?=_("维护")?></a>
      <a href="javascript:dcr_cptl('<?=$CPTL_ID?>');"> <?=_("减少")?></a>
      <a href="javascript:dpct_detail('<?=$CPTL_ID?>');"> <?=_("折旧记录")?></a>
      <a href="javascript:del_detail('<?=$CPTL_ID?>');"> <?=_("删除")?></a>
	</td>
</tr>
<?
}
if($CPTL_COUNT==0)
{
   Message("",_("无符合条件的固定资产信息"));
   Button_Back();
   exit;
}
else
{
?>
    <tr class="TableControl">
      <td nowrap align="center"><?=_("合计：")?></td>
      <td nowrap colspan="3"></td>
      <td nowrap align="center"><?=$CPTL_VAL_COUNT?></a></td>
      <td nowrap colspan="4"</td>
    </tr>
    <tr class="TableControl">
		<td colspan="9">
    		<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
    		<a href="javascript:delete_item();" title="<?=_("删除所选信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选信息")?></a>&nbsp;
		</td>
	</tr>
</table>
<br>
<div align="center">
<input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
</div>
<?
}
?>
</body>
</html>
