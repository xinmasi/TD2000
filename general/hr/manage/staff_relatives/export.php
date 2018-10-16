<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
ob_end_clean();

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------�Ϸ���У��---------

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "STAFF_NAME,MEMBER,RELATIONSHIP,BIRTHDAY,POLITICS,JOB_OCCUPATION,WORK_UNIT,OFFICE_TEL,PERSONAL_TEL,HOME_TEL,UNIT_ADDRESS,HOME_ADDRESS,REMARK";
else
    $OUTPUT_HEAD = _("��λԱ��").","._("��Ա����").","._("�뱾�˹�ϵ").","._("��������").","._("������ò").","._("ְҵ").","._("������λ").","._("��ϵ�绰����λ��").","._("��ϵ�绰�����ˣ�").","._("��ϵ�绰����ͥ��").","._("��λ��ַ").","._("��ͥ��ַ").","._("��ע");

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("����ϵ��Ϣ"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($JOB_OCCUPATION!="")
    $CONDITION_STR.=" and JOB_OCCUPATION like '%".$JOB_OCCUPATION."%'";
if($STAFF_NAME!="")
    $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($MEMBER!="")
    $CONDITION_STR.=" and MEMBER='$MEMBER'";
if($RELATIONSHIP!="")
    $CONDITION_STR.=" and RELATIONSHIP='$RELATIONSHIP'";
if($WORK_UNIT!="")
    $CONDITION_STR.=" and WORK_UNIT like '%".$WORK_UNIT."%'";

$CONDITION_STR= hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_RELATIVES where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXPERIENCE_COUNT++;
    $MEMBER=format_cvs($ROW["MEMBER"]);
    $RELATIONSHIP=format_cvs($ROW["RELATIONSHIP"]);
    $BIRTHDAY=format_cvs($ROW["BIRTHDAY"]);
    $POLITICS=format_cvs($ROW["POLITICS"]);
    $WORK_UNIT=format_cvs($ROW["WORK_UNIT"]);
    $UNIT_ADDRESS=format_cvs($ROW["UNIT_ADDRESS"]);
    $POST_OF_JOB=format_cvs($ROW["POST_OF_JOB"]);
    $OFFICE_TEL=format_cvs($ROW["OFFICE_TEL"]);
    $HOME_ADDRESS=format_cvs($ROW["HOME_ADDRESS"]);
    $HOME_TEL=format_cvs($ROW["HOME_TEL"]);
    $JOB_OCCUPATION=format_cvs($ROW["JOB_OCCUPATION"]);
    $STAFF_NAME=format_cvs($ROW["STAFF_NAME"]);
    $PERSONAL_TEL=format_cvs($ROW["PERSONAL_TEL"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME =format_cvs($ROW["ATTACHMENT_NAME"]);
    $ADD_TIME =format_cvs($ROW["ADD_TIME"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);

    $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");
    $STAFF_NAME1=td_trim(GetUserNameById($STAFF_NAME));
    if($STAFF_NAME1=="")
    {
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $STAFF_NAME1=$ROW2["STAFF_NAME"];
        $STAFF_NAME1=$STAFF_NAME1._("���û���ɾ����");
    }
    $POLITICS=get_hrms_code_name($POLITICS,"STAFF_POLITICAL_STATUS");
    $OUTPUT = $STAFF_NAME1.",".$MEMBER.",".$RELATIONSHIP.",".$BIRTHDAY.",".$POLITICS.",".$JOB_OCCUPATION.",".$WORK_UNIT.",".$OFFICE_TEL.",".$PERSONAL_TEL.",".$HOME_TEL.",".$UNIT_ADDRESS.",".$HOME_ADDRESS.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
