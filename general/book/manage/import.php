<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("ͼ����Ϣ����");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

  <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("ͼ����Ϣ����")?></span><br>
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
   { alert("<?=_("��ѡ��Ҫ������ļ���")?>");
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
  <b><?=_("��ָ�����ڵ����Excel�ļ���")?></b>
  <form name="form1" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
    <input type="submit" value="<?=_("����")?>" class="SmallButton">
  </form>
  <br>
   <?=_("��ʹ��ͼ����Ϣģ�嵼�����ݣ�")?><a href="#" onClick="window.location='templet_export.php'"><?=_("ͼ����Ϣ����ģ������")?></a>
  <br>
  <?
  MESSAGE(_("����˵��"),_("������ļ���ʹ��ģ���ļ����롣���ķ�Χ�������д�Ĳ��Ų���ȫ�岿�ţ������ţ����������ݿ����Ҳ����˲�������Ա����Ŵ��������ķ�Χ���������¼��������1.ȫ�岿��2.������3.ϵͳ��,���۲�,�г��� (ע�ⶺ��ΪӢ��״̬�µ�)��"));
  echo "<input type='button' value='"._("����")."' class='BigButton' onClick=\"location.href='index.php';\">";
   exit;
}

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���ExcelL�ļ�!"));
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
   $title=array(_("����")=>"DEPT",_("����")=>"BOOK_NAME",_("����")=>"AUTHOR",_("ͼ����")=>"BOOK_NO",_("ͼ�����")=>"TYPE_ID",_("ISBN��")=>"ISBN",
             _("������")=>"PUB_HOUSE",_("��������")=>"PUB_DATE",_("��ŵص�")=>"AREA",_("����")=>"AMT",_("�۸�")=>"PRICE",
             _("���ݼ��")=>"BRIEF",_("���ķ�Χ")=>"OPEN",_("¼����")=>"BORR_PERSON",_("��ע")=>"MEMO",
);
	$fieldAttr = array("��������" => "date");
}
$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

$ROW_COUNT = 0;
$data = file_get_contents($EXCEL_FILE);
if(!$data)
{
   Message(_("����"),_("���ļ�����!"));
   Button_Back();
   exit;
}
//$lines=EXCEL2Array($data, $title);//���ʰ����
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
       $lines[$I]["MSG_ERROR"]=_("����ʧ��,��������Ϊ��");
       continue;
    }

    $query="select * from BOOK_INFO where BOOK_NO='".$DATA["BOOK_NO"]."'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $lines[$I]["MSG_ERROR"]=_("����ʧ��,ͼ�����Ѿ�����");
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

    if($DATA["OPEN"]==_("ȫ�岿��"))
       $DATA["OPEN"]="ALL_DEPT";
    else if($DATA["OPEN"]==_("������"))
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
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>      
      <td nowrap align="center"><?=_("ISBN��")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("��Ϣ")?></td>
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
      $lines[$I]["MSG_ERROR"]=_("�ɹ�");
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
$MSG = sprintf(_("��%d�����ݵ���!"), $ROW_COUNT);
Message(_("��Ϣ"),$MSG);
?>
<br>
<div align="center">
<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='import.php';" title="<?=_("����")?>">
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
