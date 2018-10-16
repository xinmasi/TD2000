<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
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

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
function openWindow(url, width, height, target, params){

    var left = (screen.availWidth  - width)/2;
    var top  = (screen.availHeight - height)/2;


    var paramStr = "";
    if(typeof params == "undefined" || params == null){
        paramStr = "toolbar="     + "no,"
                 + "location="    + "no,"
                 + "status="      + "no,"
                 + "directories=" + "no,"
                 + "menubar="     + "no,"
                 + "scrollbars="  + "yes,"
                 + "resizable="   + "yes,"
                 + "left="        + left  + ", "
                 + "top="         + top   + ", "
                 + "width="       + width + ", "
                 + "height="      + height;
    }else{
        paramStr = "toolbar="     + ((typeof params['toolbar']  == "undefined" || params['toolbar']  == "") ? "no" : params['toolbar'])  + ","
                 + "location="    + ((typeof params['location'] == "undefined" || params['location'] == "") ? "no" : params['location']) + ","
                 + "status="      + ((typeof params['status']   == "undefined" || params['status']   == "") ? "no" : params['status'])   + ","
                 + "directories=" + ((typeof params['directories'] == "undefined" || params['directories'] == "") ? "no" : params['directories']) + ","
                 + "menubar="     + ((typeof params['menubar'] == "undefined"  || params['menubar'] == "") ? "no" : params['menubar']) + ","
                 + "scrollbars="  + ((typeof params['scrollbars'] == "undefined" || params['scrollbars'] == "") ? "yes" : params['menubar']) + ","
                 + "resizable="   + ((typeof params['resizable'] == "undefined"  || params['resizable'] == "") ? "yes" : params['resizable']) + ","
                 + "left="        + left  + ", "
                 + "top="         + top   + ", "
                 + "width="       + width + ", "
                 + "height="      + height;
    }
    if(typeof target != 'undefined'){
        window.open(url, target, paramStr);
    }else{
        window.open(url, "", paramStr);
    }
}
function ad_query()
{
  URL="hd_query.php";
  window.location=URL;
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='source_select']").attr("checked","true");
        }
        else
        {
            jQuery("[name='source_select']").removeAttr("checked");
        }    
    });
    jQuery("[name='source_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");        
    });
});

function get_checked()
{
	delete_str="";
	jQuery("input[name='source_select']:checkbox").each(function(){
	    if(jQuery(this).attr("checked"))
	    {
	        delete_str +=jQuery(this).val()+',';
	    }
	})
   return delete_str;
}
function delete_item()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("要删除固定资产，请至少选择其中一条。")?>");
		return;
	}
	msg='<?=_("确认要删除所选固定资产吗？")?>';
	if(window.confirm(msg))
	{
		url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
    	location=url;
  	}
}
function cptl_detail(CPTL_ID)
{
	URL="cptl_detail.php?CPTL_ID="+CPTL_ID;
	myleft=screen.availWidth/2-300;
	window.open(URL,"read_notify","height=470,width=600,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
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
function order_by(field,asc_desc) {
	URL="index1.php?CPTL_NO=<?=$CPTL_NO?>&CPTL_NAME=<?=urlencode($CPTL_NAME)?>&TYPE_ID=<?=$TYPE_ID?>&DEPT_ID=<?=$DEPT_ID?>&CPTL_KIND=<?=$CPTL_KIND?>&PRCS_ID=<?=$PRCS_ID?>&DCR_PRCS_ID=<?=$DCR_PRCS_ID?>&FINISH_FLAG=<?=$FINISH_FLAG?>&CPTL_VAL_MIN=<?=$CPTL_VAL_MIN?>&CPTL_VAL_MAX=<?=$CPTL_VAL_MAX?>&CREATE_DATE_MIN=<?=$CREATE_DATE_MIN?>&CREATE_DATE_MAX=<?=$CREATE_DATE_MAX?>&DCR_DATE_MIN=<?=$DCR_DATE_MIN?>&DCR_DATE_MAX=<?=$DCR_DATE_MAX?>&FROM_YYMM_MIN=<?=$FROM_YYMM_MIN?>&FROM_YYMM_MAX=<?=$FROM_YYMM_MAX?>&TO_NAME=<?=$TO_NAME?>&ORDER_FIELD="+ field +"&ASC_DESC="+asc_desc;
	window.location=URL;
}
</script>

<body class="bodycolor">
	
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
	$CONDITION_STR.=" and CP_CPTL_INFO.TYPE_ID='$TYPE_ID'";
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

//...........
$keeper_name = $TO_NAME;
$keeper_id = "";
if($TO_NAME!="")
{
    $query1="select USER_ID from USER where USER_NAME='$TO_NAME'";
    $cursor1= exequery(TD::conn(),$query1);
    $NUM=mysql_num_rows($cursor1);
    if($ROWs=mysql_fetch_array($cursor1))
    {
        $keeper_id = $ROWs['USER_ID'];
    }
}

//...................
if($keeper_name!="" && $keeper_id!="")
{
   $CONDITION_STR.=" and (KEEPER like '%".$keeper_name."%' or KEEPER like '%".$keeper_id."%') ";
}
else if($keeper_name!="")
{
   $CONDITION_STR.=" and KEEPER like '%".$keeper_name."%' ";
}
if($REMARK!="")
   $CONDITION_STR.=" and CP_CPTL_INFO.REMARK like '%".$REMARK."%'";

$WHERE_CLAUSE = " where 1= 1 " . $CONDITION_STR;
$ORDER_CLAUSE = " order by CPTL_ID desc ";

$ORDER_STR = " order by CPTL_ID"; 

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

if($ORDER_FIELD=="DEPT_ID") {
	$ORDER_STR = " order by DEPT_ID";
}

if($ORDER_FIELD=="TYPE_ID") {
	$ORDER_STR = " order by TYPE_ID";
}

if($ASC_DESC=="")
   $ASC_DESC="1";

if($ASC_DESC=="1")
   $ORDER_STR .= " DESC";
else
   $ORDER_STR .= " ASC";

$LIMIT_CLAUSE = " limit $start,$PAGE_SIZE";

?>
<fieldset style="width:90%;padding-bottom:5px;" align="center">

  <legend class="small" align=left>
      <b><?=_("快捷操作")?></b>
  </legend>
<table  class="TableList" align="right" width="100%">
  <form action="index1.php?<?=$WHERE?>"  method="post" name="form1">
  <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产编号：")?></td>
      <td class="TableData" style="text-align:left">
        <input type="text" name="CPTL_NO" size="20" maxlength="100" class="BigInput" value="<?=$CPTL_NO?>">
      </td>

	  <td nowrap class="TableData" width="100"> <?=_("资产名称：")?></td>
      <td class="TableData" style="text-align:left">
        <input type="text" name="CPTL_NAME" size="30" style="width: 228px;" maxlength="200" class="BigInput" value="<?=$CPTL_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产类别：")?></td>
      <td class="TableData" style="text-align:left">
      <select name="TYPE_ID" class="BigSelect">
        <option value="" <? if(!isset($TYPE_ID))echo "selected";?>><?=_("所有类别")?></option>
        <option value="0" <? if($TYPE_ID=="0")echo "selected";?>><?=_("未指定类别")?></option>
<?
 $THE_FOUR_VAR = "CPTL_NO=$CPTL_NO&CPTL_NAME=$CPTL_NAME&TYPE_ID=$TYPE_ID&DEPT_ID=$DEPT_ID&CPTL_KIND=$CPTL_KIND&PRCS_ID=$PRCS_ID&DCR_PRCS_ID=$DCR_PRCS_ID&FINISH_FLAG=$FINISH_FLAG&CPTL_VAL_MIN=$CPTL_VAL_MIN&CPTL_VAL_MAX=$CPTL_VAL_MAX&CREATE_DATE_MIN=$CREATE_DATE_MIN&CREATE_DATE_MAX=$CREATE_DATE_MAX&DCR_DATE_MIN=$DCR_DATE_MIN&DCR_DATE_MAX=$DCR_DATE_MAX&FROM_YYMM_MIN=$FROM_YYMM_MIN&FROM_YYMM_MAX=$FROM_YYMM_MAX&TO_NAME=$TO_NAME&REMARK=$REMARK&"."start";  
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $QUERY_TYPE_ID=$ROW["TYPE_ID"];
    $QUERY_TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$QUERY_TYPE_ID?>" <? if($TYPE_ID==$QUERY_TYPE_ID) echo "selected"; ?>><?=$QUERY_TYPE_NAME?></option>
<?
 }
?>
	  </select>
      </td>
	   <td nowrap class="TableData" width="100"> <?=_("所属部门：")?></td>
      <td class="TableData" style="text-align:left">
      <select name="DEPT_ID" class="BigSelect">
        <option value=""><?=_("所有部门")?></option>
        <option value="0"><?=_("未指定部门")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,0);
?>
      </select>
      </td>
    </tr>
   <tr>
 	<td nowrap class="TableData"  colspan="8" >
 	&nbsp;&nbsp;<input value="<?=_("快速查询")?>" type="submit" class="SmallButton" title="<?=_("快速查询")?>" name="button">
  <input value="<?=_("高级查询")?>" type="button" class="SmallButton" title="<?=_("模糊查询")?>" name="button1" onClick="ad_query();">
  <input type="button" value="<?=_("导出")?>" class="SmallButton"        onclick="openWindow('../../asset/query/export/conditional.php?WHERE_CLAUSE=<?=urlencode($WHERE_CLAUSE)?>&ORDER_CLAUSE=<?=urlencode($ORDER_STR)?>&LIMIT_CLAUSE=<?=urlencode($LIMIT_CLAUSE)?>', '600','400', 'theParam');"/>
  </td>
 </tr>
 </form>
</table>
</fieldset>
<?
if(!isset($TOTAL_ITEMS))
{
	$query = "SELECT count(*) from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")." order by DEPT_ID,CREATE_DATE,CPTL_NO";
	$cursor= exequery(TD::conn(),$query);
	$TOTAL_ITEMS=0;
	if($ROW=mysql_fetch_array($cursor))
		$TOTAL_ITEMS=$ROW[0];
}

?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE,$THE_FOUR_VAR)?></td>
</tr>
</table>
<?
$query="select * from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")."".$ORDER_STR;
$query .= " limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$CPTL_COUNT=0;
$CPTL_VAL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CPTL_COUNT++;
   if($CPTL_COUNT>200)
      break;
   $CPTL_IDS=$ROW["CPTL_ID"];
   $KEEPER = $ROW["KEEPER"];
   //$KEEPER=td_trim(GetUserNameById($KEEPER));
   $query_name = "SELECT USER_NAME from USER where USER_ID = '$KEEPER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   if($ROW_NAME=mysql_fetch_array($cursor_name)){
      $KEEPER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$KEEPER;
   }
   $CPTL_NOS=$ROW["CPTL_NO"];
   $CPTL_NAMES=$ROW["CPTL_NAME"];
   $DEPT_IDS=$ROW["DEPT_ID"];
   $CPTL_VALS=$ROW["CPTL_VAL"];
   $CPTL_BALS=$ROW["CPTL_BAL"];
   $DPCT_YYS=$ROW["DPCT_YY"];
   $MON_DPCTS=$ROW["MON_DPCT"];
   $SUM_DPCTS=$ROW["SUM_DPCT"];
   $CPTL_KINDS=$ROW["CPTL_KIND"];
   $PRCS_IDS=$ROW["PRCS_ID"];
   $FINISH_FLAGS=$ROW["FINISH_FLAG"];
   $CREATE_DATES=$ROW["CREATE_DATE"];
   $FROM_YYMMS=$ROW["FROM_YYMM"];
   $CPTL_VAL_COUNTS+=$CPTL_VALS;
   if($CPTL_KINDS=="01")
      $CPTL_KIND_DESCS=_("资产");
   else if($CPTL_KINDS=="02")
      $CPTL_KIND_DESCS=_("费用");
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_IDS'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESCS=$ROW1["PRCS_LONG_DESC"];
   if($DEPT_IDS!="" &&$DEPT_IDS!=0)
   {
   		$query2="select DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT_IDS'";
   		$cursor2= exequery(TD::conn(),$query2);
  		 if($ROW2=mysql_fetch_array($cursor2))
       $DEPT_NAME=$ROW2["DEPT_NAME"];
   }
   else
   {
   	  $DEPT_NAME="";
   }
   if($CPTL_COUNT==1)
   {
?>
<table class="TableList" width="90%" align="center">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><a href="#" onclick="order_by('DEPT_ID','<?if($ORDER_FIELD=="DEPT_ID"||$ORDER_FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');"><u><?=_("所属部门")?></u><?if($ORDER_FIELD=="DEPT_ID"||$ORDER_FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("资产编号")?></td>
      <td nowrap align="center"><?=_("资产名称")?></td>
      <td nowrap align="center"><?=_("资产保管人")?></td>
      <td nowrap align="center"><?=_("资产原值")?></td>
      <td nowrap align="center"><?=_("资产性质")?></td>
      <td nowrap align="center"><?=_("增加类型")?></td>
      <td nowrap align="center"><?=_("增加日期")?></td>
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
	<td nowrap  width=40><input type="checkbox" name="source_select" value="<?=$CPTL_IDS?>" ></td>
   <td nowrap align="center"><?=$DEPT_NAME?></td>
   <td nowrap align="center"><?=$CPTL_NOS?></td>
   <td><a href="javascript:cptl_detail('<?=$CPTL_IDS?>');"><?=$CPTL_NAMES?></a></td>
   <td nowrap align="center"><?=$KEEPER?></a></td>
   <td nowrap align="center"><?=$CPTL_VALS?></a></td>
	<td nowrap align="center"><?=$CPTL_KIND_DESCS?></td>
	<td nowrap align="left"><?=$PRCS_LONG_DESCS?></td>
	<td nowrap align="center"><?=$CREATE_DATES == "0000-00-00"?"":$CREATE_DATES?></td>
	<td nowrap align="center">
      <a href="javascript:cptl_detail('<?=$CPTL_IDS?>');"> <?=_("详情")?></a>
      <a href="javascript:openWindow('modify.php?CPTL_ID=<?=$CPTL_IDS?>', '800','600', 'theParam');"> <?=_("修改")?></a>
      <a href="javascript:openWindow('keep.php?CPTL_ID=<?=$CPTL_IDS?>', '600','600', 'theParam');"> <?=_("维护")?></a>
      <a href="javascript:dcr_cptl('<?=$CPTL_IDS?>');"> <?=_("减少")?></a>
      <a href="javascript:dpct_detail('<?=$CPTL_IDS?>');"> <?=_("折旧记录")?></a>
      <a href="javascript:del_detail('<?=$CPTL_IDS?>');"> <?=_("删除")?></a>
	</td>
</tr>
<?
}
if($CPTL_COUNT==0)
{
   Message("",_("无符合条件的固定资产信息"));
   exit;
}
else
{
?>
    <tr class="TableControl">
      <td nowrap align="center"><?=_("合计：")?></td>
      <td nowrap colspan="4"></td>
      <td nowrap align="center"><?=$CPTL_VAL_COUNTS?></a></td>
      <td nowrap colspan="4"</td>
    </tr>
    <tr class="TableControl">
		<td colspan="10">
    		<input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("全选")?></label> &nbsp;
    		<a href="javascript:delete_item();" title="<?=_("删除所选资产")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选资产")?></a>&nbsp;
		</td>
	</tr>
</table>
<br>
<?
}
?>
</body>
</html>
