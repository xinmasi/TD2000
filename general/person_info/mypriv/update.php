<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");
//URL:webroot\general\person_info\avatar\update.php

$HTML_PAGE_TITLE = _("�˻���Ϣ����");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(!$EDIT_BYNAME)
{
    Message(_("����"), _("�û����������޸ģ�����ϵϵͳ����Ա"));
    Button_Back();
    exit;
}
/*
if($BYNAME==$_SESSION["LOGIN_USER_ID"])
{
    Message(_("����"),_("�û����ѱ�ռ��"));
    Button_Back();
    exit;
}
*/

if(td_trim($BYNAME)!="")
{
    $query="select * from USER where UID!='".$_SESSION["LOGIN_UID"]."' and BYNAME='$BYNAME'";
    $cursor= exequery(TD::conn(),$query,true);
    if($ROW=mysql_fetch_array($cursor))
    {
        Message(_("����"),sprintf(_("�û��� %s �ѱ�ռ��"),$BYNAME));
        Button_Back();
        exit;
    }
}else{
    Message(_("����"),sprintf(_("�û�������Ϊ��")));
    Button_Back();
    exit;
}

$query ="update USER set BYNAME='$BYNAME' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

//Message(_("��ʾ"),_("�ѱ����޸�"));

//------------------- ���� -----------------------
Message(_("��ʾ"),_("�ѱ����޸�"));
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='index.php?start=<?=$start?>&IS_MAIN=1'"></center>    
</body>
</html>
