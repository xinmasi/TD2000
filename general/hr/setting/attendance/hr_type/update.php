<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");


if($BEGIN_DATE!="" && $END_DATE!="" && compare_date_time($END_DATE,$BEGIN_DATE)<0)
{
    echo "<br />";
    Message(_("����"),_("�������ڲ���С����ʼ���ڣ�"));
    Button_Back();
    exit;
}

//��ȡ������
$general = "";
$sql = "SELECT general FROM attend_config WHERE DUTY_TYPE = '$duty_type' and DUTY_TYPE!='99'";
$cursor= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($cursor))
{
    $general = $row[0];
}

//���˽ڼ���
function check_holiday($DAY)
{
    $IS_HOLIDAY=0;
    $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$DAY' and END_DATE>='$DAY'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $IS_HOLIDAY=1;
    return $IS_HOLIDAY;
}
$uid_str = "";

if($TO_ID!="")
{
    if($TO_ID=="ALL_DEPT")
    {
        $sql = "select UID from USER where NOT_LOGIN!='1' or NOT_MOBILE_LOGIN!='1'";
    }
    else
    {
        $TO_ID = td_trim($TO_ID);
        $sql = "select UID from USER where (NOT_LOGIN!='1' or NOT_MOBILE_LOGIN!='1') AND find_in_set(DEPT_ID,'$TO_ID')";
    }
    $cursor = exequery(TD::conn(),$sql);
    while($row=mysql_fetch_array($cursor))
        $uid_str .= $row["UID"].",";
}

if($COPY_TO_ID!="" || $PRIV_ID!="")
{
    $uid_str = td_trim($uid_str);
    $sql = "select UID from USER where (NOT_LOGIN!='1' or NOT_MOBILE_LOGIN!='1') AND (find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$COPY_TO_ID'))";
    $cursor = exequery(TD::conn(),$sql);
    while($row=mysql_fetch_array($cursor))
        $uid_str .= $row["UID"].",";
}
//ȥ��
$uid_str = td_trim($uid_str);
$return_str = explode(',', $uid_str);
$return_str = array_unique($return_str);
$return_str = array_filter($return_str);
$uid_str    = implode(',', $return_str);

//��ɾ����ѡʱ���ڵ���Ա�Ű�����
if($TO_ID=="ALL_DEPT")
{
    $rows  = TD::DB()->prepareQuery("delete from user_duty where '$BEGIN_DATE'<=duty_date and '$END_DATE'>=duty_date");
}
else
{
    $rows  = TD::DB()->prepareQuery("delete from user_duty where '$BEGIN_DATE'<=duty_date and '$END_DATE'>=duty_date and uid in($uid_str) ");
}


//ѭ����������
for($i=$BEGIN_DATE; $i<=$END_DATE; $i=date("Y-m-d",strtotime($i)+24*3600))
{
    if($general!="")
    {
        $week = date("w",strtotime($i));
        if(find_id($general,$week))
        {
            continue;
        } 
    }
    if($is_holiday=check_holiday($i)!=0)
    {
        continue;
    }

    $uid_array   = explode(",",$uid_str);

    $msg = "";
    for($j=0;$j<count($uid_array);$j++)
    {
        $msg .= "('".$uid_array[$j]."','$duty_type','$i')".",";
        //$msg[]= ["uid"=>$uid_array[$j], "duty_id"=>$duty_type, "duty_date"=>$i];
    }
    //TD::DB()->insert("user_duty",$msg);
    $msg = td_trim($msg);
    $sql = "INSERT INTO user_duty(uid,duty_type,duty_date) VALUES ".$msg;
    exequery(TD::conn(),$sql);
}

Message('',_("�������!"));
?>
<center>
    <input type="button" class="BigButton" value="<?=_("����")?>" onClick="location.href='batch.php'"/>
</center>
</body>
</html>
