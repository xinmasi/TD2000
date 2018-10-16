<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if($add_id_str == "" || $add_id_str == "undefined")
{
    $add_id_str = "add_id_str";
    $add_name_str = "add_name_str";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
{
    $FORM_NAME="form1";
}

ob_end_clean();
$HTML_PAGE_TITLE = _("选择联系人");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/org_select.css" />

<script src="<?=MYOA_JS_SERVER?>/static/js/ispirit.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/org_select.js"></script>
<script Language="JavaScript">
var query_string = "MODULE_ID=<?=$MODULE_ID?>&PRIV_NO_FLAG=<?=$PRIV_NO_FLAG?>&MANAGE_FLAG=<?=$MANAGE_FLAG?>&USE_UID=<?=$USE_UID?>&SINGLE_SELECT=<?=$SINGLE_SELECT?>";
var parent_window = jQuery.browser.msie ? parent.dialogArguments.document : parent.opener.document;
var to_id_field = parent_window.<?=$FORM_NAME?>.<?=$add_id_str?>;
var to_name_field = parent_window.<?=$FORM_NAME?>.<?=$add_name_str?>;
var single_select = <?=$SINGLE_SELECT ? "true" : "false"?>;

jQuery.noConflict();
(function($){
   $(document).ready(function($){
      load_init();
      
      //默认加载联系人选中状态
      init_item('dept');
   });
})(jQuery);
</script>
<body>
<div class="main-block" id="block_dept" style="display:block;top:0px;">
    <div class="right single" id="dept_item">
<?
$s_output_html = "";
if($show_add_str != "")
{
    $query = "select * from address where 1=1 and find_in_set(ADD_ID,'$show_add_str') order by ADD_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $s_short_name = '';
        
        $add_id     = $row['ADD_ID'];
        $psn_name   = $row['PSN_NAME'];
        
        $s_short_name = (strlen($psn_name) > 12) ? csubstr($psn_name,0,12)."..." : $psn_name;
        
        $s_output_html .= '<div class="block-right-item" title="'.$psn_name.'" item_id="'.$add_id.'" item_name="'.$psn_name.'"><span class="name">'.$s_short_name.'</span></div>';
    }
}

if($s_output_html == "")
{
   $s_output_html = '<div class="message">'._("无符合条件的联系人！").'</div>';
}
else
{
   $s_output_html = '<div class="block-right-header">'._("选择联系人").'</div><div class="block-right-add">'._("全部添加").'</div><div class="block-right-remove">'._("全部删除").'</div>'.$s_output_html;
}

echo '<div class="block-right" id="dept_item_0">'.$s_output_html.'</div>';
?>
    </div>
</div>
<div id="south">
    <input type="button" class="BigButtonA" value="<?=_("确定")?>" onclick="close_window();">&nbsp;&nbsp;
</div>
</body>
