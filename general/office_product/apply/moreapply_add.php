<?
/**
 * �������촦��ҳ��
 */
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/header.inc.php");
include_once("../function_type.php");
include_once("inc/utility_sms1.php");

$json=stripslashes($json);
$res=json_decode($json);
$sql="select max(CYCLE_NO) as max from office_transhistory ";
$cursor2 =exequery ( TD::conn (), $sql );
while ( $ROW = mysql_fetch_array ( $cursor2 ) )
{
    $max=$ROW['max']+1;
}
$pro_name = '';
$status = 0;
foreach($res as $u){
    $arr=array();
    $arr=get_depository_id($u->id);//��ȡ�����Ա
    //����id��ȡ��Ʒ�ĵǼ�����
    $query="SELECT OFFICE_PRODUCT_TYPE,PRO_PRICE FROM office_products WHERE pro_id='{$u->id}'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $TRANS_FLAG = $ROW['OFFICE_PRODUCT_TYPE'];
        $PRO_PRICE  = $ROW['PRO_PRICE'];
    }
    $sql2="SELECT TRANS_ID,PRO_ID FROM office_transhistory WHERE PRO_ID='{$u->id}' and BORROWER='{$_SESSION['LOGIN_USER_ID']}' and TRANS_FLAG='2' and (TRANS_STATE='0' or DEPT_STATUS='0')";
    $re1=exequery(TD::conn(),$sql2);
    
    if($row = mysql_fetch_array($re1))
    {
        $sql3="select PRO_NAME from office_products where PRO_ID ='".$row["PRO_ID"]."'";
        $re2=exequery(TD::conn(),$sql3);
        if($row2 = mysql_fetch_array($re2))
        {
            $pro_name.= $row2["PRO_NAME"].',';
            $status = 1;
        }
    }
    
    if($status == 0)
    {
        if($MANAGER=="")
        {
            //��ȡ����Ȩ���û�
            $query= "SELECT a.PRO_AUDITER, a.PRO_NAME, c.MANAGER, c.PRO_KEEPER FROM office_products a left outer join office_type b on a.OFFICE_PROTYPE = b.ID left outer join office_depository c on b.TYPE_DEPOSITORY = c.ID WHERE a.PRO_ID='{$u->id}'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $PRO_AUDITER .= $ROW["PRO_AUDITER"];//����Ȩ���û�
                if($ROW["PRO_AUDITER"]=="")
                {
                    $MANAGER_GL .= $ROW["MANAGER"];  //�ֿ����Ա
                }
            }
        }
        $type = ($MANAGER != "") ? "0" : "1";
        $trans_qty=-$u->value;
        $query="insert into office_transhistory(pro_id,borrower,trans_flag,remark,trans_date,trans_state,trans_qty,pro_keeper,dept_id,dept_manager,dept_status,cycle_no,cycle,grant_status,fact_qty,price)values('{$u->id}','{$_SESSION["LOGIN_USER_ID"]}',$TRANS_FLAG,'{$REMARK}',current_date(),0,'$trans_qty','{$arr['pro_keeper']}','{$_SESSION["LOGIN_DEPT_ID"]}','{$MANAGER}','$type','$max',1,0,'{$u->value}','$PRO_PRICE')";
        $cursor = exequery ( TD::conn (), $query );
    }
}
if($status==1)
{
    $pro_name_new = td_trim($pro_name);
    if($pro_name_new!='')
    {
        Message (_("����"),_($pro_name_new."�Ľ����������ڴ�����,�޷��ظ��Ǽǣ�"));
        ?>
        <br><center><input type="button" class="BigButtonA" value="<?=_("����")?>" onclick="window.history.go(-1)"></center>
        <?
        exit;
    }
}
if($cursor)
{
    //��������
    if($MANAGER!="")
    {
        $REMIND_URL="1:office_product/dept_approval/pending_list.php";
        $SMS_CONTENT=sprintf(_("������%s�������칫��Ʒ���롣"),$_SESSION["LOGIN_USER_NAME"]);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER,43,$SMS_CONTENT,$REMIND_URL);
    }else
    {
        if($PRO_AUDITER!="")//����Ȩ���û�
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("������%s�������칫��Ʒ���롣"),$_SESSION["LOGIN_USER_NAME"]);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$PRO_AUDITER,43,$SMS_CONTENT,$REMIND_URL);
        }
        if($MANAGER_GL!="")//�ֿ����Ա
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("������%s�������칫��Ʒ���롣"),$_SESSION["LOGIN_USER_NAME"]);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER_GL,43,$SMS_CONTENT,$REMIND_URL);
        }
    }
    Message (_("��ʾ"),_("�����ɹ���"));
}else
{
    Message (_("����"), _("�뷵�����ԣ�"));
}

?>
<br><center><input type="button" class="BigButtonA" value="<?=_("����")?>" onclick="window.history.go(-1)"></center>

