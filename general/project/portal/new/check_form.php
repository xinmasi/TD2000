<?php
include_once("inc/header.inc.php");
$proj_name = $_POST["PROJ_NAME"];
$proj_name = trim($proj_name);
if(!$proj_name){
    Message("",_("����ʧ�ܣ�"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("��������")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('��&nbsp;&nbsp;��')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_type = $_POST["PROJ_TYPE"];
$proj_type = trim($proj_type);
if(!$proj_type)
{
    Message("",_("����ʧ�ܣ�"));
    echo "<center>
            <input type='button' class='BigButtonB' value='"._("��������")."' onClick=\"window.location='index.php'\" >
            <input type='button' class='BigButtonB' value='"._('��&nbsp;&nbsp;��')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_start_time = $_POST["PROJ_START_TIME"];
$proj_start_time = trim($proj_start_time);

if(!$proj_start_time)
{
    Message("",_("����ʧ�ܣ�"));
    echo "<center>
            <input type='button' class='BigButtonB' value='"._("��������")."' onClick=\"window.location='index.php'\" >
            <input type='button' class='BigButtonB' value='"._('��&nbsp;&nbsp;��')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_end_time = $_POST["PROJ_END_TIME"];
$proj_end_time = trim($proj_end_time);

if(!$proj_end_time)
{
    Message("",_("����ʧ�ܣ�"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("��������")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('��&nbsp;&nbsp;��')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}

$proj_level = $_POST["PROJ_LEVEL"];
$proj_level = trim($proj_level);

if(!$proj_level)
{
    Message("",_("����ʧ�ܣ�"));
    echo "<center>
        <input type='button' class='BigButtonB' value='"._("��������")."' onClick=\"window.location='index.php'\" >
        <input type='button' class='BigButtonB' value='"._('��&nbsp;&nbsp;��')."' onClick='parent.hide_mask();'>
          </center>";
    exit;
}
?>

