<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ָ����������Ա�Ͳ���");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//��������ڹ������������Ա�н����򲻿����
$count = 0;
$COPY_TO_ID_ARRAY = array_filter(explode(',',$COPY_TO_ID));
for($i=0;$i<count($COPY_TO_ID_ARRAY);$i++){
    $query  = "select * from ATTEND_LEAVE_MANAGER where FIND_IN_SET('$COPY_TO_ID_ARRAY[$i]',MANAGERS)";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor)){
        $count++;
    }
}
if($count){
    Message ( _ ( "" ), _ ( "��������Ա�ظ����ô����ڹ��򲻿����!" ) );
    Button_Back ();
    exit ();
}else{
    $query="insert into ATTEND_LEAVE_MANAGER(MANAGERS,DEPT_ID_STR) values('$COPY_TO_ID','$TO_ID')";
    exequery(TD::conn(),$query);
    header("location: index.php?connstatus=1");
}
?>
</body>
</html>
