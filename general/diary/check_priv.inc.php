<?

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=4;
include_once("inc/my_priv.php");
include_once("inc/utility_org.php");


//��ȡģ�����ԱȨ��
if(is_module_manager(2) && $_SESSION["LOGIN_USER_PRIV"]!=1)
{
	$DEPT_PRIV = 1;
	$ROLE_PRIV = 2;
	
}

//---------��һ�μ���ҳ�棬Ҫ���ݹ���Χ�г���Ա����������ʾ������----------
if($DEPT_PRIV=="0")  //������
{
    $query = "select DEPT_ID from DEPARTMENT where DEPT_PARENT='".$_SESSION["LOGIN_DEPT_ID"]."'";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $DEPT_IDS.= $ROW["DEPT_ID"].",";
    }
    $DEPT_IDS = td_trim($DEPT_IDS);
    if($DEPT_IDS!="")
    {
        $WHERE_STRS.=" and (b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'or b.DEPT_ID in($DEPT_IDS))";
    }
    else
    {
        $WHERE_STRS.=" and b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
    }
    //$WHERE_STRS.=" and b.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
}
else if($DEPT_PRIV=="2") //ָ������
{
    $DEPT_ID_STR=td_trim($DEPT_ID_STR);
    if($DEPT_ID_STR!="")
        $WHERE_STRS.=" and b.DEPT_ID in ($DEPT_ID_STR)";
}
else if($DEPT_PRIV=="3")  //ָ����Ա
{  
    $USER_ID_STR=td_trim($USER_ID_STR);
    if($USER_ID_STR!="")
        $WHERE_STRS.=" and find_in_set(b.USER_ID,'$USER_ID_STR')";
}
else if($DEPT_PRIV=="4")  //����
{
    $WHERE_STRS.=" and b.USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
}
if($ROLE_PRIV == "0")   //�ͽ�ɫ���û�
    $WHERE_STRS.=" and g.PRIV_NO>'$MY_PRIV_NO'";
else if($ROLE_PRIV == "1")  //ͬ��ɫ�͵ͽ�ɫ���û�
    $WHERE_STRS.=" and g.PRIV_NO>='$MY_PRIV_NO'";
else if($ROLE_PRIV == "3")  //ָ����ɫ���û�  2:���н�ɫ���û�
{
	$PRIV_ID_STR=td_trim($PRIV_ID_STR);
	if($PRIV_ID_STR!="")
	{
        $WHERE_STRS.=" and g.USER_PRIV in ($PRIV_ID_STR)";
    }
}
?>