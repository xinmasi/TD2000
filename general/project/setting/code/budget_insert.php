<?php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������Ŀ����");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

    <?php
    $type_no = $_POST['type_no'];
    if (!is_numeric($type_no))
    {
        Message(_("����"), _("��ŷ�����"));
        Button_Back();
        exit;
    }
    if (strlen($type_no) != 3)
    {
        Message(_("����"), _("���Ϊ3λ����"));
        Button_Back();
        exit;
    }
    $type_name = $_POST['type_name'];
    if ($type_name == "")
    {
        Message(_("����"), _("���Ʋ���Ϊ��"));
        Button_Back();
        exit;
    }
    $parent_budget = $_POST['parent_budget'];
    if ($parent_budget != 0)
    {
        $type_no = $parent_budget . $type_no;
    }
    $sql = "select 1 from proj_budget_type where type_no = '$type_no' ";
    $cursor = exequery(TD::conn(), $sql);
    if (mysql_fetch_array($cursor))
    {
        Message(_("����"), _("����ظ�"));
        Button_Back();
        exit;
    }
    $sql = "insert into proj_budget_type(type_name,type_no) values ('$type_name','$type_no')";
    $cursor = exequery(TD::conn(), $sql);


    Message("", _("����ɹ�"));
    ?>	
    <div align="center">
        <input type="button" class="BigButton" value="<?= _("���ؿ�Ŀ�б�") ?>" onclick="location = 'budget_list.php?PARENT_NO=<?= $PARENT_NO ?>'">
    </div>

</body>
</html>
