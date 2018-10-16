<?
include_once("inc/auth.inc.php");
header('Content-type: application/x-javascript'); 
$where_str = "";
$MODEL_TYPE = $_GET['MODEL_TYPE'];
if($MODEL_TYPE != "")
{
    $sql = "select DEPT_ID,DEPT_ID_OTHER from user where uid='".$_SESSION["LOGIN_UID"]."'";
    $result=exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($result))
    {
        $dept_id = td_trim($row["DEPT_ID"].",".$row["DEPT_ID_OTHER"]);
        $my_dept_arr = explode(",",$dept_id);
    }
    $query = "select MODEL_ID,DEPT_ID from HTML_MODEL where MODEL_TYPE='$MODEL_TYPE' or MODEL_TYPE='ALL_MODEL'";
    $cursor=exequery(TD::conn(),$query);
    $model_id = '';
    while($ROW=mysql_fetch_array($cursor))
    {
        $html_dept_id = td_trim($ROW["DEPT_ID"]);
        $html_dept_arr = explode(",",$html_dept_id);
        $arr_count = count(array_intersect($my_dept_arr,$html_dept_arr));
        if($arr_count>0 || $html_dept_id=="ALL_DEPT")
        {
            $model_id .= $ROW["MODEL_ID"].',';
        }
    }
    $model_id_str = td_trim($model_id);
    if($model_id_str!='')
    {
        $where_str = " where MODEL_ID in($model_id_str)";
    }
    else
    {
        $where_str =" where 1=2";
    }
}

$data = array();
$query = "select * from HTML_MODEL".$where_str." order by MODEL_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $MODEL_NAME=$ROW["MODEL_NAME"];
    $CONTENT =$ROW["CONTENT"];
    $CONTENT = @gzuncompress($CONTENT);
    $CONTENT=str_replace(chr(10),"",$CONTENT);
    $CONTENT=str_replace(chr(13),"",$CONTENT);
    $CONTENT=str_replace("\\","\\\\",$CONTENT);  //zzj 2012-5-25 修改模板图片路径错误
    $data[] = array(
        'pre' => '',
        'title' => iconv('gbk', 'utf-8', $MODEL_NAME),
        'preHtml' => iconv('gbk', 'utf-8', $CONTENT),
        'html' => iconv('gbk', 'utf-8', $CONTENT)
    );
}
ob_end_clean();

echo $_GET['callback'].'('.json_encode($data).')';
?>