<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
include_once ("inc/utility_all.php");
include_once("../function_type.php");
ob_end_clean();

$EXCEL_OUT = array (
    _("办公用品名称"),
    _("办公用品库"),
    _("办公用品类别"),
    _("登记类型"),
    _("申请人"),
    _("所属部门"),
    _("数量"),
    _("价格"),
    _("申请日期"),
    _("审批状态"),
    _("状态"),
    _("备注"),

);
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("办公用品申请记录"));
$objExcel->addHead($EXCEL_OUT);


$num= get_transhistory($_SESSION['LOGIN_USER_ID']);

if(!empty($RECORDER_ID))
{
    $where.=" and borrower='{$RECORDER_ID}' ";
}
if($PRO_ID!='-1'&&$PRO_ID!='')
{
    $where.=" and PRO_ID='{$PRO_ID}'";
}
if($_GET['project-id']!='')
{
    $where.=" and PRO_ID='{$_GET['project-id']}'";
}
if(isset($GRANT_STATUS) && $GRANT_STATUS!="")
{
    $where.=" and grant_status='{$GRANT_STATUS}'";
}
if(!empty($FROM_DATE))
{
    $where .= " and trans_date>='{$FROM_DATE}'";
}
if(!empty($TO_DATE))
{
    $where.=" and trans_date<='{$TO_DATE}'";
}

if(empty($num))
{
    $query = "SELECT * FROM office_transhistory WHERE FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',DEPT_MANAGER)";
}else
{
    $query = "SELECT * FROM office_transhistory WHERE ((TRANS_FLAG in(1,2) and pro_id in ({$num})) or (FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',DEPT_MANAGER))) ";
}

$query  = $query.$where."order by TRANS_DATE desc";
$cursor = exequery(TD::conn(),$query);
while($arr=mysql_fetch_array($cursor))
{
    $TRANS_ID      = $arr['TRANS_ID'];
    $PRO_ID        = $arr['PRO_ID'];
    $TRANS_FLAG    = $arr['TRANS_FLAG'];
    $FACT_QTY      = $arr['FACT_QTY'];
    $DEPT_MANAGER  = $arr['DEPT_MANAGER'];
    $DEPT_STATUS   = $arr['DEPT_STATUS'];
    $TRANS_STATE   = $arr['TRANS_STATE'];
    $TRANS_DATE    = $arr['TRANS_DATE'];
    $REMARK        = $arr['REMARK'];
    $RETURN_STATUS = $arr['RETURN_STATUS'];
    $BORROWER      = $arr['BORROWER'];
    $GRANT_STATUS  = $arr['GRANT_STATUS'];

    $sql = "SELECT PRO_NAME,PRO_PRICE FROM office_products WHERE PRO_ID = '$PRO_ID'";
    $cursor1 = exequery(TD::conn(),$sql);
    if($res=mysql_fetch_array($cursor1))
    {
        $PRO_NAME  = $res['PRO_NAME'];
        $PRO_PRICE = $res['PRO_PRICE'];
    }
    $TYPE_NAME = get_office_type($PRO_ID);
    $DEPOSITORY_NAME = get_office_depository($PRO_ID,"DEPOSITORY_NAME");

    $sql1 = "SELECT USER_NAME,DEPT_NAME FROM user,department WHERE user.DEPT_ID = department.DEPT_ID AND user.USER_ID = '$BORROWER'";
    $cursor2 = exequery(TD::conn(),$sql1);
    if($row=mysql_fetch_array($cursor2))
    {
        $USER_NAME  = $row['USER_NAME'];
        $DEPT_NAME  = $row['DEPT_NAME'];
    }
    if($DEPT_NAME=="")
    {
        $DEPT_NAME = _("离职人员/外部人员");
    }
    if(!empty($DEPT_MANAGER))
    {
        if($DEPT_STATUS==0)
        {
            $stname = _("部门审批人未审核");
        }elseif($DEPT_STATUS==2)
        {
            $stname = _("部门审批未通过");
        }else
        {
            $stname = $TRANS_STATE==0?_("仓库管理员未审批"):($TRANS_STATE==1?_("审核通过"):_("仓库管理员审批未通过"));
        }
    }else
    {
        $stname = $TRANS_STATE==0?_("仓库管理员未审批"):($TRANS_STATE==1?_("审核通过"):_("仓库管理员审批未通过"));
    }
    if($TRANS_STATE==1)
    {
        if($TRANS_FLAG==1)
        {
            $STATE = $GRANT_STATUS==0?_("未发放"):_("已发放");
        }
        else
        {
            $STATE = $GRANT_STATUS==0?_("未发放"):($RETURN_STATUS==0?_("未归还"):_("已归还"));
        }
    }else
    {
        $STATE = "";
    }

    $TRANS_FLAG_NAME = $TRANS_FLAG==1?_("领用"):_("借用");

    $EXCEL_OUT='"'.str_replace('"','""',$PRO_NAME).'","'.$DEPOSITORY_NAME.'","'.$TYPE_NAME.'","'.$TRANS_FLAG_NAME.'","'.$USER_NAME.'","'.$DEPT_NAME.'","'.abs($FACT_QTY).'","'.str_replace('"','""',$PRO_PRICE).'","'.$TRANS_DATE.'","'.$stname.'","'.$STATE.'","'.$REMARK.'"';
    $objExcel->addRow($EXCEL_OUT);
}
$objExcel->Save();

?>
