<?
include_once ("inc/auth.inc.php");
include_once ("inc/header.inc.php");
$query="SELECT id,depository_name FROM office_depository WHERE id='{$_GET['id']}'";
$cursor = exequery ( TD::conn (), $query );
$ROW = mysql_fetch_array ( $cursor );
$depository_name = $ROW['depository_name'];
$depository_id=$ROW['id'];

if($_GET['type']!=''){
    $query="SELECT * FROM office_type where id='{$_GET['type']}'";
    $cursor = exequery ( TD::conn (), $query );
    $ROW = mysql_fetch_array ( $cursor );
}
?>
<style type="text/css">
    .float{
        padding-left: 15px;
    }
</style>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<body>
<form id='form2' name='form2' method="post"  action='type_update.php'>
    <div class="code_fine_center" style='text-align:center;'>
        <div>
            <div>
                <span class="public_font_color float"><?=_("所属库")?><font style="color: red;">*</font></span>
                <input name="depository_name" type="text" value="<?=$depository_name ?>" disabled>
                <input name="depository_id" type="hidden" value="<?=$depository_id ?>">
            </div>
            <div>
                <span class="public_font_color float"><?=_("排序号")?>&nbsp;</span>
                <input	type="text" name="TYPE_ORDER" value="<?=$ROW['TYPE_ORDER']; ?>"	id="TYPE_ORDER" />
            </div>
            <div>
                <span class="public_font_color"><?=_("物品分类")?><font style="color: red;padding-left: 1px;">*</font></span>
                <input type="text" name="TYPE_NAME" value="<?=$ROW['TYPE_NAME']; ?>" id="TYPE_NAME">
            </div>
        </div>
    </div>
    <input type="hidden" name="ID" id="id" value="<?=$ROW['ID']?>" />
</form>
</body>
</html>