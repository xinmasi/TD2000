<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("车辆详细信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"><?=_("车辆详细信息")?></span><br>
        </td>
    </tr>
</table>
<?

//修改事务提醒状态--yc
update_sms_status('9',$V_ID);

$s_curtime = date('Y-m-d',time());
$query = "SELECT * from VEHICLE where V_ID='$V_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $V_MODEL            = $ROW["V_MODEL"];
    $V_NUM              = $ROW["V_NUM"];
    $V_DRIVER           = $ROW["V_DRIVER"];
    $V_PHONE_NO         = $ROW["V_PHONE_NO"];
    $V_TYPE             = $ROW["V_TYPE"];
    $V_DISPLACEMENT     = $ROW["V_DISPLACEMENT"];  //排量
    $V_COLOR            = $ROW["V_COLOR"];  //车身颜色
    $V_SEATING          = $ROW["V_SEATING"];    //座位数
    $V_FRAME            = $ROW["V_FRAME"];  //车架号后6位
    $V_CERTIFICATION    = $ROW["V_CERTIFICATION"];  //机动车登记证书后7位
    $V_NATURE           = $ROW["V_NATURE"]; //车辆性质
    $V_DEPART           = $ROW["V_DEPART"]; //保管部门
    $V_ONWER            = $ROW["V_ONWER"];  //车辆所有人
    $V_CARUSER          = $ROW["V_CARUSER"];    //车辆使用人
    $V_TAX              = $ROW["V_TAX"];    //购置税价格
    $V_PAY              = $ROW["V_PAY"];    //财务借支
    $V_MILEAGE          = $ROW["V_MILEAGE"];    //初始里程
    $V_RECORD           = $ROW["V_RECORD"]; //车辆档案情况
    $V_BACKRECORD       = $ROW["V_BACKRECORD"]; //车辆档案情况
    $V_DEPART_PHONE     = $ROW["V_DEPART_PHONE"];   //保管部门手机号码
    $V_ONWER_PHONE      = $ROW["V_ONWER_PHONE"];   //车辆所有人手机号码
    $V_CARUSER_PHONE    = $ROW["V_CARUSER_PHONE"];   //车辆使用人手机号码
    $V_DATE             = $ROW["V_DATE"];
    $V_PRICE            = $ROW["V_PRICE"];
    $V_ENGINE_NUM       = $ROW["V_ENGINE_NUM"];
    $V_STATUS           = $ROW["V_STATUS"];
    $V_REMARK           = $ROW["V_REMARK"];
    $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];
    $DEPT_RANGE         = $ROW["DEPT_RANGE"];
    $USER_RANGE         = $ROW["USER_RANGE"];
    $V_TITLES           = $ROW["V_TITLES"]; //自定义字段标题
    $V_FIELDS           = $ROW["V_FIELDS"]; //自定义字段内容
    
    $V_MOT              = $ROW["V_MOT"];
    $V_INSURE           = $ROW["V_INSURE"];
    $V_BINSURE          = $ROW["V_BINSURE"];
    
    $V_MOT = ($V_MOT=='0000-00-00') ? '' : ($s_curtime >= $V_MOT ? '<font color=red>'.$V_MOT.'</font>' : $V_MOT);
    $V_INSURE = ($V_INSURE=='0000-00-00') ? '' : ($s_curtime >= $V_INSURE ? '<font color=red>'.$V_INSURE.'</font>' : $V_INSURE);
    $V_BINSURE = ($V_BINSURE=='0000-00-00') ? '' : ($s_curtime >= $V_BINSURE ? '<font color=red>'.$V_BINSURE.'</font>' : $V_BINSURE);
    
    if($DEPT_RANGE=="ALL_DEPT")
    {
        $DEPT_RANGE_DESC = _("全体部门"); 
    }
    else
    {
        $DEPT_RANGE_DESC = substr(GetDeptNameById($DEPT_RANGE),0,-1);
    }
    $USER_RANGE_DESC = substr(GetUserNameById($USER_RANGE),0,-1); 
    
    if($V_DATE=="0000-00-00")
    {
        $V_DATE="";
    }

    $V_TYPE_DESC=get_code_name($V_TYPE,"VEHICLE_TYPE");
    
    $V_TYPE_V_NATURE=get_code_name($V_NATURE,"VEHICLE_NATURE");
    
    if($V_STATUS==0)
    {
        $V_STATUS_DESC= "<font color=\'#00AA00\'><b>"._("可用")."</b></font>";
    }
    else if($VU_STATUS==1)
    {
        $V_STATUS_DESC=_("损坏");
    }
    else if($V_STATUS==2)
    {
        $V_STATUS_DESC=_("维修中");
    }
    else if($V_STATUS==3)
    {
        $V_STATUS_DESC=_("报废");
    }

    $query = "SELECT count(*) from VEHICLE_USAGE where V_ID='$V_ID' and VU_STATUS!='4'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $VU_COUNT=$ROW[0];
    }
    if($VU_COUNT>0)
    {
        $MSG = sprintf(_("共%d条预定信息"),$VU_COUNT); 
        $ORDER_DETAIL="<a href=\"javascript:;\" onClick=\"window.open('order_detail.php?V_ID=".$V_ID."','','height=400,width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=170,top=170,resizable=yes');\" title=\""._("点击查看详情")."\">".$MSG."</a>";
    }
    else
    {
        $ORDER_DETAIL=_("无预定信息");
    }
?>
<table class="TableBlock" width="100%">
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("型号：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_MODEL?></td>
        <td class="TableData" width="40%" rowspan="6">
<?
        if($ATTACHMENT_NAME=="")
        {
            echo "<center>"._("暂无照片")."</center>";
        }
        else
        {
            $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
	        <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>
<?
        }
?>	
        </td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车牌号：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_NUM?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("排量：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_DISPLACEMENT?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车身颜色：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_COLOR?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("座位数：")?></td>
        <td nowrap align="left" class="TableData"><?=$V_SEATING?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车架号后6位：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_FRAME?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("机动车登记证书后7位：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CERTIFICATION?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("发动机号码：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ENGINE_NUM?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车辆性质：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TYPE_V_NATURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车辆类型：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TYPE_DESC?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("保管部门：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DEPART?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("保管部门电话：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DEPART_PHONE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车辆所有人：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ONWER?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车辆所有人电话：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ONWER_PHONE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("长期使用人：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CARUSER?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("长期使用人电话：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CARUSER_PHONE?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("司机：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DRIVER?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("司机电话：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PHONE_NO?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("购置日期：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DATE?></td>
    </tr>
    
<?
    $query = "SELECT OC_ID FROM vehicle_oil_card where V_NUM='$V_NUM'";
    $cursor = exequery(TD::conn(), $query);
    $ocWord = _("油卡编号");
    
    if( $row = mysql_fetch_array($cursor) )
    {
        echo    '<tr class="TableLine2">'.
                    '<td nowrap align="left" width="80" class="TableContent">'.$ocWord.'</td>'.
                    '<td nowrap align="left" class="TableData" colspan="2">'.$row["OC_ID"].'</td>'.
                '</tr>';
    }
?>
    
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("初始里程：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_MILEAGE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("购买价格：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PRICE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("购置税价格：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TAX?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("财务借支：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PAY?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("年检日期：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_MOT?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("交强险日期：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_INSURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("商业险日期：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_BINSURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("预定情况：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$ORDER_DETAIL?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" class="TableContent"><?=_("申请权限(部门)：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$DEPT_RANGE_DESC?></td>
    </tr>  
    <tr class="TableLine1">
        <td nowrap align="left" class="TableContent"><?=_("申请权限(人员)：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$USER_RANGE_DESC?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("当前状态：")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_STATUS_DESC?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("车辆档案情况：")?></td>
        <td align="left" class="TableData" colspan="2"><?=$V_RECORD?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("档案借还记录：")?></td>
        <td align="left" class="TableData" colspan="2"><?=$V_BACKRECORD?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("备注：")?></td>
        <td align="left" class="TableData" colspan="2"><?=$V_REMARK?></td>
    </tr>
<?
    $titlesArray = Array();
    $fieldsArray = Array();
    
    $V_TITLES = substr($V_TITLES, 0, -1);
    $V_FIELDS = substr($V_FIELDS, 0, -1);
    
    $titlesArray = explode(",", $V_TITLES);
    $fieldsArray = explode(",", $V_FIELDS);
    
    if($V_TITLES != '')
    {
        for($count=0; $count<count($titlesArray); $count++)
        {
            echo    '<tr>'.
                        '<tr class="TableLine2">'.
                        '<td nowrap align="left" width="80" class="TableContent">'.$titlesArray[$count].'</td>'.
                        '<td align="left" class="TableData" colspan="2">'.$fieldsArray[$count].'</td>'.
                    '</tr>';
        }
    }
    
    
?>
    <tr align="center" class="TableControl">
        <td colspan="3">
            <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>">&nbsp;&nbsp;
            <input type="button" value="<?=_("车辆保养提醒")?>" class="BigButton" onClick="window.open('repairremind.php?V_ID=2','','height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=170,top=100,resizable=yes');">&nbsp;&nbsp;
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
        </td>
    </tr>
</table>
<?
}
else
{
    Message("",_("无未找到相应记录！"));
}
?>

</body>
</html>
