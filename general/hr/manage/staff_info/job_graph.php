<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("岗位结构图");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="wz_jsgraphics.js"></script>
<script type="text/javascript" src="dept_graph.js"></script>
<script type="text/javascript">
var deptNewArray = new Array();
<?
$SYS_DEPT_NEW = array();
$JOB_FIRST= array();
$JOB_SECONDARY= array();
$JOB_THIRD= array();
//获得一级信息（公司名称）
$query = "SELECT * from UNIT";
$cursor = exequery(TD::conn(),$query);
if ($ROW = mysql_fetch_array($cursor))
{
   $UNIT_NAME = $ROW["UNIT_NAME"];
}
//获得子节点信息
$SECONDARY_NUMBER="";
$THIRD_NUMBER1="";
$query = "SELECT CODE_NO from HR_CODE where PARENT_NO ='JOBS_STYLE'";
$cursor = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
   $CODE_NO = '1'.$ROW["CODE_NO"];
   $SECONDARY_NUMBER.=$CODE_NO.',';
}
$query = "SELECT JOB_CODE_ID from HR_JOB_RESPONSIBILITIES where JOB_CODE_STYLE_ID !=''";
$cursor = exequery(TD::conn(),$query);
while($ROW1 = mysql_fetch_array($cursor))
{
    $JOB_CODE_ID = '2'.$ROW1["JOB_CODE_ID"];
    $THIRD_NUMBER1.=$JOB_CODE_ID.',';
}

$JOB_FIRST = array("DEPT_NAME" => $UNIT_NAME, "DEPT_PARENT" => -1, "DEPT_NO" => "0", "DEPT_LEVEL" => 0, "DEPT_LONG_NAME" => $UNIT_NAME, "DEPT_LINE" => "", "SON_ID_STR" => $SECONDARY_NUMBER, "BROTHER_STR" => "","LEAF_FLAG" =>false);
$SYS_DEPT_NEW[0]=$JOB_FIRST;


//获得二级信息（岗位分类）
$count=$count1=0;
$query = "SELECT CODE_NAME,CODE_NO from HR_CODE where PARENT_NO ='JOBS_STYLE'";
$cursor = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
   $count++;
   $CODE_NO = '1'.$ROW["CODE_NO"];
   $CODE_NAME = $ROW["CODE_NAME"];

   $THIRD_NUMBER="";
   $query1 = "SELECT JOB_CODE_ID from HR_JOB_RESPONSIBILITIES where JOB_CODE_STYLE_ID ='".$ROW["CODE_NO"]."'";
   $cursor1 = exequery(TD::conn(),$query1);
   while($ROW1 = mysql_fetch_array($cursor1))
   {
       $JOB_CODE_ID = '2'.$ROW1["JOB_CODE_ID"];
       $THIRD_NUMBER.=$JOB_CODE_ID.',';
   }
   $SYS_DEPT_NEW[$CODE_NO]=array("DEPT_NAME" => $CODE_NAME, "DEPT_PARENT" => 0, "DEPT_NO" => $count, "DEPT_LEVEL" => 1, "DEPT_LONG_NAME" => $CODE_NAME, "DEPT_LINE" => $CODE_NAME, "SON_ID_STR" => $THIRD_NUMBER, "BROTHER_STR" => $SECONDARY_NUMBER, "LEAF_FLAG" =>false);

   $query2 = "SELECT HR_JOB_RESPONSIBILITIES.JOB_CODE_ID,HR_CODE.CODE_NAME,HR_CODE.CODE_NO from HR_JOB_RESPONSIBILITIES,HR_CODE where HR_JOB_RESPONSIBILITIES.JOB_CODE_STYLE_ID ='".$ROW["CODE_NO"]."' and HR_JOB_RESPONSIBILITIES.JOB_CODE_ID=HR_CODE.CODE_ID";
   $cursor2 = exequery(TD::conn(),$query2);
   while($ROW2 = mysql_fetch_array($cursor2))
   {
       $count1++;
       $JOB_CODE_ID = '2'.$ROW2["JOB_CODE_ID"];
       $CODE_NAME1 =$ROW2["CODE_NAME"];
       $CODE_NO_INFO=$ROW2["CODE_NO"];
       $USER_ID_INFO="";
       $query3 = "SELECT STAFF_NAME from HR_STAFF_INFO where WORK_JOB='$CODE_NO_INFO'";
       $cursor3 = exequery(TD::conn(),$query3);
       while($ROW3 = mysql_fetch_array($cursor3))
       {
           if($ROW3["STAFF_NAME"]!="")
           {
               $USER_ID_INFO.=$ROW3["STAFF_NAME"].',';
           }
       }
       $SYS_DEPT_NEW[$JOB_CODE_ID]=array("DEPT_NAME" => $CODE_NAME1, "DEPT_PARENT" => $CODE_NO, "DEPT_NO" => $count1, "DEPT_LEVEL" => 2, "DEPT_LONG_NAME" => $USER_ID_INFO, "DEPT_LINE" => $USER_ID_INFO, "SON_ID_STR" => "", "BROTHER_STR" => $THIRD_NUMBER1, "LEAF_FLAG" =>true);

   }
}


   foreach($SYS_DEPT_NEW as $DEPT_ID => $DEPT_RECORD)
   {
      if ($DEPT_RECORD["SON_ID_STR"] == "")
      {
         $LEAF_FLAG = 'true';
      }
      else
      {
         $LEAF_FLAG = 'false';
      }
//      if ($DEPT_RECORD["DEPT_PARENT"] != "-1")
//         $DEPT_RECORD["DEPT_NAME"] = strlen($DEPT_NAME) > 10 ? csubstr($DEPT_NAME,0,10)."..." : $DEPT_NAME;
   ?>
      deptNewArray[<?=$DEPT_ID?>] = ['<?=$DEPT_RECORD["DEPT_NAME"]?>',//部门名
                                     '<?=$DEPT_RECORD["DEPT_PARENT"]?>',//父节点ID
                                     '<?=$DEPT_RECORD["DEPT_NO"]?>',//同层排序号
                                     '<?=$DEPT_RECORD["DEPT_LEVEL"]?>',//层次
                                     '<?=$DEPT_RECORD["DEPT_LONG_NAME"]?>',//长部门名
                                     '<?=$DEPT_RECORD["SON_ID_STR"]?>',//儿子ID串
                                     '<?=$DEPT_RECORD["BROTHER_STR"]?>',//兄弟ID串
                                      <?=$LEAF_FLAG?>]; //叶子节点标识

   <?
   }
?>

var FIELD_DEPT_LABEL_CONST			= 0;		//部门名(必须)
var FIELD_DEPT_PARENT_CONST			= 1;		//父亲节点(没用)
var FIELD_DEPT_NO_CONST				= 2;		//序号(没用)
var FIELD_DEPT_LEVEL_CONST			= 3;		//层(没用)
var FIELD_DEPT_LONG_LABEL_CONST		= 4;		//长部门名(可以保留)
var FIELD_DEPT_CHILD_CONST			= 5;		//孩子(有用)
var FIELD_DEPT_BROTHER_CONST		= 6;		//兄弟(无用)
var FIELD_DEPT_LEAF_CONST			= 7;		//是否叶子节点(必须)

function show_organ_graph(){
  var tree = createDeptTree(deptNewArray, 0, deptNewArray[0][FIELD_DEPT_LABEL_CONST]);
  var jg = new jsGraphics("myCanvas");
  jg.setStroke(2);
  tree.drawDeptRect(jg, "#1653A3");
  tree.fillDeptRect(jg, "#1653A3");
  tree.drawDeptString(jg,"#FFFFFF", "1");
  jg.setStroke(2);
  tree.drawDeptLine(jg, "#1653A3");
  jg.paint();
}


window.onload = function()
{
   show_organ_graph();

}
</script>


<body style="background-color: #F6F7F9">
   <div id="myCanvas" style="position:relative; width: 70%; top: 60px; margin:0 auto; left: 50px;"> </div>

<div style="width:85%; padding-bottom: 20px; margin: 0 auto;">
    <button hidefocus="true" type="button" class="btn btn-info " style="float: right; margin-top: 30px;" onclick="history.back();"/><?=_("返回")?>
    </button>
</div>
</body>
</html>