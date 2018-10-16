<?php 
/**
*   regain.php文件
*
*   文件内容描述：
*   1、恢复项目操作逻辑
*
*   @edit_time  2013/09/20
*
*/
include_once("inc/auth.inc.php");
include_once("../../proj/proj_priv.php");
$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;

$update_proj = "UPDATE proj_project SET PROJ_STATUS = '2' where PROJ_ID='$i_proj_id'";
exequery(TD::conn(),$update_proj);

$query_task = "update PROJ_TASK set TASK_STATUS = '0' WHERE PROJ_ID='$i_proj_id' and TASK_PERCENT_COMPLETE<>100";
exequery(TD::conn(),$query_task);
header("location:proj_detail.php?VALUE=1&PROJ_ID=$i_proj_id");
?>