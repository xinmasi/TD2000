<?
include_once("inc/auth.inc.php");

function insert_notify($DATA,$DATA_VALUE)
{
   $query="insert into  NOTIFY ($DATA) values ($DATA_VALUE)";
   exequery(TD::conn(), $query);
   $NOTIFY_ID=mysql_insert_id();
   if($NOTIFY_ID==""||$NOTIFY_ID=="0")
   { 
      Message(_("��ʾ"),_("���淢�����������·�����"));
  	  exit;
   }
   return $NOTIFY_ID;
}
function update_notify($DATA_VALUE,$WHERE)
{
		$query="update  NOTIFY  set  $DATA_VALUE $WHERE ";
    exequery(TD::conn(), $query);
		
}
//����Ķ���
function delete_reader($NOTIFY_ID)
{
		$query="delete from APP_LOG where MODULE=4 and OPP_ID='$NOTIFY_ID' ";
    exequery(TD::conn(), $query);	
}




?>