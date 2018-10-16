<?
include_once("inc/auth.inc.php");
ob_end_clean();

$PROJ_MODEL_ARRAY = explode("|", $DELETE_STR);

foreach ($PROJ_MODEL_ARRAY as $PROJ_MODEL_NAME)
{
    if ($PROJ_MODEL_NAME != "")
    {
        $URL = MYOA_ATTACH_PATH . "proj_model/" . urldecode($PROJ_MODEL_NAME) . ".xml";
        //--------------文件校验------------------
        if (file_exists($URL))
            unlink($URL);
    }
}
header("location:index.php");
?>