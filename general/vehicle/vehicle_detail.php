<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"><?=_("������ϸ��Ϣ")?></span><br>
        </td>
    </tr>
</table>
<?

//�޸���������״̬--yc
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
    $V_DISPLACEMENT     = $ROW["V_DISPLACEMENT"];  //����
    $V_COLOR            = $ROW["V_COLOR"];  //������ɫ
    $V_SEATING          = $ROW["V_SEATING"];    //��λ��
    $V_FRAME            = $ROW["V_FRAME"];  //���ܺź�6λ
    $V_CERTIFICATION    = $ROW["V_CERTIFICATION"];  //�������Ǽ�֤���7λ
    $V_NATURE           = $ROW["V_NATURE"]; //��������
    $V_DEPART           = $ROW["V_DEPART"]; //���ܲ���
    $V_ONWER            = $ROW["V_ONWER"];  //����������
    $V_CARUSER          = $ROW["V_CARUSER"];    //����ʹ����
    $V_TAX              = $ROW["V_TAX"];    //����˰�۸�
    $V_PAY              = $ROW["V_PAY"];    //�����֧
    $V_MILEAGE          = $ROW["V_MILEAGE"];    //��ʼ���
    $V_RECORD           = $ROW["V_RECORD"]; //�����������
    $V_BACKRECORD       = $ROW["V_BACKRECORD"]; //�����������
    $V_DEPART_PHONE     = $ROW["V_DEPART_PHONE"];   //���ܲ����ֻ�����
    $V_ONWER_PHONE      = $ROW["V_ONWER_PHONE"];   //�����������ֻ�����
    $V_CARUSER_PHONE    = $ROW["V_CARUSER_PHONE"];   //����ʹ�����ֻ�����
    $V_DATE             = $ROW["V_DATE"];
    $V_PRICE            = $ROW["V_PRICE"];
    $V_ENGINE_NUM       = $ROW["V_ENGINE_NUM"];
    $V_STATUS           = $ROW["V_STATUS"];
    $V_REMARK           = $ROW["V_REMARK"];
    $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];
    $DEPT_RANGE         = $ROW["DEPT_RANGE"];
    $USER_RANGE         = $ROW["USER_RANGE"];
    $V_TITLES           = $ROW["V_TITLES"]; //�Զ����ֶα���
    $V_FIELDS           = $ROW["V_FIELDS"]; //�Զ����ֶ�����
    
    $V_MOT              = $ROW["V_MOT"];
    $V_INSURE           = $ROW["V_INSURE"];
    $V_BINSURE          = $ROW["V_BINSURE"];
    
    $V_MOT = ($V_MOT=='0000-00-00') ? '' : ($s_curtime >= $V_MOT ? '<font color=red>'.$V_MOT.'</font>' : $V_MOT);
    $V_INSURE = ($V_INSURE=='0000-00-00') ? '' : ($s_curtime >= $V_INSURE ? '<font color=red>'.$V_INSURE.'</font>' : $V_INSURE);
    $V_BINSURE = ($V_BINSURE=='0000-00-00') ? '' : ($s_curtime >= $V_BINSURE ? '<font color=red>'.$V_BINSURE.'</font>' : $V_BINSURE);
    
    if($DEPT_RANGE=="ALL_DEPT")
    {
        $DEPT_RANGE_DESC = _("ȫ�岿��"); 
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
        $V_STATUS_DESC= "<font color=\'#00AA00\'><b>"._("����")."</b></font>";
    }
    else if($VU_STATUS==1)
    {
        $V_STATUS_DESC=_("��");
    }
    else if($V_STATUS==2)
    {
        $V_STATUS_DESC=_("ά����");
    }
    else if($V_STATUS==3)
    {
        $V_STATUS_DESC=_("����");
    }

    $query = "SELECT count(*) from VEHICLE_USAGE where V_ID='$V_ID' and VU_STATUS!='4'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $VU_COUNT=$ROW[0];
    }
    if($VU_COUNT>0)
    {
        $MSG = sprintf(_("��%d��Ԥ����Ϣ"),$VU_COUNT); 
        $ORDER_DETAIL="<a href=\"javascript:;\" onClick=\"window.open('order_detail.php?V_ID=".$V_ID."','','height=400,width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=170,top=170,resizable=yes');\" title=\""._("����鿴����")."\">".$MSG."</a>";
    }
    else
    {
        $ORDER_DETAIL=_("��Ԥ����Ϣ");
    }
?>
<table class="TableBlock" width="100%">
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�ͺţ�")?></td>
        <td nowrap align="left" class="TableData"><?=$V_MODEL?></td>
        <td class="TableData" width="40%" rowspan="6">
<?
        if($ATTACHMENT_NAME=="")
        {
            echo "<center>"._("������Ƭ")."</center>";
        }
        else
        {
            $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
	        <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("����鿴�Ŵ�ͼƬ")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("�ļ�����")?><?=$ATTACHMENT_NAME?>"></a>
<?
        }
?>	
        </td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���ƺţ�")?></td>
        <td nowrap align="left" class="TableData"><?=$V_NUM?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("������")?></td>
        <td nowrap align="left" class="TableData"><?=$V_DISPLACEMENT?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("������ɫ��")?></td>
        <td nowrap align="left" class="TableData"><?=$V_COLOR?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��λ����")?></td>
        <td nowrap align="left" class="TableData"><?=$V_SEATING?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���ܺź�6λ��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_FRAME?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�������Ǽ�֤���7λ��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CERTIFICATION?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���������룺")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ENGINE_NUM?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�������ʣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TYPE_V_NATURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�������ͣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TYPE_DESC?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���ܲ��ţ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DEPART?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���ܲ��ŵ绰��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DEPART_PHONE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���������ˣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ONWER?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("���������˵绰��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_ONWER_PHONE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("����ʹ���ˣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CARUSER?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("����ʹ���˵绰��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_CARUSER_PHONE?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("˾����")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DRIVER?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("˾���绰��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PHONE_NO?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�������ڣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_DATE?></td>
    </tr>
    
<?
    $query = "SELECT OC_ID FROM vehicle_oil_card where V_NUM='$V_NUM'";
    $cursor = exequery(TD::conn(), $query);
    $ocWord = _("�Ϳ����");
    
    if( $row = mysql_fetch_array($cursor) )
    {
        echo    '<tr class="TableLine2">'.
                    '<td nowrap align="left" width="80" class="TableContent">'.$ocWord.'</td>'.
                    '<td nowrap align="left" class="TableData" colspan="2">'.$row["OC_ID"].'</td>'.
                '</tr>';
    }
?>
    
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��ʼ��̣�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_MILEAGE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("����۸�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PRICE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("����˰�۸�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_TAX?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�����֧��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_PAY?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("������ڣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_MOT?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��ǿ�����ڣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_INSURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��ҵ�����ڣ�")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_BINSURE?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("Ԥ�������")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$ORDER_DETAIL?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" class="TableContent"><?=_("����Ȩ��(����)��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$DEPT_RANGE_DESC?></td>
    </tr>  
    <tr class="TableLine1">
        <td nowrap align="left" class="TableContent"><?=_("����Ȩ��(��Ա)��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$USER_RANGE_DESC?></td>
    </tr>
    <tr class="TableLine1">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��ǰ״̬��")?></td>
        <td nowrap align="left" class="TableData" colspan="2"><?=$V_STATUS_DESC?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�������������")?></td>
        <td align="left" class="TableData" colspan="2"><?=$V_RECORD?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("�����軹��¼��")?></td>
        <td align="left" class="TableData" colspan="2"><?=$V_BACKRECORD?></td>
    </tr>
    <tr class="TableLine2">
        <td nowrap align="left" width="80" class="TableContent"><?=_("��ע��")?></td>
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
            <input type="button" value="<?=_("��ӡ")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("ֱ�Ӵ�ӡ���ҳ��")?>">&nbsp;&nbsp;
            <input type="button" value="<?=_("������������")?>" class="BigButton" onClick="window.open('repairremind.php?V_ID=2','','height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=170,top=100,resizable=yes');">&nbsp;&nbsp;
            <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
        </td>
    </tr>
</table>
<?
}
else
{
    Message("",_("��δ�ҵ���Ӧ��¼��"));
}
?>

</body>
</html>
