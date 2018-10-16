<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("办公用品编辑");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css">
<script>
var flag = 0;
function CheckForm()
{
    var temp = document.getElementsByTagName("input");
    for(var i = 0;i<temp.length;i++)
    {
        if(temp[i].type == "checkbox")
        {
            if(temp[i].checked == true)
                flag =1;
        }
    }
    if(flag == 0)
    {
        alert('<?=_("请选择物品")?>');
        return false;
    }
    document.form1.submit();
}
</script>

<body class="bodycolor">
<h3><?=_("办公用品权限批量设置")?></h3>
<div class="container-fluid ">
    <fieldset class="box" id="box">
        <?
        if($item == 'SHENPI_ID')
            echo '<label class="big3"><font size="3">'._("审批权限(用户)：").td_trim(getUserNameById($values)).'</font></label><label class="big3"><font size="3">'._("设置到下面所选物品：").'</font></label>';
        if($item == 'DENGJI_USER')
            echo '<label class="big3"><font size="3">'._("登记权限(用户)：").td_trim(getUserNameById($values)).'</font></label><label class="big3"><font size="3">'._("设置到下面所选物品：").'</font></label>';
        if($item == 'DENGJI_DEPT')
            echo '<label class="big3"><font size="3">'._("登记权限(部门)：").td_trim(getDeptNameById($values)).'</font></label><label class="big3"><font size="3">'._("设置到下面所选物品：").'</font></label>';
        ?>
        <form method="post" name="form1" action="update_more.php">
            <?
            $query = "SELECT * FROM OFFICE_DEPOSITORY where ID = '$depository_id'";
            $cursor = exequery(TD::conn(), $query);
            if($ROW = mysql_fetch_array($cursor))
            {
                if (find_id($ROW['DEPT_ID'], $_SESSION["LOGIN_DEPT_ID"]) || $ROW['DEPT_ID'] == '' || $ROW['DEPT_ID'] == 'ALL_DEPT')
                {
                    $ARR[$ROW['ID']] = array (
                        $ROW['DEPOSITORY_NAME']
                    );
                    if ($ROW['OFFICE_TYPE_ID'] != '')
                    {
                        $query_type = "select * from OFFICE_TYPE where ID in (".$ROW['OFFICE_TYPE_ID'].")";
                        $cursor_type = exequery(TD::conn(), $query_type);
                        while($ROW_TYPE = mysql_fetch_array($cursor_type))
                        {
                            $ARR[$ROW['ID']] [$ROW_TYPE['ID']] = array (
                                $ROW_TYPE['TYPE_NAME']
                            );

                            if ($_SESSION["LOGIN_USER_PRIV"] != 1)
                                $query1 = "((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT')";
                            else
                                $query1 = "1=1";

                            $query_products = "SELECT * FROM office_products WHERE OFFICE_PROTYPE = '" . $ROW_TYPE['ID'] . "' and $query1";
                            $cursor_products = exequery(TD::conn(), $query_products);
                            while($ROW_PRODUCTS = mysql_fetch_array($cursor_products))
                            {
                                $ARR[$ROW['ID']] [$ROW_TYPE['ID']] [$ROW_PRODUCTS['PRO_ID']] = $ROW_PRODUCTS['PRO_NAME'];
                            }
                        }
                    }
                }
            }
            if (is_array($ARR) && ! empty($ARR))
            {
                foreach((array) $ARR as $key1 => $value1)
                {
                    foreach((array) $value1 as $key => $value)
                    {
                        if($key == '0')
                        {
                            $COUNT2 ++;
                            $DEPOSITORY = $value;
                            echo "<label><font color='red' size='4px'>".$DEPOSITORY."</font></label>";
                            continue;
                        }
                        $CODE_NO = $key;
                        foreach($value as $key1 => $value1)
                        {
                            if($key1 == '0')
                            {
                                $CODE_NAME = $value1;
                                echo "<label><font size='4px'>" . $CODE_NAME . "</font></label>";
                                continue;
                            }
                        }
                        if ($_SESSION["LOGIN_USER_PRIV"] != 1)
                            $query1 = "(find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT'";
                        else
                            $query1 = "1=1";
                        $query2 = "SELECT * FROM office_products WHERE OFFICE_PROTYPE='$CODE_NO' and ($query1) and pro_id!='$pro_id' ORDER BY PRO_CODE";
                        $cursor1 = exequery(TD::conn(), $query2);
                        while($ROW = mysql_fetch_array($cursor1))
                        {
                            $COUNT1 ++;
                            $PRO_NAME   = $ROW ['PRO_NAME'];
                            $PRO_ID     = $ROW ['PRO_ID'];
                            $PRO_STOCK  = $ROW ['PRO_STOCK'];
                            $PRO_UNIT   = $ROW ['PRO_UNIT'];
                            $name       = "pro_id_" . $COUNT1;
                            echo "<label class='checkbox inline' >";
                            echo "<input type='checkbox' name='$name' value='$PRO_ID'>";
                            echo "<span>" . $PRO_NAME . "</font></span>";
                            echo "</label>";
                        }
                        $COUNT ++;
                    }
                }
            ?>
            <div align="center">
                <input type="hidden" name="values" value="<?=$values?>" />
                <input type="hidden" name="COUNT" value="<?=$COUNT1?>" />
                <input type="hidden" name="item" value="<?=$item?>" />
                <input class="btn" type="button" value="<?=_("提交")?>"  onclick="CheckForm()">
            </div>
        </form>
    <?
    }
    ?>
    </fieldset>
</div>
</body>
</html>