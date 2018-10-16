<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
    $start=0;


//�޸���������״̬--yc
update_sms_status('61',0);

$HTML_PAGE_TITLE = _("��ѵ�ƻ���Ϣ");
include_once("inc/header.inc.php");
?>

<script Language="JavaScript">
function delete_plan(T_PLAN_ID)
{
    msg='<?=_("ȷ��Ҫɾ��������ѵ�ƻ���")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?T_PLAN_ID=" + T_PLAN_ID+"&PAGE_START=<?=$PAGE_START?>";
        window.location=URL;
    }
}

function check_all()
{
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {
        if(document.getElementsByName("allbox").item(0).checked)
            document.getElementsByName("email_select").item(i).checked=true;
        else
            document.getElementsByName("email_select").item(i).checked=false;
    }

    if(i==0)
    {
        if(document.getElementsByName("allbox").item(0).checked)
            document.getElementsByName("email_select").checked=true;
        else
            document.getElementsByName("email_select").checked=false;
    }
}

function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
    checked_str="";
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {

        el=document.getElementsByName("email_select").item(i);
        if(el.checked)
        {  val=el.value;
            checked_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("email_select");
        if(el.checked)
        {  val=el.value;
            checked_str+=val + ",";
        }
    }
    return checked_str;
}

function delete_mail()
{
    delete_str=get_checked();
    if(delete_str=="")
    {
        alert("<?=_("Ҫɾ��֤��ѵ�ƻ���������ѡ������һ����")?>");
        return;
    }

    msg='<?=_("ȷ��Ҫɾ��������ѵ�ƻ���Ϣ��")?>';
    if(window.confirm(msg))
    {
        url="delete.php?T_PLAN_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
        location=url;
    }
}
</script>

<body class="bodycolor">

<?
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA����Ա ��������Ա �½���
$WHERE_STR = hr_priv("");

if(!isset($TOTAL_ITEMS))
{
    $query = "SELECT count(*) from HR_TRAINING_PLAN where ".$WHERE_STR;
    $cursor= exequery(TD::conn(),$query, $connstatus);
    if($ROW=mysql_fetch_array($cursor))
        $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������ѵ�ƻ�")?></span>&nbsp;
        </td>
        <?
        if($TOTAL_ITEMS>0)
        {
            ?>
            <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
            <?
        }
        ?>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>
<table class="TableList" width="100%">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("ѡ��")?></td>
        <td nowrap align="center"><?=_("��ѵ�ƻ����")?></td>
        <td nowrap align="center"><?=_("��ѵ�ƻ�����")?></td>
        <td nowrap align="center"><?=_("��ѵ����")?></td>
        <td nowrap align="center"><?=_("��ѵ��ʽ")?></td>
        <td nowrap align="center"><?=_("�ƻ�״̬")?></td>
        <td nowrap align="center"><?=_("����")?></td>
    </tr>
    <?
    }

    $query = "SELECT * from  HR_TRAINING_PLAN where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $PLAN_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $PLAN_COUNT++;

        $T_PLAN_ID=$ROW["T_PLAN_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $T_PLAN_NO=$ROW["T_PLAN_NO"];
        $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
        $T_CHANNEL=$ROW["T_CHANNEL"];
        $T_COURSE_TYPES=$ROW["T_COURSE_TYPES"];
        $ASSESSING_STATUS=$ROW["ASSESSING_STATUS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $T_COURSE_TYPES=get_hrms_code_name($T_COURSE_TYPES,"T_COURSE_TYPE");

        if($T_CHANNEL=="0")
            $T_CHANNEL=_("�ڲ���ѵ");
        if($T_CHANNEL=="1")
            $T_CHANNEL=_("������ѵ");
        ?>
        <tr class="TableData">
            <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$T_PLAN_ID?>" onClick="check_one(self);">
            <td nowrap align="center"><?=$T_PLAN_NO?></td>
            <td nowrap align="center"><?=$T_PLAN_NAME?></td>
            <td nowrap align="center"><?=$T_CHANNEL?></td>
            <td nowrap align="center"><?=$T_COURSE_TYPES?></td>
            <td nowrap align="center"><?if($ASSESSING_STATUS==0) echo _("������");?><?if($ASSESSING_STATUS==1) echo "<font color=green>"._("����׼")."</font>";?><?if($ASSESSING_STATUS==2) echo "<font color=red>"._("δ��׼")."</font>";?></td>
            <td nowrap align="center">
                <a href="javascript:;" onClick="window.open('plan_detail.php?T_PLAN_ID=<?=$T_PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
                <?if($ASSESSING_STATUS!=1) {?><a href="modify.php?T_PLAN_ID=<?=$T_PLAN_ID?>"> <?=_("�޸�")?></a>&nbsp;<?}?>
                <a href="javascript:delete_plan(<?=$T_PLAN_ID?>);"> <?=_("ɾ��")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($TOTAL_ITEMS>0)
    {
        ?>
        <tr class="TableControl">
            <td colspan="19">
                <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
                <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ��ѵ�ƻ���Ϣ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    else
        Message("",_("����ѵ�ƻ���¼"));

    ?>
</table>
<br>
</body>
</html>