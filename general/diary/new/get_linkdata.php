<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_email.php");
ob_end_clean();

//��ȡ�����������Ϣ
$LINK_STR = "<b>".$CUR_DATE._("������������:")."</b>";
$BEGIN_TIME = $CUR_DATE." 00:00:00";
$END_TIME = $CUR_DATE." 23:59:59";
$BEGIN_TIME_U = strtotime($BEGIN_TIME);
$END_TIME_U = strtotime($END_TIME);
if($BEGIN_TIME != "")
{
    $WHERE .=" and SEND_TIME >= '$BEGIN_TIME' ";
}
if($END_TIME != "")
{
    $WHERE .= " and SEND_TIME <= '$END_TIME' ";
}
$query = "SELECT SUBJECT FROM notify  WHERE FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' ".$WHERE." and PUBLISH=1 ";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("��������").$COUNT._("��")."&nbsp;&nbsp;&nbsp; </b>";
    $LINK_STR .= _("����Ϊ��")."";    
    while($ROW = mysql_fetch_array($cursor))
    {
        $SUBJECT = $ROW['SUBJECT'];
        $LINK_STR .= $SUBJECT._("��");
    }
}
//��ȡ�����������Ϣ
$query = "SELECT SUBJECT FROM notify WHERE AUDITER='".$_SESSION["LOGIN_USER_ID"]."' and AUDIT_DATE='$CUR_DATE' and (PUBLISH='1' or PUBLISH='3')";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("��������").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $LINK_STR .= _("����Ϊ��");    
    while($ROW = mysql_fetch_array($cursor))
    {
        $SUBJECT = $ROW['SUBJECT'];
        $LINK_STR .= $SUBJECT._("��");
    }
}
//��ȡ�������ŵ���Ϣ
$query = "SELECT SUBJECT FROM news WHERE PROVIDER='".$_SESSION["LOGIN_USER_ID"]."' and NEWS_TIME>= '$BEGIN_TIME' and  NEWS_TIME<= '$END_TIME' and PUBLISH=1 ";
$cursor = exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("��������").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $LINK_STR .= _("����Ϊ��")."";    
    while($ROW = mysql_fetch_array($cursor))
    {
        $SUBJECT = $ROW['SUBJECT'];
        $LINK_STR .= $SUBJECT._("��");
    }
}
//��ȡ�ʼ�������Ϣ
$query = "SELECT SUBJECT FROM email_body WHERE FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and SEND_FLAG='1' and SEND_TIME >'$BEGIN_TIME_U' and SEND_TIME <'$END_TIME_U'";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("�����ʼ�").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $LINK_STR .= _("����Ϊ��")."";
    while($ROW = mysql_fetch_array($cursor))
    {
        $SUBJECT = $ROW['SUBJECT'];    
        $LINK_STR .= $SUBJECT._("��");
    }
}
//��ȡ������������Ϣ
$query = "SELECT RUN_ID,PRCS_FLAG FROM flow_run_prcs WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."'  and DELIVER_TIME >='$BEGIN_TIME' and DELIVER_TIME <='$END_TIME' and (PRCS_FLAG=4 or PRCS_FLAG=3) ";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("��������").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $PRCS_FLAG_TEXT = "";
    while($ROW = mysql_fetch_array($cursor))
    {
        $RUN_ID = $ROW['RUN_ID'];
        $PRCS_FLAG = $ROW['PRCS_FLAG'];
        $querys = "SELECT RUN_NAME FROM flow_run WHERE RUN_ID='$RUN_ID' ";
        $cursors = exequery(TD::conn(),$querys);
        if($ROWS = mysql_fetch_array($cursors))
        {
            $RUN_NAME = $ROWS["RUN_NAME"];
        }
        if($PRCS_FLAG==1){
            $PRCS_FLAG_TEXT = _("δ����");
        }
        else if($PRCS_FLAG==2){
            $PRCS_FLAG_TEXT = _("������");
        }
        else if($PRCS_FLAG==3 || $PRCS_FLAG==4){
            $PRCS_FLAG_TEXT = _("�Ѱ��");
        }
        else if($PRCS_FLAG==6){
            $PRCS_FLAG_TEXT = _("�ѹ���");
        }
        $LINK_STR .= _("��ˮ�ţ�").$RUN_ID. _("| ����Ϊ��") .$RUN_NAME." | ".$PRCS_FLAG_TEXT._("��");
    }
}
//��ȡ����ɵĹ��������ճ���Ϣ
$query = "SELECT CAL_TIME,END_TIME,CONTENT FROM calendar WHERE CAL_TYPE!='2' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CAL_TIME>='$BEGIN_TIME_U'  and  END_TIME <= '$END_TIME_U' ";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("����ɹ����������͵��ճ�").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $LINK_STR .= _("����Ϊ��")."";
    while($ROW = mysql_fetch_array($cursor))
    {
        $CAL_TIME = $ROW['CAL_TIME'];
        $END_TIME = $ROW['END_TIME'];
        $CONTENT = $ROW['CONTENT'];
        $CAL_TIME = date('H:i', $CAL_TIME); 
        $END_TIME = date('H:i', $END_TIME); 
        $LINK_STR .= $CAL_TIME."-".$END_TIME."  ".$CONTENT._("��");
    }
}
//��ȡ����ɵĹ�����������
$query = "SELECT SUBJECT FROM task WHERE TASK_TYPE!='2' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE>='$BEGIN_TIME'  and  END_DATE <= '$END_TIME' and  TASK_STATUS='3' ";
$cursor = exequery(TD::conn(), $query);
$COUNT = mysql_num_rows($cursor);
if($COUNT > 0)
{
    $LINK_STR .= "<br><br><b>"._("����ɹ����������͵�����").$COUNT._("��")."&nbsp;&nbsp;&nbsp;</b>";
    $LINK_STR .= _("����Ϊ��")."";    
    while($ROW = mysql_fetch_array($cursor))
    {
        $SUBJECT = $ROW['SUBJECT'];
        $LINK_STR .= $SUBJECT._("��");
    }
}
echo $LINK_STR;
?>

