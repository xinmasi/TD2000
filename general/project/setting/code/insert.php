<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加项目代码");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <?

	if($CODE_NO == 0){
        $MSG = sprintf(_("代码编号不能为0"), $CODE_NO);
        Message(_("错误"), $MSG);
        Button_Back();
        exit;		
	}	
	if($CODE_NAME == '0'){
        $MSG = sprintf(_("代码名称不能为0!"), $CODE_NAME);
        Message(_("错误"), $MSG);
        Button_Back();
        exit;		
	}	

    $query = "SELECT * from SYS_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $MSG = sprintf(_("项目代码编号%s已存在！"), $CODE_NO);
        Message(_("错误"), $MSG);
        Button_Back();
        exit;
    }

    $query = "insert into SYS_CODE (CODE_NO,CODE_NAME,CODE_ORDER,PARENT_NO) values ('$CODE_NO','$CODE_NAME','$CODE_ORDER','$PARENT_NO')";
    exequery(TD::conn(), $query);
    Message("", _("增加成功！"));
    ?>
    <div align="center">
        <input type="button" class="BigButton" value="<?= _("返回代码列表") ?>" onclick="location = 'code_list.php?PARENT_NO=<?= $PARENT_NO ?>'">
    </div>
</body>
</html>
