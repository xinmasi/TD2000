<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������Ŀ����");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <?

	if($CODE_NO == 0){
        $MSG = sprintf(_("�����Ų���Ϊ0"), $CODE_NO);
        Message(_("����"), $MSG);
        Button_Back();
        exit;		
	}	
	if($CODE_NAME == '0'){
        $MSG = sprintf(_("�������Ʋ���Ϊ0!"), $CODE_NAME);
        Message(_("����"), $MSG);
        Button_Back();
        exit;		
	}	

    $query = "SELECT * from SYS_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $MSG = sprintf(_("��Ŀ������%s�Ѵ��ڣ�"), $CODE_NO);
        Message(_("����"), $MSG);
        Button_Back();
        exit;
    }

    $query = "insert into SYS_CODE (CODE_NO,CODE_NAME,CODE_ORDER,PARENT_NO) values ('$CODE_NO','$CODE_NAME','$CODE_ORDER','$PARENT_NO')";
    exequery(TD::conn(), $query);
    Message("", _("���ӳɹ���"));
    ?>
    <div align="center">
        <input type="button" class="BigButton" value="<?= _("���ش����б�") ?>" onclick="location = 'code_list.php?PARENT_NO=<?= $PARENT_NO ?>'">
    </div>
</body>
</html>
