<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("外汇牌价");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_BODY.='<script type="text/javascript" src="'.MYOA_JS_SERVER.'/static/js/utility.js"></script>
<script>
function getNav()
{
	for(var i=0; i<=19 ; i++){
		document.write("<a href=\"javascript:getWhpj(\'"+i+"\');\">"+(i+1)+"</a>&nbsp;");
	}
}
function getWhpj(page)
{
   $("whpj").innerHTML =  \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
   _get("/inc/whpj.php", "PAGE="+page, showWhpj, true);
}
function showWhpj(req)
{
   $("whpj").innerHTML =  req.status==200 ? req.responseText : '._("连接错误：").'+req.status;
}
</script>';

$MODULE_BODY .= '<div id="whpj" style="padding-bottom:5px;"></div>';
$MODULE_BODY .= '<div style="padding-bottom:5px;"><script>getNav();getWhpj(0);</script></div>';
$MODULE_BODY .= "<div><font color='red'>"._("免责声明：此汇率表仅供参考，以各银行实际交易汇率为准。")."</font></div>";
}
?>