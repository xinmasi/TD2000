<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ڹ�����Ա����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//����ÿ��������������������Ա�н����򲻿�����
$count = 0;
$COPY_TO_ID_ARRAY = array_filter(explode(',',$COPY_TO_ID));
for($i=0;$i<count($COPY_TO_ID_ARRAY);$i++){
    $query  = "select * from attend_manager where FIND_IN_SET('$COPY_TO_ID_ARRAY[$i]',MANAGERS) and MANAGER_ID!='$MANAGER_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor)){
        $count++;
    }
}
if($count){
    Message ( _ ( "" ), _ ( "������Ա�ظ����������޸�!" ) );
    Button_Back ();
    exit ();
}else{
    $query="update ATTEND_MANAGER set DEPT_ID_STR='$TO_ID',MANAGERS='$COPY_TO_ID' where MANAGER_ID='$MANAGER_ID'";
    exequery(TD::conn(),$query);
    header("location: index.php?connstatus=1");
}
?>
</body>
</html>