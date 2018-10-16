<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("指定考勤审批人员和部门");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?





//检验该考勤审批规则，如果审批人员有交集则不可添加
$count = 0;
$COPY_TO_ID_ARRAY = array_filter(explode(',',$COPY_TO_ID));
for($i=0;$i<count($COPY_TO_ID_ARRAY);$i++){
    $query  = "select * from attend_manager where FIND_IN_SET('$COPY_TO_ID_ARRAY[$i]',MANAGERS)";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor)){
        $count++;
    }
}
if($count){
    Message ( _ ( "" ), _ ( "审批人员重复，该考勤审批规不可添加!" ) );
    Button_Back ();
    exit ();
}else{
    $query="insert into ATTEND_MANAGER(MANAGERS,DEPT_ID_STR) values('$COPY_TO_ID','$TO_ID')";
    exequery(TD::conn(),$query);
    header("location: index.php?connstatus=1");
}
?>
</body>
</html>
