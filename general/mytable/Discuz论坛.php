<!------------- ʹ��˵�� - ��Ҫ�޸ĵ����� ----------------
//1����8�е�ģ������
//2����12�е���̳��ַ�����Discuz��װ��OA\webroot\general\bbs2�µĻ������޸�
//3����15�еġ�Ψһ��ʶ���ַ���
---->
<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("Discuz! - Ĭ�ϰ��");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'bbs';

//Discuz ��̳��ַ����Ե�ַ�;��Ե�ַ����
//$DISCUZ_BASE_PATH="/general/bbs2/";
$DISCUZ_BASE_PATH="http://".$_SERVER["HTTP_HOST"]."/general/bbs2/";

//Discuz ϵͳ����->ϵͳ����->Discuz! ����->JS ��������->������Ŀ���� �����õ�ĳ����Ŀ�ġ�Ψһ��ʶ�����硰threads_ng5��
$DISCUZ_KEY="threads_ng5";

$MODULE_BODY.= "<ul>";

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
   $MODULE_BODY.="<script src='".$DISCUZ_BASE_PATH."/api/javascript.php?key=".$DISCUZ_KEY."'></script>";
}

$MODULE_BODY.= "<ul>";
?>