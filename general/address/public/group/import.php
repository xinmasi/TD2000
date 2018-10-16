<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
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
<div class="PageHeader"></div>
<table class="TableTop" width="80%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("����ExcelͨѶ��")?>
      </td>
      <td class="right"></td>
   </tr>
</table>

<table class="TableBlock no-top-border"  width="80%">
  <form name="form1" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("��ָ�����ڵ����Excel�ļ���")?></td>
      <td class="TableData"><input type="file" name="EXCEL_FILE" class="BigInput" size="50"></td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
         <input type="hidden" name="FILE_NAME">
         <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
         <input type="submit" value="<?=_("����")?>" class="BigButton">
         <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>
<?
   exit;
}

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
   Button_Back();
   exit;
}
if(MYOA_IS_UN == 1)
{
   $title=array("NAME"=>"PSN_NAME","FIRST_NAME"=>"FIRST_NAME","LAST_NAME"=>"LAST_NAME","SEX"=>"SEX","NICK_NAME"=>"NICK_NAME","EMAIL"=>"EMAIL",
             "HOME_ADD"=>"ADD_HOME","HOME_PHONE"=>"TEL_NO_HOME","MOBIL_NO"=>"MOBIL_NO","TEL_NO"=>"BP_NO","QQ"=>"OICQ_NO","MSN"=>"ICQ_NO","BIRTHDAY"=>"BIRTHDAY",
             "HOME_POSTCODE"=>"POST_NO_HOME","DEPT_POSTCODE"=>"POST_NO_DEPT","DEPT_ADD"=>"ADD_DEPT","MINISTRATION"=>"MINISTRATION","OFFICE_PHONE"=>"TEL_NO_DEPT",
             "COMPONY_FAX"=>"FAX_NO_DEPT","MATE"=>"MATE","CHILD"=>"CHILD","DEPT_NAME"=>"DEPT_NAME","NOTES"=>"NOTES",
   );
   $fieldAttr = array("BIRTHDAY" => "date");
}
else
{
   $title=array(_("����")=>"PSN_NAME",_("��")=>"FIRST_NAME",_("��")=>"LAST_NAME",_("�Ա�")=>"SEX",_("�ǳ�")=>"NICK_NAME",_("�����ʼ���ַ")=>"EMAIL",_("�����ʼ�")=>"EMAIL",
             _("סլ���ڽֵ�")=>"ADD_HOME",_("��ͥ���ڽֵ�")=>"ADD_HOME",_("��ͥסַ")=>"ADD_HOME",_("סլ��ַ �ֵ�")=>"ADD_HOME",_("��ͥ�绰1")=>"TEL_NO_HOME",_("סլ�绰")=>"TEL_NO_HOME",_("��ͥ�绰")=>"TEL_NO_HOME",
             _("�ֻ�")=>"MOBIL_NO",_("�ƶ��绰")=>"MOBIL_NO",_("С��ͨ")=>"BP_NO",_("������")=>"BP_NO","QQ"=>"OICQ_NO","MSN"=>"ICQ_NO",_("����")=>"BIRTHDAY",
             _("��ͥ���ڵ���������")=>"POST_NO_HOME",_("סլ���ڵص���������")=>"POST_NO_HOME",_("סլ��ַ ��������")=>"POST_NO_HOME",_("��ͥ�ʱ�")=>"POST_NO_HOME",
             _("��˾���ڵ���������")=>"POST_NO_DEPT",_("��˾���ڵص���������")=>"POST_NO_DEPT",_("�����ַ ��������")=>"POST_NO_DEPT",_("��λ�ʱ�")=>"POST_NO_DEPT",_("��˾���ڽֵ�")=>"ADD_DEPT",_("�����ַ �ֵ�")=>"ADD_DEPT",_("��λ��ַ")=>"ADD_DEPT",
             _("ְλ")=>"MINISTRATION",_("ְ��")=>"MINISTRATION",_("�칫�绰1")=>"TEL_NO_DEPT",_("ҵ��绰")=>"TEL_NO_DEPT",_("����绰")=>"TEL_NO_DEPT",_("�����绰")=>"TEL_NO_DEPT",
             _("��˾����")=>"FAX_NO_DEPT",_("ҵ����")=>"FAX_NO_DEPT",_("��������")=>"FAX_NO_DEPT",_("��ż")=>"MATE",_("��Ů")=>"CHILD",_("��˾")=>"DEPT_NAME",_("��λ����")=>"DEPT_NAME",_("��ע")=>"NOTES",_("��ע")=>"NOTES",
   );
   $fieldAttr = array(_("����") => "date");
}

$ext_title = get_field_title("ADDRESS");

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, array_merge($title, $ext_title), $fieldAttr);

$ROW_COUNT = 0;
while($DATA = $objExcel->getNextRow())
{
   $ROW_COUNT++;
   $EXT_DATA = get_field_row("ADDRESS", $DATA, $ext_title);
   
   $ID_STR="";
   $VALUE_STR="";
   $STR_UPDATE="";
   
   reset($title);
   foreach($title as $key)
   {
      if(find_id($ID_STR, $key))
         continue;

      if($key=="FIRST_NAME" || $key=="LAST_NAME" || $key=="PSN_NAME" && $DATA["PSN_NAME"]=="")
      {
         continue;
      }
      else if($key=="SEX")
      {
         if($DATA[$key]==_("Ů"))
            $DATA[$key]="1";
         else if($DATA[$key]==_("��"))
            $DATA[$key]="0";
         else
            $DATA[$key]="";
      }
      
      $ID_STR.=$key.",";
      $VALUE_STR.="'".$DATA[$key]."',";
      $STR_UPDATE.="$key='$DATA[$key]',";
      
   }
   
   if (substr($STR_UPDATE,-1)==",")
       $STR_UPDATE=substr($STR_UPDATE,0,-1);
       
   if(!find_id($ID_STR, "PSN_NAME"))
   {
      $ID_STR.="PSN_NAME,";
      $VALUE_STR.="'".$DATA["LAST_NAME"].$DATA["FIRST_NAME"]."',";
   }
    
   $ID_STR=trim($ID_STR,",");
   $VALUE_STR=trim($VALUE_STR,",");

   $query="select ADD_ID from ADDRESS where PSN_NAME='".$DATA["PSN_NAME"]."' and GROUP_ID='$GROUP_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $ADD_ID = $ROW["ADD_ID"];
      $query1="update ADDRESS SET ".$STR_UPDATE." where ADD_ID='".$ADD_ID."'";
      $cursor1=exequery(TD::conn(),$query1);
   }else{   
      $query="insert into ADDRESS (USER_ID,GROUP_ID,PSN_NO,".$ID_STR.") values ('','$GROUP_ID','$ROW_COUNT',".$VALUE_STR.");";
      exequery(TD::conn(),$query);
      $ADD_ID=mysql_insert_id();
   }
   //�����Զ����ֶ�
   if(count($EXT_DATA) > 0)
   {
      save_field_data("ADDRESS",$ADD_ID,$EXT_DATA);
   }
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);

$MSG = sprintf(_("��%d�����ݵ���!"), $ROW_COUNT);
Message("",$MSG);
?>
<div align="center">
<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php';" title="<?=_("����")?>">
</div>

</body>
</html>