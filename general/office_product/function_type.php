<?
include_once("inc/auth.inc.php"); 
//��ȡ�칫��Ʒoffice_products�������  $idΪ��Ʒ��id $typeΪҪ���ҵ��ֶ�
function get_office_name($id,$type='pro_name')
{
    $name='';
    $query="SELECT $type FROM office_products WHERE pro_id='{$id}'";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
    {
        $name=$ROW[$type];
    }
    return $name;
}
//������Ʒid ��ȡ�칫��Ʒ�����������
function get_office_type($id,$type='type_name')
{
    $id=get_office_name($id,'office_protype');
    $name='';
    $query="SELECT $type FROM office_type WHERE id='{$id}'";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
    {
        $name=$ROW[$type];
    }
    return $name;
}
//������Ʒid ��ȡ�칫��Ʒ����������
function get_office_depository($id,$type='OFFICE_TYPE_ID')
{
    $id=get_office_type($id,'type_depository');
    $name='';
    $query="SELECT $type FROM office_depository WHERE id='{$id}'";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
    {
        $name=$ROW[$type];
    }
    return $name;
}

//��ȡ�칫��Ʒ����������� $idΪ�칫��Ʒid
function get_depository_name($id)
{
    $query="SELECT office_protype FROM office_products WHERE pro_id='{$id}'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $type = $ROW['office_protype'];
    }
    $query="SELECT depository_name from office_depository WHERE find_in_set('{$type}',office_type_id)";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $depository_name = $ROW['depository_name'];
        return $depository_name;
    }
}
/**
 * ���ݲ��Ż�����Ʒ����id���select��������
 * @param unknown $dept_id
 * @return string
 */
function get_depository($search_type,$id,$num=-1)
{
    $html = '';
    $id_array = explode(',',$id); 
    $where = "";
    if(count($id_array)>1)
    {
        for($i=0;$i<count($id_array);$i++)
        {
            $where .= "FIND_IN_SET('{$id_array[$i]}',DEPT_ID) or ";
        }
    }
    else
    {
        $where = "FIND_IN_SET('$id',DEPT_ID) or ";
    }
    if($search_type == 'dept')
    {
        if($_SESSION["LOGIN_USER_PRIV"]==1)
        {
            $query = "select * from OFFICE_DEPOSITORY ";
        }else
        {
            $query = "select * from OFFICE_DEPOSITORY where ".$where." DEPT_ID = '' or  DEPT_ID = 'ALL_DEPT'";
        }        
    }
    else if($search_type == 'office_type')
    {
        $query = "select * from OFFICE_DEPOSITORY ";
    }
    else if($search_type == 'dept_aut')
    {
        if($_SESSION["LOGIN_USER_PRIV"]==1)
        {
            $query = "select * from OFFICE_DEPOSITORY ";
        }else
        {
            $query = "select * from OFFICE_DEPOSITORY where (".$where." DEPT_ID = '' or  DEPT_ID = 'ALL_DEPT') and find_in_set('{$_SESSION["LOGIN_USER_ID"]}',MANAGER)";
        }
    }
    $cursor = exequery(TD::conn(),$query);
    while($ROW = mysql_fetch_array($cursor))
    {
        if($ROW['OFFICE_TYPE_ID']==$num)
        {
            $html .=  "<option selected value=".$ROW['OFFICE_TYPE_ID'].">".$ROW['DEPOSITORY_NAME']."</option>";
        }else{
            $html .=  "<option value=".$ROW['OFFICE_TYPE_ID'].">".$ROW['DEPOSITORY_NAME']."</option>";
        }
        
    }
    return $html;
}
//���ݰ칫��Ʒ�����Ա�û����û�������ɫ��ȡ������Ŀ��µ�������Ʒid  gy 2014-7-15
function get_transhistory($username)
{
    $str='';
    $sql="SELECT id from office_type where TYPE_DEPOSITORY in(SELECT id from office_depository where FIND_IN_SET('{$username}',MANAGER) or FIND_IN_SET('{$_SESSION['LOGIN_USER_PRIV']}',PRIV_ID))";
    $cursor = exequery(TD::conn(),$sql);
    while($ROW = mysql_fetch_array($cursor))
    {
        $str.=$ROW['id'].',';
    }
    $num = substr($str,0,-1);
    if(empty($num)){
        return 0;
    }
    $sql = "select pro_id from office_products where office_protype in ({$num}) or PRO_AUDITER = '{$username}'";
    $cursor = exequery(TD::conn(),$sql);
    $str='';
    while($ROW = mysql_fetch_array($cursor))
    {
        $str.=$ROW['pro_id'].',';
    }
    return substr($str,0,-1);
}
//������Ʒid��ȡ��ǰ���͵���
function get_product_num($id)
{
    $arr=array();
    $sql="SELECT pro_price,pro_stock from office_products where pro_id='{$id}'";
    $cursor = exequery(TD::conn(),$sql);
    while($ROW = mysql_fetch_array($cursor))
    {
        $arr=$ROW;
    }
    return $arr;
}
//������Ʒid��ȡ��id�Ϳ����Ա
function get_depository_id($id)
{
    $query="SELECT office_protype FROM office_products WHERE pro_id='{$id}'";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
    {
        $type=$ROW['office_protype'];
    }
    $query="SELECT a.id as de_id, a.depository_name, a.pro_keeper from office_depository a left join office_type b on a.id=b.type_depository WHERE b.id='{$type}'";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
    {
        return $ROW;
    }
}
//ͨ���칫��Ʒ���id��ȡ�������
function get_type_name($id)
{
    $sql="select type_name from office_type where id='{$id}'";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW = mysql_fetch_array($cursor))
    {
        return $ROW['type_name'];
    }
}
//ͨ���칫��Ʒid��ȡ�������
function getTypeNameByProid($pro_id)
{
    $query="SELECT office_protype FROM office_products WHERE pro_id='{$pro_id}'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $type = $ROW['office_protype'];
    }
    $sql="SELECT type_name FROM office_type WHERE id='{$type}'";
    $result = exequery(TD::conn(), $sql);
    if($ROW2 = mysql_fetch_array($result))
    {
        $type_name = $ROW2['type_name'];
        return $type_name;
    }
}
?>