<?PHP
//-------------------------zfc-----------------
/*
 * 
 * HANG
 *    1:����
 *    0:�ָ�����
 * 
 * TIME : 2014-1-3
 *  */

include_once("details.inc.php");

$HANG = intval($HANG);
$PROJ_ID = intval($PROJ_ID);

if(!project_update_priv($PROJ_ID)){
    Message(_("����"),_("��Ȩ�������Ŀ!"));
    Button_Back();
    exit;    
}

$STATUS = 2;
$STR = "�ָ�";

if($HANG == 1){
    $STATUS = 4;
    $STR = "";
}

$QUERY = "UPDATE proj_project SET PROJ_STATUS = '$STATUS' WHERE PROJ_ID = '$PROJ_ID' AND (PROJ_OWNER = '". $_SESSION['LOGIN_USER_ID'] ."'|| PROJ_LEADER = '". $_SESSION['LOGIN_USER_ID'] ."' || PROJ_MANAGER = '". $_SESSION['LOGIN_USER_ID'] ."')";
exequery(TD::conn(), $QUERY);
if(mysql_affected_rows()){
    Message("",_($STR . "������Ŀ�ɹ�!"));
   
}else{
    Message("",_($STR . "������Ŀʧ��!�����Ƿ���Ȩ�޹�����Ŀ!"));
}

Button_Back();

?>