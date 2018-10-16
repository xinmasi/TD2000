<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("从项目模板导入");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js"></script>

<body class="bodycolor">  
<?
if(!$PROJ_MODEL_NAME)
{
?>
<script Language="JavaScript">
var parent_window =window.opener;

function click_model(ID)
{
  parent_window.location="import.php?PROJ_ID=<?=$PROJ_ID?>&PROJ_MODEL_NAME="+ID;
  window.close();
}
</script>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("从项目模板导入")?></span><br>
    </td>
  </tr>
</table>
<?
$PATH = MYOA_ATTACH_PATH."proj_model/";
$I=0;
if ($handle = opendir($PATH))
{
    while (false !== ($file = readdir($handle)))
    {
        if (strtolower(substr($file,-4))==".xml")
        {
            $MODEL_ARRAY[$I++]=substr($file,0,-4);
        }
    }
    closedir($handle);
}

if(sizeof($MODEL_ARRAY)>0)
{
   sort ($MODEL_ARRAY);
   reset ($MODEL_ARRAY);
}

for($I=0;$I<sizeof($MODEL_ARRAY);$I++)
{
   if($I==0)
   {
?>
<table class="TableBlock" width="100%" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">

<?
   }
?>

<tr class="TableData">
  <td class="menulines" align="center" onclick=javascript:click_model('<?=urlencode($MODEL_ARRAY[$I])?>') style="cursor:hand">
  	<?=$MODEL_ARRAY[$I]?>
  </td>
</tr>
<?
}

if($I==0)
   Message("",_("没有定义项目模版"));
else
	 echo "</table>";

exit;
}

$PROJ_MODEL_NAME = MYOA_ATTACH_PATH."proj_model/".urldecode($PROJ_MODEL_NAME).".xml";
//--------------文件校验------------------
if(!file_exists($PROJ_MODEL_NAME))
{
	Message("",_("项目模板不存在！"),"error");
    echo '<div align="center"><input type="button" value='._("返回").' class="BigButton" onclick="location=\'index.php?PROJ_ID=$PROJ_ID\'"></div>';
}

$BASE = $TASK = $FILE = array();
$flag=$cur_name="";
$I=$J=0;
$parser=xml_parser_create();
xml_set_element_handler($parser,"startElement","endElement");
xml_set_character_data_handler($parser,"characterData");

$data=file_get_contents($PROJ_MODEL_NAME);

xml_parse($parser,$data);
xml_parser_free($parser);

/*function startElement($parser,$element_name,$attrs)
{
  global $flag,$cur_name,$I,$J;
  if($element_name=="BASEINFO" || $element_name=="TASK" || $element_name=="FILESORT")
  {
    if($flag=="TASK")
      $I++;
    elseif($flag=="FILESORT")
      $J++;
    $flag=$element_name;
    $cur_name="";
  }
  elseif($element_name!="PROJECT")
    $cur_name=$element_name;
  else
    $cur_name="";
}
function characterData($parser,$xml_data)
{
  global $BASE,$TASK,$FILE,$flag,$cur_name,$I,$J;
  $xml_data=iconv("utf-8",MYOA_CHARSET,$xml_data);
  $xml_data=addslashes($xml_data);
  if($flag=="BASEINFO" && $cur_name!="")
    $BASE[$cur_name]=$xml_data;
  elseif($flag=="TASK" && $cur_name!="")
    $TASK[$I][$cur_name]=$xml_data;
  elseif($flag=="FILESORT" && $cur_name!="")
    $FILE[$J][$cur_name]=$xml_data;
}

function endElement($parser,$element_name)
{
  static $cur_name;
  $cur_name="";
}*/

function startElement($parser,$element_name,$attrs)
{
    global $flag,$tag,$I,$J;	
    if($element_name=="BASEINFO" || $element_name=="TASK" || $element_name=="FILESORT")
    {
        if($flag=="TASK")
            $I++;
		elseif($flag=="FILESORT")
      		$J++;
        $flag=$element_name;
        $tag="";
    }
    elseif($element_name!="PROJECT")
    {
        $tag=$element_name;
    }
    else
    {
        $tag="";
    }
}
function characterData($parser,$xml_data)
{
    global $BASE,$TASK,$FILE,$flag,$tag,$I,$J;
    $xml_data=iconv("utf-8",MYOA_CHARSET,$xml_data);
    $xml_data=addslashes($xml_data);
	
    if($flag=="BASEINFO" && $tag!="")
    {
        $BASE[$tag]=$xml_data;
    }
	elseif($flag=="TASK" && $tag!="")
	{
		$TASK[$I][$tag]=$xml_data;
	}
    elseif($flag=="FILESORT" && $tag!="")
    {
        $FILE[$J][$tag]=$xml_data;
    }
}

function endElement($parser,$element_name)
{
    global $tag;
    $tag="";
}
/*
echo "<pre>";print_r($TASK)."<br>";
echo "<pre>";print_r($BASE)."<br>";
echo "<pre>";print_r($FILE)."<br>";exit;
*/
//---------------组织SQL语句-----------------
//---------------项目基本信息--------------
if($PROJ_ID)
{
   $query="update PROJ_PROJECT set ";
   foreach($BASE AS $K => $V)
   {
     $query.="$K='$V',";
   }
   $query=substr($query,0,-1);
   $query.=" where PROJ_ID='$PROJ_ID'";
   exequery(TD::conn(),$query);
}
else
{
   foreach($BASE AS $K => $V)
   {
   	 $K_STR.=$K.",";
     $V_STR.="'$V',";
   }
   $K_STR=substr($K_STR,0,-1);
   $V_STR=substr($V_STR,0,-1);
   $query="insert into PROJ_PROJECT($K_STR,PROJ_STATUS,PROJ_OWNER) VALUES ($V_STR,'0','".$_SESSION["LOGIN_USER_ID"]."')";
   exequery(TD::conn(),$query);
   $PROJ_ID=mysql_insert_id();
}

//-----------------项目任务信息-------------
//先清空所有步骤
$query="delete from PROJ_TASK where PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query); 

foreach($TASK AS $I)
{
  $K_STR="";
  $V_STR="";
  foreach($I AS $K => $V)
  {
    $K_STR.=$K.",";
    $V_STR.="'$V',";
  }
  $K_STR=substr($K_STR,0,-1);
  $V_STR=substr($V_STR,0,-1);
  $query="insert into PROJ_TASK($K_STR,PROJ_ID) VALUES ($V_STR,'$PROJ_ID')";
  exequery(TD::conn(),$query);  
}

//-----------------项目文档信息-------------
foreach($FILE AS $I)
{
  $K_STR="";
  $V_STR="";
  foreach($I AS $K => $V)
  {
    $K_STR.=$K.",";
    $V_STR.="'$V',";
  }
  $K_STR=substr($K_STR,0,-1);
  $V_STR=substr($V_STR,0,-1);
  $query="insert into PROJ_FILE_SORT($K_STR,PROJ_ID) VALUES ($V_STR,'$PROJ_ID')";
  exequery(TD::conn(),$query);  
}

Message("",_("项目导入成功!"));
echo '<div align="center"><input type="button" value='._("返回").' class="BigButton" onclick="parent.location=\'../index.php?PROJ_ID='.$PROJ_ID.'\'"></div>';
?>