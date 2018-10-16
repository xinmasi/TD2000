<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆使用记录查询");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='source_select']").attr("checked",'true');
        }
        else
        {
            jQuery("[name='source_select']").removeAttr("checked");
        }
    })
    
    jQuery("[name='source_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});
function delete_uvehicle(VU_ID,back_str)
{
  msg='<?=_("确认要删除该用车记录吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete_search.php?VU_ID=" + VU_ID + back_str;
     window.location=URL;
  }
}
function get_checked()
{
    checked_str="";
    
    jQuery("input[name='source_select']:checkbox").each(function(){ 
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val() +","
        }
    })
    
    return checked_str;
}
function delete_item(back_str)
{   
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("要删除用车记录，请至少选择其中一条。")?>");
		return;
	}
	msg='<?=_("确认要删除所选用车记录吗？")?>';
	if(window.confirm(msg))
	{
		url="delete_search.php?VU_ID="+ delete_str + '&delete_all=1' + back_str;
    	location=url;
  	}
}
function order_by(field,asc_desc,back_str)
{
  window.location="search.php?FIELD="+field+"&ASC_DESC="+asc_desc + back_str;
}
</script>


<body class="bodycolor">
<?
if($VU_REQUEST_DATE_MIN!="")
{
  $TIME_OK=is_date($VU_REQUEST_DATE_MIN);

  if(!$TIME_OK)
  { Message(_("错误"),_("申请日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_REQUEST_DATE_MIN.=" 00:00:00";
}
if($VU_REQUEST_DATE_MAX!="")
{
  $TIME_OK=is_date($VU_REQUEST_DATE_MAX);

  if(!$TIME_OK)
  { Message(_("错误"),_("申请日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_REQUEST_DATE_MAX.=" 23:59:59";
}

if($VU_START_MIN!="")
{
  $TIME_OK=is_date($VU_START_MIN);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_START_MIN.=" 00:00:00";
}
if($VU_START_MAX!="")
{
  $TIME_OK=is_date($VU_START_MAX);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_START_MAX.=" 23:59:59";
}

if($VU_END_MIN!="")
{
  $TIME_OK=is_date($VU_END_MIN);

  if(!$TIME_OK)
  { Message(_("错误"),_("结束日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_END_MIN.=" 00:00:00";
}
if($VU_END_MAX!="")
{
  $TIME_OK=is_date($VU_END_MAX);

  if(!$TIME_OK)
  { Message(_("错误"),_("结束日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
  $VU_END_MAX.=" 23:59:59";
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("车辆使用记录查询")?></span>
    </td>
  </tr>
</table>

<?
$WHERE_STR="";
$BACK_STR="";

if($VU_STATUS!="")
{
   $WHERE_STR.=" and VU_STATUS='$VU_STATUS'";
   $BACK_STR.="&VU_STATUS=$VU_STATUS";
}
if($V_ID!="")
{
   $WHERE_STR.=" and V_ID='$V_ID'";
   $BACK_STR.="&V_ID=$V_ID";
}
if($VU_REQUEST_DATE_MIN!="")
{
   $WHERE_STR.=" and VU_REQUEST_DATE>='$VU_REQUEST_DATE_MIN'";
   $BACK_STR.="&VU_REQUEST_DATE_MIN=$VU_REQUEST_DATE_MIN";
}
if($VU_REQUEST_DATE_MAX!="")
{
   $WHERE_STR.=" and VU_REQUEST_DATE<='$VU_REQUEST_DATE_MAX'";
   $BACK_STR.="&VU_REQUEST_DATE_MAX=$VU_REQUEST_DATE_MAX";
}
if($TO_NAME1!="")
{ 
$query1="select USER_ID from USER where USER_NMAE='$TO_NAME1'";
$cursor1= exequery(TD::conn(),$query1);
$NUM=mysql_num_rows($cursor1);
if($NUM==0)
   $VU_USER = $TO_NAME1;
else 
   $VU_USER = $TO_ID1;   
}
if($VU_USER!="")
{
   $WHERE_STR.=" and VU_USER like '%$VU_USER%'";
   $BACK_STR.="&VU_USER=$VU_USER";
}
if($VU_DEPT!="")
{
   $WHERE_STR.=" and VU_DEPT='$VU_DEPT'";
   $BACK_STR.="&VU_DEPT=$VU_DEPT";
}
if($VU_START_MIN!="")
{
   $WHERE_STR.=" and VU_START>='$VU_START_MIN'";
   $BACK_STR.="&VU_START_MIN=$VU_START_MIN";
}
if($VU_START_MAX!="")
{
   $WHERE_STR.=" and VU_START<='$VU_START_MAX'";
   $BACK_STR.="&VU_START_MAX=$VU_START_MAX";
}
if($VU_END_MIN!="")
{
   $WHERE_STR.=" and VU_END>='$VU_END_MIN'";
   $BACK_STR.="&VU_END_MIN=$VU_END_MIN";
}
if($VU_END_MAX!="")
{
   $WHERE_STR.=" and VU_END<='$VU_END_MAX'";
   $BACK_STR.="&VU_END_MAX=$VU_END_MAX";
}
if($TO_ID!="")
{
   $WHERE_STR.=" and VU_PROPOSER='$TO_ID'";
   $BACK_STR.="&TO_ID=$TO_ID";
}
if($VU_OPERATOR!="")
{
   $WHERE_STR.=" and VU_OPERATOR='$VU_OPERATOR'";
   $BACK_STR.="&VU_OPERATOR=$VU_OPERATOR";
}
if($VU_REASON!="")
{
   $WHERE_STR.=" and VU_REASON like '%$VU_REASON%'";
   $BACK_STR.="&VU_REASON=$VU_REASON";
}
if($VU_REMARK!="")
{
   $WHERE_STR.=" and VU_REMARK like '%$VU_REMARK%'";
   $BACK_STR.="&VU_REMARK=$VU_REMARK";
}
if($VU_DRIVER!="")
{
   $WHERE_STR.=" and VU_DRIVER like '%$VU_DRIVER%'";
   $BACK_STR.="&VU_DRIVER=$VU_DRIVER";
}

if(!isset($_SESSION["MY_BACK_STR"]))
   $_SESSION["MY_BACK_STR"] = $BACK_STR;

//===================================================================

if($ASC_DESC=="")
   $ASC_DESC="1";
if($FIELD=="")
   $FIELD="VU_START";

$query = "SELECT * from VEHICLE_USAGE where 1=1".$WHERE_STR;
$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $VU_COUNT++;

   $VU_ID=$ROW["VU_ID"];
   $V_ID=$ROW["V_ID"];
   $VU_PROPOSER=$ROW["VU_PROPOSER"];
   $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
   $VU_USER=$ROW["VU_USER"];
   $VU_REASON=$ROW["VU_REASON"];
   $VU_START =$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
	 $VU_FINAL_END=$ROW["VU_FINAL_END"];
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_DEPT=$ROW["VU_DEPT"];
   $VU_STATUS=$ROW["VU_STATUS"];
   $VU_REMARK=$ROW["VU_REMARK"];
   $VU_DESTINATION=$ROW["VU_DESTINATION"];
   $VU_OPERATOR=$ROW["VU_OPERATOR"];
   $VU_DESTINATION=$ROW["VU_DESTINATION"];
   $VU_MILEAGE_SUM+=$VU_MILEAGE;

    $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   	$cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		//$VU_USER_ID = $ROW_NAME["USER_NAME"] != ""?$VU_USER:"";
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}
   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";
   if($VU_REQUEST_DATE=="0000-00-00 00:00:00")
      $VU_REQUEST_DATE="";

   $query = "SELECT * from USER  where USER_ID='$VU_PROPOSER'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $VU_PROPOSER_NAME=$ROW["USER_NAME"];

   if($VU_DEPT!="")
   {
      $query = "SELECT * from DEPARTMENT  where DEPT_ID='$VU_DEPT'";
      $cursor1= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor1))
         $VU_DEPT_FIELD_DESC=$ROW["DEPT_NAME"];
   }

   $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
      $V_NUM=$ROW1["V_NUM"];

   if($VU_STATUS==0)
      $VU_STATUS_DESC=_("待批");
   elseif($VU_STATUS==1)
      $VU_STATUS_DESC=_("已准");
   elseif($VU_STATUS==2)
      $VU_STATUS_DESC=_("使用中");
   elseif($VU_STATUS==3)
      $VU_STATUS_DESC=_("未准");
   elseif($VU_STATUS==4)
      $VU_STATUS_DESC=_("空闲");

   if($VU_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("车牌号")?></td>
      <td nowrap align="center"><?=_("用车人")?></td>
      <td align="center"><?=_("事由")?></td>
      <td align="center"><?=_("目的地")?></td>        
      <td width="120" align="center" onclick="order_by('VU_START','<?if($FIELD=="VU_START"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>','<?=$BACK_STR?>');" style="cursor:hand;" width="30%"><u><?=_("开始时间")?></u><?if($FIELD=="VU_START"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td width="120" align="center" onclick="order_by('VU_END','<?if($FIELD=="VU_END") echo 1-$ASC_DESC;else echo "1";?>','<?=$BACK_STR?>');" style="cursor:hand;"><u><?=_("结束时间")?></u><?if($FIELD=="VU_END") echo $ORDER_IMG;?></td>
			<td width="120" align="center" onclick="order_by('VU_FINAL_END','<?if($FIELD=="VU_FINAL_END") echo 1-$ASC_DESC;else echo "1";?>','<?=$BACK_STR?>');" style="cursor:hand;"><u><?=_("实际结束")?></u><?if($FIELD=="VU_FINAL_END") echo $ORDER_IMG;?></td>
      <td align="center"><?=_("备注")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
?>
    <tr class="TableData">
      <td nowrap  width=40><input type="checkbox" name="source_select" id="source_select" value="<?=$VU_ID?>"></td>
      <td nowrap align="center"><a href="javascript:;" onClick="window.open('../vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=160,,resizable=yes');"><?=$V_NUM?></a></td>
      <td nowrap align="center"><?=$VU_USER?></td>
      <td align="center"><?=$VU_REASON?></td>
      <td align="center"><?=$VU_DESTINATION?></td>         
      <td width="120" nowrap align="center"><?=$VU_START?></td>
      <td width="120" nowrap align="center"><?=$VU_END?></td>
			<td width="120" nowrap align="center">
			<? 
					if($VU_STATUS!=4){
						echo "-";
					}else{
						echo $VU_FINAL_END;
					}
			?>
			</td>
      <td align="center"><?=$VU_REMARK?></td>
      <td nowrap align="center">
        <a href="javascript:;" onClick="window.open('../usage_detail.php?VU_ID=<?=$VU_ID?>','','height=380,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=200,left=280,resizable=yes');"><?=_("详细信息")?></a>
<?
$query1 = "SELECT * from VEHICLE_OPERATOR where OPERATOR_ID=1";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME=$ROW1["OPERATOR_NAME"];
   //登录用户是调度员并且是审批该用车记录
   if(find_id($OPERATOR_NAME,$_SESSION["LOGIN_USER_ID"]) && find_id($VU_OPERATOR,$_SESSION["LOGIN_USER_ID"]))
   {
?>
        <a href="javascript:;" onClick="window.open('edit.php?VU_ID=<?=$VU_ID?>','','height=380,width=680,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=200,left=280,resizable=yes');"><?=_("修改")?></a>
<?
   }
}

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:delete_uvehicle('<?=$VU_ID?>','<?=$BACK_STR?>');><?=_("删除")?></a>
<?
}
?>

      </td>
    </tr>
<?
   }
   if($VU_COUNT>0)
   {
?>
        <tr class="TableControl">
        <td colspan = "10"><input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
        <a href="javascript:delete_item('<?=$BACK_STR?>');" title="<?=_("删除所选记录")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选记录")?></a>&nbsp;</td>
        </tr>
        </table>
<?  
  }
   else
      Message("",_("无符合条件的车辆使用信息"));
?>
<br>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='query_all.php'"></center>
</body>

</html>
