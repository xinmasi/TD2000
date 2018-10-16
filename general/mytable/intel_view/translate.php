<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$q = td_trim($_GET["q"]);
if($q == "")
{
   echo _("请输入要翻译的内容");
   exit;
}

$key = parse_ini_file("./translate.ini");
$AIPkey = trim($key['AIPkey']);
$keyfrom = trim($key['keyfrom']);
if($AIPkey == "" || $keyfrom == "")
{
   echo _("在线翻译失败，请联系管理员设置翻译接口参数，详细设置方法参见[OA安装目录/webroot/general/mytable/intel_view/translate.ini]");
   exit;
}

$trans_url = "http://fanyi.youdao.com/openapi.do?keyfrom=".$keyfrom."&key=".$AIPkey."&type=data&doctype=json&version=1.1&q=".urlencode($q);
$result = @file_get_contents($trans_url);
if($result === FALSE)
{
   echo _("在线翻译失败，不能连接至：")."http://fanyi.youdao.com";
   exit;
}

$result = json_decode($result, TRUE);
if(!is_array($result) || !isset($result['errorCode']))
{
   echo _("在线翻译失败，数据格式不正确：").$result;
   exit;
}

if($result['errorCode'] == "50")
{
   echo _("在线翻译失败，请联系管理员设置翻译接口参数，详细设置方法参见[OA安装目录/webroot/general/mytable/intel_view/translate.ini]");
   exit;
}
else if($result['errorCode'] != "0")
{
   echo _("在线翻译失败，").translate_error($result['errorCode']);
   exit;
}

if(!is_array($result['translation']))
{
   echo _("在线翻译失败，数据格式不正确：");
   print_r($result);
   exit;
}

$return_text = '';
foreach ($result['translation'] as $translation)
{
   $return_text .= td_iconv($translation, "utf-8", MYOA_CHARSET)."; ";
}

$return_text = substr($return_text, 0, -2)."\n";
if(is_array($result['basic']) && is_array($result['basic']['explains']))
{
   $return_text .= "-----------------------------------\n";
   foreach ($result['basic']['explains'] as $translation)
   {
      $return_text .= td_iconv($translation, "utf-8", MYOA_CHARSET)."\n";
   }
}

echo td_trim($return_text);

function translate_error($errorCode)
{
   switch($errorCode)
   {
      case "20": return _("要翻译的文本过长");
      case "30": return _("无法进行有效的翻译");
      case "40": return _("不支持的语言类型");
      case "50": return _("无效的key");
      default  : return _("未知错误：").$errorCode;
   }
}
?>