<?
$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;
$i_task_id = isset($_GET["TASK_ID"]) ? intval($_GET["TASK_ID"]) : 0;
if(!$i_proj_id)
{
	Message(_("����"),_("δָ����Ŀ��"));
	exit;
}
$IS_HAVE_POWER1 = $IS_HAVE_POWER2 = $IS_HAVE_POWER3 = false;

//----------Ȩ�޿���---------
$query_priv = "select PROJ_USER,PROJ_OWNER,PROJ_LEADER,PROJ_MANAGER,PROJ_VIEWER from PROJ_PROJECT WHERE PROJ_ID='$i_proj_id'";
$cursor_priv = exequery(TD::conn(),$query_priv);
if($result_priv = mysql_fetch_array($cursor_priv))
{
	$PROJ_USER = $result_priv["PROJ_USER"];  //��Ŀ��Ա
	$PROJ_OWNER = $result_priv["PROJ_OWNER"];//��Ŀ�����ߡ�������
	$PROJ_LEADER = $result_priv["PROJ_LEADER"];//��Ŀ������
	$PROJ_MANAGER = $result_priv["PROJ_MANAGER"];//��Ŀ������
	$PROJ_VIEWER = $result_priv["PROJ_VIEWER"];//��Ŀ�鿴�ˡ�������ϵ��
}

$ARR_PROJ_USER = explode("|",$PROJ_USER);
$ARR_PROJ_OWNER = explode("|",$PROJ_OWNER);
$ARR_PROJ_VIEWER = explode("|",$PROJ_VIEWER);

foreach($ARR_PROJ_USER as $k => $v){
	if(find_id($v,$_SESSION["LOGIN_USER_ID"])){
		$IS_HAVE_POWER1 = true;
	}
}

foreach($ARR_PROJ_OWNER as $k => $v){
	if(find_id($v,$_SESSION["LOGIN_USER_ID"])){
		$IS_HAVE_POWER2 = true;
	}
}

if($PROJ_LEADER == $_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER == $_SESSION["LOGIN_USER_ID"])
{
    $IS_HAVE_POWER2 = true;
}

foreach($ARR_PROJ_VIEWER as $k => $v){
	if(find_id($v,$_SESSION["LOGIN_USER_ID"])){
		$IS_HAVE_POWER3 = true;
	}
}
if($_SESSION['LOGIN_SYS_ADMIN'] == '1')
{
    $IS_HAVE_POWER1 = true;
}
if(!$IS_HAVE_POWER1 and !$IS_HAVE_POWER2 and !$IS_HAVE_POWER3){
	Message(_("����"),_("����Ȩ�鿴��"));
	exit;
}
