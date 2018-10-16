<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$filename = _("车辆使用记录");
ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; ".get_attachment_filename($filename.".xls"));
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<title><?=_("车辆使用记录")?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=MYOA_CHARSET?>">
</head>
<body topmargin="5">
 <table class="TableList"width="95%">
    <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
      <td align="center"><?=_("车牌号")?></td>
      <td align="center"><?=_("司机")?></td>
      <td align="center"><?=_("申请人")?></td>
      <td align="center"><?=_("申请时间")?></td>
      <td align="center"><?=_("用车人")?></td>
      <td align="center"><?=_("用车部门")?></td>
      <td align="center"><?=_("事由")?></td>
      <td align="center"><?=_("开始时间")?></td>
      <td align="center"><?=_("结束时间")?></td>
      <td align="center"><?=_("目的地")?></td>
      <td align="center"><?=_("申请里程(公里)")?></td>
      <td align="center"><?=_("实际里程(公里)")?></td>
      <td align="center"><?=_("部门审批人")?></td>
      <td align="center"><?=_("调度员")?></td>
      <td align="center"><?=_("当前状态")?></td>
      <td align="center"><?=_("备注")?></td>
    </tr>
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


if($VU_STATUS!="")
   $WHERE_STR.=" and VU_STATUS='$VU_STATUS'";
if($V_ID!="")
   $WHERE_STR.=" and V_ID='$V_ID'";
if($VU_REQUEST_DATE_MIN!="")
   $WHERE_STR.=" and VU_REQUEST_DATE>='$VU_REQUEST_DATE_MIN'";
if($VU_REQUEST_DATE_MAX!="")
   $WHERE_STR.=" and VU_REQUEST_DATE<='$VU_REQUEST_DATE_MAX'";
   
if($TO_NAME1!="")
{ 
$query1="select USER_ID from USER where USER_NAME='$TO_NAME1'";
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
}
if($VU_DEPT!="")
   $WHERE_STR.=" and VU_DEPT='$VU_DEPT'";
if($VU_START_MIN!="")
   $WHERE_STR.=" and VU_START>='$VU_START_MIN'";
if($VU_START_MAX!="")
   $WHERE_STR.=" and VU_START<='$VU_START_MAX'";
if($VU_END_MIN!="")
   $WHERE_STR.=" and VU_END>='$VU_END_MIN'";
if($VU_END_MAX!="")
   $WHERE_STR.=" and VU_END<='$VU_END_MAX'";
if($TO_ID!="")
   $WHERE_STR.=" and VU_PROPOSER='$TO_ID'";
if($VU_OPERATOR!="")
   $WHERE_STR.=" and VU_OPERATOR='$VU_OPERATOR'";
if($VU_REASON!="")
   $WHERE_STR.=" and VU_REASON like '%$VU_REASON%'";
if($VU_REMARK!="")
   $WHERE_STR.=" and VU_REMARK like '%$VU_REMARK%'";
if($VU_DRIVER!="")
   $WHERE_STR.=" and VU_DRIVER like '%$VU_DRIVER%'";
   
//============================  =======================================
$query = "SELECT * from VEHICLE_USAGE where 1=1".$WHERE_STR." order by VU_STATUS,VU_START";
$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $VU_ID=$ROW["VU_ID"];
   $V_ID=$ROW["V_ID"];
   $VU_PROPOSER=$ROW["VU_PROPOSER"];
   $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
   $VU_USER=$ROW["VU_USER"];
   $VU_REASON=$ROW["VU_REASON"];
   $VU_START =$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_MILEAGE_TRUE=$ROW["VU_MILEAGE_TRUE"];
   $VU_DEPT=$ROW["VU_DEPT"];
   $VU_STATUS=$ROW["VU_STATUS"];
   $DEPT_MANAGER=$ROW["DEPT_MANAGER"];
   $VU_REMARK=$ROW["VU_REMARK"];
   $VU_DESTINATION=$ROW["VU_DESTINATION"];
   $VU_DRIVER=$ROW["VU_DRIVER"];
   $VU_OPERATOR=$ROW["VU_OPERATOR"];
   
   $DEPT_MANAGER_NAME=GetUserNameById($DEPT_MANAGER);
   
   $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}
   
   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";

   if($VU_STATUS==0)
      $VU_STATUS_DESC=_("待批");
   elseif($VU_STATUS==1)
      $VU_STATUS_DESC=_("已准");
   elseif($VU_STATUS==2)
      $VU_STATUS_DESC=_("使用中");
   elseif($VU_STATUS==3)
     $VU_STATUS_DESC=_("未准");
   elseif($VU_STATUS==4)
     $VU_STATUS_DESC=_("已结束");
   
   $query = "SELECT * from USER where USER_ID='$VU_PROPOSER'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $VU_PROPOSER_NAME=$ROW2["USER_NAME"];
   $query = "SELECT * from USER where USER_ID='$VU_OPERATOR'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $VU_OPERATOR_NAME=$ROW2["USER_NAME"];
   $query = "SELECT * from DEPARTMENT where DEPT_ID='$VU_DEPT'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $DEPT_NAME=$ROW2["DEPT_NAME"];
   $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $V_NUM=$ROW2["V_NUM"];
?>
    <tr>
      <td><?=$V_NUM?></td>
      <td align="center"><?=$VU_DRIVER?></td>
      <td align="center"><?=$VU_PROPOSER_NAME?></td>
      <td nowrap align="center" x:str="'<?=$VU_REQUEST_DATE?>"><?=$VU_REQUEST_DATE?></td>
      <td><?=$VU_USER?></td>
      <td><?=$DEPT_NAME?></td>
      <td><?=$VU_REASON?></td>
      <td nowrap align="center" x:str="'<?=$VU_START?>"><?=$VU_START?></a></td>
      <td nowrap align="center" x:str="'<?=$VU_END?>"><?=$VU_END?></a></td>
      <td><?=$VU_DESTINATION?></a></td>
      <td align="right"><?=$VU_MILEAGE?></a></td>
      <td align="right"><?=$VU_MILEAGE_TRUE?></a></td>
      <td align="center"><?=$DEPT_MANAGER_NAME?></a></td>
      <td align="center"><?=$VU_OPERATOR_NAME?></a></td>
      <td align="center"><?=$VU_STATUS_DESC?></a></td>
      <td><?=$VU_REMARK?></td>
    </tr>
<?
   }
?>

  </table>
</body>

</html>
