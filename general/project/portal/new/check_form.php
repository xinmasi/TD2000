<?php
include_once("inc/header.inc.php");
$proj_name = $_POST["PROJ_NAME"];
$proj_name = trim($proj_name);
if(!$proj_name){
    Message("",_("保存失败！"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("重新立项")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('关&nbsp;&nbsp;闭')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_type = $_POST["PROJ_TYPE"];
$proj_type = trim($proj_type);
if(!$proj_type)
{
    Message("",_("保存失败！"));
    echo "<center>
            <input type='button' class='BigButtonB' value='"._("重新立项")."' onClick=\"window.location='index.php'\" >
            <input type='button' class='BigButtonB' value='"._('关&nbsp;&nbsp;闭')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_start_time = $_POST["PROJ_START_TIME"];
$proj_start_time = trim($proj_start_time);

if(!$proj_start_time)
{
    Message("",_("保存失败！"));
    echo "<center>
            <input type='button' class='BigButtonB' value='"._("重新立项")."' onClick=\"window.location='index.php'\" >
            <input type='button' class='BigButtonB' value='"._('关&nbsp;&nbsp;闭')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_end_time = $_POST["PROJ_END_TIME"];
$proj_end_time = trim($proj_end_time);

if(!$proj_end_time)
{
    Message("",_("保存失败！"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("重新立项")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('关&nbsp;&nbsp;闭')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_level = $_POST["PROJ_LEVEL"];
$proj_level = trim($proj_level);

if(!$proj_level)
{
    Message("",_("保存失败！"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("重新立项")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('关&nbsp;&nbsp;闭')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}
?>

