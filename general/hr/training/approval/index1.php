<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��ѵ�ƻ�����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript">
var $ = function(id){return document.getElementById(id);};
function ShowDetial(T_PLAN_ID)
{
    myleft=(screen.availWidth-800)/2;
    window.open("../plan/plan_detail.php?T_PLAN_ID="+T_PLAN_ID,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=600,left="+myleft+",top=50");
}
function approval(T_PLAN_ID,PASS)
{
    if(PASS==1)
        var msg="<?=_("ȷ��Ҫ����ͨ���˼ƻ�����������д���������")?>";
    else
        var msg="<?=_("ȷ��Ҫ���ش˼ƻ�����������д�������ɣ�")?>";
    $("confirm").innerHTML="<font color=red>"+msg+"</font>";
    $("T_PLAN_ID").value=T_PLAN_ID;
    $("PASS").value=PASS;
    ShowDialog('comment');
}
function check_form()
{
    if(document.getElementById("assessing_view").value=="")
    {
        alert("<?=_("����д���������")?>");
        return(false);
    }
    return(true);
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_training.gif" align="absmiddle"/>
            <span class="big3"><?=_("��ѵ�ƻ�����")?></span>
        <td>
    </tr>
</table>

<table class="TableList" width="100%">
<?
if($T_PLAN_ID)
{
    //�޸���������״̬--yc
    update_sms_status('61',$T_PLAN_ID);
}


$query = "SELECT * from HR_TRAINING_PLAN WHERE ASSESSING_OFFICER='".$_SESSION["LOGIN_USER_ID"]."' and ASSESSING_STATUS='$ASSESSING_STATUS' ORDER BY T_PLAN_ID desc";
$cursor = exequery(TD::conn(),$query, true);
while($ROW=mysql_Fetch_array($cursor))
{
    $COUNT++;

    $T_CHANNEL = $ROW["T_CHANNEL"];
    if($T_CHANNEL=="0")
        $T_CHANNEL=_("�ڲ���ѵ");
    if($T_CHANNEL=="1")
        $T_CHANNEL=_("������ѵ");

    $T_COURSE_TYPES = $ROW["T_COURSE_TYPES"];
    $T_COURSE_TYPES=get_hrms_code_name($T_COURSE_TYPES,"T_COURSE_TYPE");

    if($COUNT==1)
        echo '
    <tr class="TableHeader">
        <td nowrap align="center">��ѵ�ƻ����</td>
        <td nowrap align="center">��ѵ�ƻ�����</td>
        <td nowrap align="center">��ѵ����</td>
        <td nowrap align="center">��ѵ��ʽ</td>
        <td nowrap align="center">��ѵ�ص�</td>
        <td nowrap align="center">����</td>
    </tr>';

    if($COUNT%2==1)
        $TableLine='TableLine1';
    else
        $TableLine='TableLine2';
    $aaa= '<tr class="'.$TableLine.'">
    <td nowrap align="center">'.$ROW["T_PLAN_NO"].'</td>
    <td align="center">'.$ROW["T_PLAN_NAME"].'</td>
    <td nowrap align="center">'.$T_CHANNEL.'</td>
    <td nowrap align="center">'.$T_COURSE_TYPES.'</td>
    <td nowrap align="center">'.$ROW["T_ADDRESS"].'</td>
    <td nowrap align="center">
    <a href="#" onclick=ShowDetial("'.$ROW["T_PLAN_ID"].'")>��ϸ��Ϣ</a>&nbsp;';
    if($ASSESSING_STATUS==0)
    {
        $aaa.='<a href="#" onclick=approval("'.$ROW["T_PLAN_ID"].'","1")>��׼</a>&nbsp;<a href="#" onclick=approval("'.$ROW["T_PLAN_ID"].'","0")>�ܾ�</a>&nbsp;';
    }
    echo $aaa.='</td></tr>';
    }
    ?>
</table>
<?
if($COUNT==0)
{
    if($ASSESSING_STATUS==0)
        Message(_("��ʾ"),_("û�д����ƻ���"));
    if($ASSESSING_STATUS==1)
        Message(_("��ʾ"),_("û����׼�ƻ���"));
    if($ASSESSING_STATUS==2)
        Message(_("��ʾ"),_("û��δ��׼�ƻ���"));
}
?>
<div id="overlay"></div>
<div id="comment" class="ModalDialog" style="width:500px;">
    <div class="header"><span id="title" class="title"><?=_("�������")?></span><a class="operation" href="javascript:HideDialog('comment');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <form name="form1" method="post" onSubmit="return check_form();" action="approval.php">
        <div id="detail_body" class="body">
            <span id="confirm"></span>
            <textarea id="assessing_view" name="ASSESSING_VIEW" cols="60" rows="5" style="overflow-y:auto;" class="BigInput" wrap="yes"></textarea>
        </div>
        <input type="hidden" name="T_PLAN_ID" id="T_PLAN_ID">
        <input type="hidden" name="PASS" id="PASS">
        <div id="footer" class="footer">
            <input class="BigButton" type="submit" value="<?=_("ȷ��")?>"/>
            <input class="BigButton" onClick="HideDialog('comment')" type="button" value="<?=_("�ر�")?>"/>
        </div>
    </form>
</div>
</body>
</html>