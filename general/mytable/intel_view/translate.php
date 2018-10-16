<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$q = td_trim($_GET["q"]);
if($q == "")
{
   echo _("������Ҫ���������");
   exit;
}

$key = parse_ini_file("./translate.ini");
$AIPkey = trim($key['AIPkey']);
$keyfrom = trim($key['keyfrom']);
if($AIPkey == "" || $keyfrom == "")
{
   echo _("���߷���ʧ�ܣ�����ϵ����Ա���÷���ӿڲ�������ϸ���÷����μ�[OA��װĿ¼/webroot/general/mytable/intel_view/translate.ini]");
   exit;
}

$trans_url = "http://fanyi.youdao.com/openapi.do?keyfrom=".$keyfrom."&key=".$AIPkey."&type=data&doctype=json&version=1.1&q=".urlencode($q);
$result = @file_get_contents($trans_url);
if($result === FALSE)
{
   echo _("���߷���ʧ�ܣ�������������")."http://fanyi.youdao.com";
   exit;
}

$result = json_decode($result, TRUE);
if(!is_array($result) || !isset($result['errorCode']))
{
   echo _("���߷���ʧ�ܣ����ݸ�ʽ����ȷ��").$result;
   exit;
}

if($result['errorCode'] == "50")
{
   echo _("���߷���ʧ�ܣ�����ϵ����Ա���÷���ӿڲ�������ϸ���÷����μ�[OA��װĿ¼/webroot/general/mytable/intel_view/translate.ini]");
   exit;
}
else if($result['errorCode'] != "0")
{
   echo _("���߷���ʧ�ܣ�").translate_error($result['errorCode']);
   exit;
}

if(!is_array($result['translation']))
{
   echo _("���߷���ʧ�ܣ����ݸ�ʽ����ȷ��");
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
      case "20": return _("Ҫ������ı�����");
      case "30": return _("�޷�������Ч�ķ���");
      case "40": return _("��֧�ֵ���������");
      case "50": return _("��Ч��key");
      default  : return _("δ֪����").$errorCode;
   }
}
?>