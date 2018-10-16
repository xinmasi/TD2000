<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("�Ϳ�����");
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

function delete_vehicle(ID)
{
    msg='<?=_("ȷ��Ҫɾ�����Ϳ���Ϣ��")?>';
    if(window.confirm(msg))
    {
        URL="deleteoilcard.php?ID=" + ID;
        window.location=URL;
    }
}

function my_manage_open(V_URL, V_ID, V_WINDTH, V_HEIGHT)
{
    myleft=(screen.availWidth-V_WINDTH)/2;
    mytop=(screen.availHeight-V_HEIGHT)/2;
    window.open(V_URL+"?V_ID="+V_ID,"note_win"+V_ID,"height="+V_HEIGHT+",width="+V_WINDTH+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='source_select']:checkbox").each(function(){ 
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}

function delete_item()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫɾ���Ϳ���Ϣ��������ѡ������һ����")?>");
		return;
	}
	msg='<?=_("ȷ��Ҫɾ����ѡ�Ϳ���Ϣ��")?>';
	if(window.confirm(msg))
	{
		url="deleteoilcard.php?ID="+ delete_str + '&delete_all=1';
    	location=url;
  	}
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("�Ϳ�����")?></span>
        </td>
    </tr>
</table>

<?
$s_curtime = date('Y-m-d',time());
//============================ ��ʾ�ѷ������� =======================================
$query = "SELECT * FROM vehicle_oil_card ORDER BY id DESC";
$cursor = exequery(TD::conn(),$query);
$COUNT = 0;
while($ROW=  mysql_fetch_array($cursor))
{
    $COUNT++;
    
    $ID = $ROW["ID"];
    $V_ID = $ROW["V_ID"];
    $OC_ID = $ROW["OC_ID"];
    $OC_DATE = $ROW["OC_DATE"];
    $OC_HANDLED = $ROW["OC_HANDLED"];
    $OC_COMPANY = $ROW["OC_COMPANY"];
    $OC_PASSWORD = $ROW["OC_PASSWORD"];
    $OC_STATUS = $ROW["OC_STATUS"];
    $V_DEPT = $ROW["V_DEPT"];
    $V_NUM = $ROW["V_NUM"];
    $V_TYPE = $ROW["V_TYPE"];
    $V_ONWER = $ROW["V_ONWER"];
    $V_USER = $ROW["V_USER"];
    
    if($OC_DATE == '0000-00-00' || $OC_DATE=='')
    {
        $OC_DATE = '-';
    }
    
    $OC_STATUS_DESC = get_code_name($OC_STATUS,"VEHICLE_OIL_STATUS");
    
    if($COUNT%2==1)
    {
        $TableLine="TableLine1";
    }
    else
    {
        $TableLine="TableLine2";
    }
    
    if($COUNT==1)
    {
?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("ѡ��")?></td>
        <td nowrap align="center"><?=_("�Ϳ����")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("������")?></td>
        <td nowrap align="center"><?=_("�Ϳ����е�λ")?></td>
        <td nowrap align="center"><?=_("�Ϳ�����")?></td>
        <td nowrap align="center"><?=_("ʹ��״̬")?></td>
        <td nowrap align="center"><?=_("���ܲ���")?></td>
        <td nowrap align="center"><?=_("���ƺ�")?></td>
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("������")?></td>
        <td nowrap align="center"><?=_("ʹ����")?></td>
        <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
    }
?>
    <tr class="<?=$TableLine?>">
        <td nowrap  width=40><input type="checkbox" name="source_select" id="source_select" value="<?=$ID?>"></td>
        <!--<td nowrap align="center"><a href="javascript:;" onClick="my_manage_open('../vehicle_detail.php', '<?=$V_ID?>', '700', '500');"><?=$V_NUM?></a></td>-->
        <td nowrap align="center"><?=$OC_ID?></td>
        <td nowrap align="center"><?=$OC_DATE?></td>
        <td nowrap align="center"><?=$OC_HANDLED?></td>
        <td nowrap align="center"><?=$OC_COMPANY?></td>
        <td nowrap align="center"><?=$OC_PASSWORD?></td>
        <td nowrap align="center"><?=$OC_STATUS_DESC?></td>
        <td nowrap align="center"><?=$V_DEPT?></td>
        <td nowrap align="center"><a href="javascript:;" onClick="my_manage_open('../vehicle_detail.php', '<?=$V_ID?>', '700', '500');"><?=$V_NUM?></a></td>
        <td nowrap align="center"><?=$V_TYPE?></td>
        <td nowrap align="center"><?=$V_ONWER?></td>
        <td nowrap align="center"><?=$V_USER?></td>

        <td nowrap align="center">
            <a href="newoilcard.php?ID=<?=$ID?>"><?=_("�޸�")?></a>&nbsp;&nbsp;&nbsp;
            <a href="javascript:delete_vehicle('<?=$ID?>');"><?=_("ɾ��")?></a>
        </td>
    </tr>

<?
}
if($COUNT>0)
{
    echo '<tr class="TableControl">
        <td colspan = "13"><input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for">ȫѡ</label> &nbsp;
        <a href="javascript:delete_item();" title=_("ɾ����ѡ�Ϳ���Ϣ")><img src="'.MYOA_STATIC_SERVER.'/static/images/delete.gif" align="absMiddle">ɾ����ѡ�Ϳ���Ϣ</a>&nbsp;</td>
        </tr></table>';
}
else
{
    Message("",_("�����Ϳ���Ϣ"));
}
?>

</body>
</html>
