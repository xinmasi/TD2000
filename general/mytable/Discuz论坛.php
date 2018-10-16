<!------------- 使用说明 - 需要修改的内容 ----------------
//1、第8行的模块名称
//2、第12行的论坛地址，如果Discuz安装在OA\webroot\general\bbs2下的话则不用修改
//3、第15行的“唯一标识”字符串
---->
<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("Discuz! - 默认版块");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'bbs';

//Discuz 论坛地址，相对地址和绝对地址均可
//$DISCUZ_BASE_PATH="/general/bbs2/";
$DISCUZ_BASE_PATH="http://".$_SERVER["HTTP_HOST"]."/general/bbs2/";

//Discuz 系统管理->系统工具->Discuz! 工具->JS 调用设置->调用项目管理 中设置的某个项目的“唯一标识”，如“threads_ng5”
$DISCUZ_KEY="threads_ng5";

$MODULE_BODY.= "<ul>";

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
   $MODULE_BODY.="<script src='".$DISCUZ_BASE_PATH."/api/javascript.php?key=".$DISCUZ_KEY."'></script>";
}

$MODULE_BODY.= "<ul>";
?>