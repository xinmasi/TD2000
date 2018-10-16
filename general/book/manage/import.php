<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("图书信息导入");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

  <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("图书信息导入")?></span><br>
      </td>
    </tr>
  </table>

<?
if($FILE_NAME=="")
{
?>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.EXCEL_FILE.value=="")
   { alert("<?=_("请选择要导入的文件！")?>");
     return (false);
   }

   if (document.form1.EXCEL_FILE.value!="")
   {
     var file_temp=document.form1.EXCEL_FILE.value,file_name;
     var Pos;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form1.FILE_NAME.value=file_name;
   }

   return (true);
}
</script>

  <br>
  <br>

  <div align="center" class="Big1">
  <b><?=_("请指定用于导入的Excel文件：")?></b>
  <form name="form1" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
    <input type="submit" value="<?=_("导入")?>" class="SmallButton">
  </form>
  <br>
   <?=_("请使用图书信息模板导入数据！")?><a href="#" onClick="window.location='templet_export.php'"><?=_("图书信息导入模板下载")?></a>
  <br>
  <?
  MESSAGE(_("导入说明"),_("导入的文件请使用模板文件导入。借阅范围如果所填写的部门不是全体部门，本部门，并且在数据库中找不到此部门则均以本部门处理，即借阅范围可以是以下几种情况：1.全体部门2.本部门3.系统部,销售部,市场部 (注意逗号为英文状态下的)。"));
  echo "<input type='button' value='"._("返回")."' class='BigButton' onClick=\"location.href='index.php';\">";
   exit;
}

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("错误"),_("只能导入ExcelL文件!"));
   Button_Back();
   exit;
}

if(MYOA_IS_UN == 1)
{
   $title=array("DEPARTMENT"=>"DEPT","BOOK_NAME"=>"BOOK_NAME","AUTHOR"=>"AUTHOR","BOOK_NO"=>"BOOK_NO","BOOK_TYPE"=>"TYPE_ID","ISBN"=>"ISBN",
             "PUBLISH_HOUSE"=>"PUB_HOUSE","PUBLISH_DATE"=>"PUB_DATE","PLACE"=>"AREA","AMOUNT"=>"AMT","PRICE"=>"PRICE",
             "BRIEF"=>"BRIEF","OPEN"=>"OPEN","BORR_PERSON"=>"BORR_PERSON","MEMO"=>"MEMO",
);
	$fieldAttr = array("PUBLISH_DATE" => "date");
}
else
{
   $title=array(_("部门")=>"DEPT",_("书名")=>"BOOK_NAME",_("作者")=>"AUTHOR",_("图书编号")=>"BOOK_NO",_("图书类别")=>"TYPE_ID",_("ISBN号")=>"ISBN",
             _("出版社")=>"PUB_HOUSE",_("出版日期")=>"PUB_DATE",_("存放地点")=>"AREA",_("数量")=>"AMT",_("价格")=>"PRICE",
             _("内容简介")=>"BRIEF",_("借阅范围")=>"OPEN",_("录入人")=>"BORR_PERSON",_("备注")=>"MEMO",
);
	$fieldAttr = array("出版日期" => "date");
}
$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

$ROW_COUNT = 0;
$data = file_get_contents($EXCEL_FILE);
if(!$data)
{
   Message(_("错误"),_("打开文件错误!"));
   Button_Back();
   exit;
}
//$lines=EXCEL2Array($data, $title);//国际版需改
require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
$I = -1;
$lines = array();
while ($DATA = $objExcel->getNextRow())
{
		$lines[] = $DATA;
		$I++;
    if($DATA["BOOK_NAME"]=="")
    {
       $lines[$I]["MSG_ERROR"]=_("导入失败,书名不能为空");
       continue;
    }

    $query="select * from BOOK_INFO where BOOK_NO='".$DATA["BOOK_NO"]."'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $lines[$I]["MSG_ERROR"]=_("导入失败,图书编号已经存在");
       continue;  	
    }

    $query="select DEPT_ID from DEPARTMENT where DEPT_NAME='".$DATA["DEPT"]."'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $DATA["DEPT"]=$ROW["DEPT_ID"];
    else
       $DATA["DEPT"]=0;

    $query="select TYPE_ID from BOOK_TYPE  where TYPE_NAME='".$DATA["TYPE_ID"]."'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $DATA["TYPE_ID"]=$ROW["TYPE_ID"];
    else
       $DATA["TYPE_ID"]="";

    if($DATA["OPEN"]==_("全体部门"))
       $DATA["OPEN"]="ALL_DEPT";
    else if($DATA["OPEN"]==_("本部门"))
    	 $DATA["OPEN"]=$_SESSION["LOGIN_DEPT_ID"].",";
    else
       $DATA["OPEN"]=DeptChange($DATA["OPEN"]);       
  
    $ID_STR="";
    $VALUE_STR="";
    reset($title);
    foreach($title as $key)
    {
       if(find_id($ID_STR, $key))
          continue;
       $ID_STR.=$key.",";
       $VALUE_STR.="'".$DATA[$key]."',";
    }
    $ID_STR=trim($ID_STR,",");
    $VALUE_STR=trim($VALUE_STR,",");
    
    $query="insert into BOOK_INFO(".$ID_STR.") values (".$VALUE_STR.");";
    exequery(TD::conn(),$query);
    if(mysql_affected_rows()>0)
       $ROW_COUNT++;
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" align="center" width="100%">
  <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("书名")?></td>
      <td nowrap align="center"><?=_("作者")?></td>      
      <td nowrap align="center"><?=_("ISBN号")?></td>
      <td nowrap align="center"><?=_("出版社")?></td>
      <td nowrap align="center"><?=_("信息")?></td>
  </thead>
<?
for($I=0;$I< count($lines);$I++)
{
   if($lines[$I]["MSG_ERROR"]!="")
   {
      $TR_STYLE="color:#FF0000";
   }
   else
   {
      $TR_STYLE="";
      $lines[$I]["MSG_ERROR"]=_("成功");
   }
?>
  <tr class="TableData" align="center" style="<?=$TR_STYLE?>">
      <td><?=$lines[$I]["DEPT"]?></td>
      <td><?=$lines[$I]["BOOK_NAME"]?></td>
      <td><?=$lines[$I]["AUTHOR"]?></td>
      <td><?=$lines[$I]["ISBN"]?></td>
      <td><?=$lines[$I]["PUB_HOUSE"]?></td>
      <td align="left"><?=$lines[$I]["MSG_ERROR"]?></td>
  </tr>
<?
}
?>
</table>
<?
$MSG = sprintf(_("共%d条数据导入!"), $ROW_COUNT);
Message(_("信息"),$MSG);
?>
<br>
<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='import.php';" title="<?=_("返回")?>">
</div>

</body>
</html>
<?
function DeptChange($OPEN)
{
	$OPEN_ARRAY=explode(",",$OPEN); 
	$DEPT_STR="";
	for($I=0;$I<count($OPEN_ARRAY);$I++)
	{
		 if($OPEN_ARRAY[$I]=="")
		 	 continue;	 
		 $query="select DEPT_ID from DEPARTMENT where DEPT_NAME='".$OPEN_ARRAY[$I]."'";
    	 $cursor=exequery(TD::conn(),$query);
    	 $num=mysql_num_rows($cursor);
    	 if($num>0)
    	 {
	   	 if($ROW=mysql_fetch_array($cursor))
	      	 $DEPT_STR.=$ROW["DEPT_ID"].",";
       }
	}
	if($DEPT_STR=="")
		$DEPT_STR=$_SESSION["LOGIN_DEPT_ID"].",";
	return $DEPT_STR;
		
}

?>
