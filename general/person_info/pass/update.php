<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("�޸�����");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/tdPass.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>

<body class="bodycolor">

<?
if(MYOA_IS_DEMO)
{
    Message(_("��ʾ"),_("��ʾ�治���޸�����"));
    Button_Back();
    exit;
}

$PARA_ARRAY=get_sys_para("SEC_PASS_MIN,SEC_PASS_MAX,SEC_PASS_SAFE");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
    $$PARA_NAME = $PARA_VALUE;
//-------------����Ϸ��Լ���-------------------------------------------------
if(strlen($PASS1)<$SEC_PASS_MIN||strlen($PASS2)<$SEC_PASS_MIN||strlen($PASS1)>$SEC_PASS_MAX||strlen($PASS2)>$SEC_PASS_MAX)
{
    Message(_("����"),sprintf(_("���볤��Ӧ%sλ!"),$SEC_PASS_MIN."-".$SEC_PASS_MAX));
    Button_Back();
    exit;
}

if($PASS1!=$PASS2)
{
    Message(_("����"),_("����������벻һ�£�"));
    Button_back();
    exit;
}

if(strstr($PASS1,"\'")!=false)
{
    Message(_("����"),_("�������к��зǷ��ַ�"));
    Button_back();
    exit;
}

if($PASS1==$PASS0)
{
    Message(_("����"),_("�����벻����ԭ������ͬ��"));
    Button_back();
    exit;
}
if($SEC_PASS_SAFE=="1" && (!preg_match("/[a-z]/i",$PASS1)||!preg_match("/[0-9]/i",$PASS1)))
{
    Message(_("����"),_("�������ͬʱ������ĸ������!"));
    Button_Back();
    exit;
}


$query = "SELECT user_id, PASSWORD,USEING_KEY from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PASSWORD=$ROW["PASSWORD"];
    $USER_ID = $ROW["user_id"];
    $USEING_KEY=$ROW["USEING_KEY"];
    if(crypt($PASS0,$PASSWORD)!= $PASSWORD)
    {
        Message(_("����"),_("�����ԭ�������!"));
        Button_back();
        exit;
    }
}
$PASS1=crypt($PASS1);

$CUR_TIME=date("Y-m-d H:i:s",time());
$query="update USER SET PASSWORD='$PASS1',LAST_PASS_TIME='$CUR_TIME' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

if($USEING_KEY=="1")
    Message(_("��ʾ"),_("�û��������޸�,�����USB�û�KEY,�����³�ʼ��KEY"));
else
    Message(_("��ʾ"),_("�û��������޸�!"));
add_log(14,"",$_SESSION["LOGIN_USER_ID"]);

//UCenter����
$SYS_PARA_ARRAY = get_sys_para('USE_DISCUZ');
$USE_DISCUZ = $SYS_PARA_ARRAY["USE_DISCUZ"];

if($USE_DISCUZ > 0)
{
    include_once("inc/uc_client/config.inc.php");

    //�Ƿ�����Ӧ��
    if(defined("UC_APPID"))
    {
        include_once("inc/uc_client/client.php");
        switch($USE_DISCUZ)
        {
            case 1:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_NAME"];
                break;
            case 2:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_ID"];
                break;
            case 3:
                $UC_USER_NAME = $_SESSION["LOGIN_BYNAME"];
                break;
            default:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_NAME"];
        }
        $query="select EMAIL FROM USER where UID='".$_SESSION["LOGIN_UID"]."'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW = mysql_fetch_array($cursor))
        {
            $USER_EMAIL = $ROW["EMAIL"];
            if($USER_EMAIL == "")
            {
                $USER_EMAIL = "noname@noname.com";
            }
        }
        $uc_result = uc_user_edit($UC_USER_NAME, $PASS0, $PASS2, $USER_EMAIL);
        if($uc_result < 0)
        {
            Message(_("����"),_("UCenterͬ������ʧ��!"));
        }
    }
}
if($USEING_KEY=="1")
{
    ?>
    <br>
    <div align="center">
        <input type="button" value="<?=_("��ʼ��USB KEY")?>" class="BigButton" onClick="CREAT_KEY('<?=$USER_ID?>')">
    </div>
    <script>
        function CREAT_KEY(USER_ID)
        {
            msg='<?=_("ȷ���Ѳ����û�KEY��")?>';
            if(window.confirm(msg))
            {
                var theDevice=$("tdPass");
                var current_key_user = READ_KEYUSER(theDevice);
                if( current_key_user != -1 && current_key_user != USER_ID ){//�����ͬһ�û�������ʾ��Ϣ
                    //��ʱ��Ҫʹ��AJAX��ȡ�Ѿ��󶨵��û�����ʵ����,��������ʾʱ�����Ի�
                    var xmlHttpObj=getXMLHttpObj();
                    var theURL="/general/system/user/get_key_user_info.php?USER_ID="+current_key_user;
                    xmlHttpObj.open("GET",theURL,true);
                    var responseText="";
                    xmlHttpObj.onreadystatechange=function()
                    {
                        if(xmlHttpObj.readyState==4)
                        {
                            KEY_USERINFO=xmlHttpObj.responseText;
                            var key_msg = sprintf('<?=_("��USB KEY�Ѱ��û� %s,�������ȷ��USB KEY��ʵ�ְ�!")?>', KEY_USERINFO);
                            alert(key_msg);
                        }
                    }
                    xmlHttpObj.send(null);
                }else{//ֱ�ӳ�ʼ������
                    URL="create_key.php?USER_ID=" + USER_ID;
                    window.location=URL;
                }
            }
        }
    </script>
    <object id="tdPass" name="tdPass" CLASSID="clsid:0272DA76-96FB-449E-8298-178876E0EA89"	CODEBASE="<?=MYOA_JS_SERVER?>/static/js/tdPass_<?=(stristr($_SERVER['HTTP_USER_AGENT'], 'x64') ? 'x64' : 'x86')?>.cab#version=1,2,12,1023" BORDER="0" VSPACE="0" HSPACE="0" ALIGN="TOP" HEIGHT="0" WIDTH="0"></object>
    <?
    exit;
}
?>

<div align="center">
    <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?IS_MAIN=1'">
</div>
</body>
</html>
