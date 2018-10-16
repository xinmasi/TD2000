<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("����֪ͨ��ѯ");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
//$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
$ROW=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,POST_DEPT,DEPT_ID");
$POST_PRIV=$ROW["POST_PRIV"];
$DEPT_ID1=td_trim($ROW["POST_DEPT"]);
$DEPT_ID2=$ROW["DEPT_ID"];
//-------�Ϸ���У��---------
if($SEND_TIME_MIN!="")
{
    $TIME_OK=is_date($SEND_TIME_MIN);
    if(!$TIME_OK)
    {
        Message(_("����"),_("\"��������\"�ĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
}
if($SEND_TIME_MAX!="")
{
    $TIME_OK=is_date($SEND_TIME_MAX);
    if(!$TIME_OK)
    {
        Message(_("����"),_("\"��������\"�ĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
}
$PARA_ARRAY=get_sys_para("NOTIFY_EDIT_PRIV");
$NOTIFY_EDIT_PRIV=$PARA_ARRAY["NOTIFY_EDIT_PRIV"];
//--------- ���������ַ��� ---------------
$CONDITION_STR="";
if($SUBJECT!="")
    $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and SEND_TIME>='$SEND_TIME_MIN'";
if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and SEND_TIME<='$SEND_TIME_MAX'";
if($FORMAT!="")
    $CONDITION_STR.=" and FORMAT='$FORMAT'";
if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
if($PUBLISH!="")
{
    /*
    if($_SESSION["LOGIN_USER_PRIV"]!="1"&&($PUBLISH==1&& $POST_PRIV!="1"&&$NOTIFY_EDIT_PRIV==0)
    {
        Message(_("��ʾ"),_("���淢���������߲���ɾ�������޸�"));
        Button_Back();
        exit;      
    }
    */
    if($_SESSION["LOGIN_USER_PRIV"]==1)//oa����Ա
    {
        if($PUBLISH==1)
            $CONDITION_STR.=" and PUBLISH='1' ";
        else
            $CONDITION_STR.=" and PUBLISH<>'1'";
    }
    else if($NOTIFY_EDIT_PRIV==1)//����ǿɱ༭��ɾ���ģ�Ҫ�ų�����ͨ����
    {
        if($PUBLISH==1)
            $CONDITION_STR.=" and PUBLISH='1' and AUDITER!='' ";
        else
            $CONDITION_STR.=" and PUBLISH<>'1' and PUBLISH<>'2'"; //����е�Ҳ���ܱ�ɾ��
    }
    else
    {
        if($PUBLISH==1)
        {
            Message(_("��ʾ"),_("���淢�������ύ��˵����߲���ɾ��"));
            Button_Back();
            exit;      
        }
        else
            $CONDITION_STR.=" and PUBLISH<>'1' and PUBLISH<>'2'";
    }
}
else//ûѡ�񷢲�������
{
    if($_SESSION["LOGIN_USER_PRIV"]==1)//oa����Ա
    {
        $CONDITION_STR.="";
    }
    else if($NOTIFY_EDIT_PRIV==1)//����ǿɱ༭��ɾ���ģ�Ҫ�ų�����ͨ����
    {
        $CONDITION_STR.=" and ((PUBLISH='1' and AUDITER!='')||(PUBLISH<>'1' and PUBLISH<>'2')) ";   //����е�Ҳ���ܱ�ɾ��
    }
    else
    {
        $CONDITION_STR.=" and PUBLISH<>'1' and PUBLISH<>'2'";
    }
}
if($TOP!="")
    $CONDITION_STR.=" and TOP='$TOP'";
if($STAT!="")
{
    if($STAT==1)
        $CONDITION_STR.=" and BEGIN_DATE>'$CUR_DATE_U'";
    else if($STAT==2)
        $CONDITION_STR.=" and BEGIN_DATE<='$CUR_DATE_U' and (END_DATE='0' or END_DATE>='$CUR_DATE_U')";
    else if($STAT==3)
        $CONDITION_STR.=" and END_DATE!='0' and END_DATE<='$CUR_DATE_U'";
}

$CONDITION_STR1=$CONDITION_STR;
if($TO_ID!="")
{
    $CONDITION_STR1.=" and find_in_set(FROM_ID,'$TO_ID')";
}   

//ɾ������
if($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1") 
{
    $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where ATTACHMENT_NAME!=''";
    $query.=$CONDITION_STR1;
}
else if ($POST_PRIV=="0" || $POST_PRIV=="6")
{
    if ($NOTIFY_EDIT_PRIV!=0)
        $query="SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE USER.DEPT_ID='$DEPT_ID2'";
    else 
        $query="SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE NOTIFY.PUBLISH<>'1' AND USER.DEPT_ID='$DEPT_ID2' " ;
    $query.=$CONDITION_STR;     
}
else if ($POST_PRIV=="2")
{
    if ($NOTIFY_EDIT_PRIV!=0)
        $query="SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM NOTIFY WHERE find_in_set(FROM_DEPT,'$DEPT_ID1') OR FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    else 
        $query="SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM NOTIFY WHERE PUBLISH<>'1' AND (find_in_set(FROM_DEPT,'$DEPT_ID1') OR FROM_ID='".$_SESSION["LOGIN_USER_ID"]."')";   
    $query.=$CONDITION_STR;  
}

/*if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
{
   if($NOTIFY_EDIT_PRIV!=0)   
      $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where ATTACHMENT_NAME!='' and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   else
      $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where ATTACHMENT_NAME!='' and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and PUBLISH<>'1'";
   $query.=$CONDITION_STR;
}
else
{
   $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where ATTACHMENT_NAME!=''";
   $query.=$CONDITION_STR1;
}*/

$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

if ($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1")
{
    $query = "delete from NOTIFY where 1=1";
    $query.=$CONDITION_STR1;
}
else if ($POST_PRIV=="0" || $POST_PRIV=="6")
{
    if ($NOTIFY_EDIT_PRIV!=0)
        $query="DELETE NOTIFY.* FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE USER.DEPT_ID='$DEPT_ID2'";
    else 
        $query="DELETE NOTIFY.* FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE NOTIFY.PUBLISH<>'1' AND USER.DEPT_ID='$DEPT_ID2' ";
    $query.=$CONDITION_STR;      
}
else if ($POST_PRIV=="2")
{
    if ($NOTIFY_EDIT_PRIV!=0)
        $query="DELETE FROM NOTIFY WHERE find_in_set(FROM_DEPT,'$DEPT_ID1') OR FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    else 
        $query="DELETE FROM NOTIFY WHERE PUBLISH<>'1' AND (find_in_set(FROM_DEPT,'$DEPT_ID1') OR FROM_ID='".$_SESSION["LOGIN_USER_ID"]."')  ";
    $query.=$CONDITION_STR;       
}

/*if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
{
   if($NOTIFY_EDIT_PRIV!=0)
      $query = "delete from NOTIFY where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   else
      $query = "delete from NOTIFY where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and PUBLISH<>'1'";
   $query.=$CONDITION_STR;
}
else
{
   $query = "delete from NOTIFY where 1=1";
   $query.=$CONDITION_STR1;
}
*/
exequery(TD::conn(),$query);

$NOTIFY_COUNT=mysql_affected_rows();
Message("",sprintf(_("��ɾ��%d������֪ͨ"),$NOTIFY_COUNT));
Button_Back();

add_log(15,sprintf(_("��ѯɾ��%d����֪ͨ"),$NOTIFY_COUNT),$_SESSION["LOGIN_USER_ID"]);
?>
</body>
</html>