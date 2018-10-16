<?
include_once("inc/auth.inc.php");

function insert_notify($DATA,$DATA_VALUE)
{
   $query="insert into  NOTIFY ($DATA) values ($DATA_VALUE)";
   exequery(TD::conn(), $query);
   $NOTIFY_ID=mysql_insert_id();
   if($NOTIFY_ID==""||$NOTIFY_ID=="0")
   { 
      Message(_("提示"),_("公告发布错误，请重新发布！"));
  	  exit;
   }
   return $NOTIFY_ID;
}
function update_notify($DATA_VALUE,$WHERE)
{
		$query="update  NOTIFY  set  $DATA_VALUE $WHERE ";
    exequery(TD::conn(), $query);
		
}
//清空阅读人
function delete_reader($NOTIFY_ID)
{
		$query="delete from APP_LOG where MODULE=4 and OPP_ID='$NOTIFY_ID' ";
    exequery(TD::conn(), $query);	
}




?>