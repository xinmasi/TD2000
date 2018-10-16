<?php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加项目代码");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

    <?php
    $type_no = $_POST['type_no'];
    if (!is_numeric($type_no))
    {
        Message(_("错误"), _("编号非数字"));
        Button_Back();
        exit;
    }
    if (strlen($type_no) != 3)
    {
        Message(_("错误"), _("编号为3位数字"));
        Button_Back();
        exit;
    }
    $type_name = $_POST['type_name'];
    if ($type_name == "")
    {
        Message(_("错误"), _("名称不能为空"));
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
        Message(_("错误"), _("编号重复"));
        Button_Back();
        exit;
    }
    $sql = "insert into proj_budget_type(type_name,type_no) values ('$type_name','$type_no')";
    $cursor = exequery(TD::conn(), $sql);


    Message("", _("保存成功"));
    ?>	
    <div align="center">
        <input type="button" class="BigButton" value="<?= _("返回科目列表") ?>" onclick="location = 'budget_list.php?PARENT_NO=<?= $PARENT_NO ?>'">
    </div>

</body>
</html>
