<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("分组管理");
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
<div class="PageHeader"></div>
<table class="TableTop" width="80%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("导入Excel通讯簿")?>
      </td>
      <td class="right"></td>
   </tr>
</table>

<table class="TableBlock no-top-border"  width="80%">
  <form name="form1" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("请指定用于导入的Excel文件：")?></td>
      <td class="TableData"><input type="file" name="EXCEL_FILE" class="BigInput" size="50"></td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
         <input type="hidden" name="FILE_NAME">
         <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
         <input type="submit" value="<?=_("导入")?>" class="BigButton">
         <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>
<?
   exit;
}

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("错误"),_("只能导入Excel文件!"));
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
   $title=array(_("姓名")=>"PSN_NAME",_("名")=>"FIRST_NAME",_("姓")=>"LAST_NAME",_("性别")=>"SEX",_("昵称")=>"NICK_NAME",_("电子邮件地址")=>"EMAIL",_("电子邮件")=>"EMAIL",
             _("住宅所在街道")=>"ADD_HOME",_("家庭所在街道")=>"ADD_HOME",_("家庭住址")=>"ADD_HOME",_("住宅地址 街道")=>"ADD_HOME",_("家庭电话1")=>"TEL_NO_HOME",_("住宅电话")=>"TEL_NO_HOME",_("家庭电话")=>"TEL_NO_HOME",
             _("手机")=>"MOBIL_NO",_("移动电话")=>"MOBIL_NO",_("小灵通")=>"BP_NO",_("传呼机")=>"BP_NO","QQ"=>"OICQ_NO","MSN"=>"ICQ_NO",_("生日")=>"BIRTHDAY",
             _("家庭所在地邮政编码")=>"POST_NO_HOME",_("住宅所在地的邮政编码")=>"POST_NO_HOME",_("住宅地址 邮政编码")=>"POST_NO_HOME",_("家庭邮编")=>"POST_NO_HOME",
             _("公司所在地邮政编码")=>"POST_NO_DEPT",_("公司所在地的邮政编码")=>"POST_NO_DEPT",_("商务地址 邮政编码")=>"POST_NO_DEPT",_("单位邮编")=>"POST_NO_DEPT",_("公司所在街道")=>"ADD_DEPT",_("商务地址 街道")=>"ADD_DEPT",_("单位地址")=>"ADD_DEPT",
             _("职位")=>"MINISTRATION",_("职务")=>"MINISTRATION",_("办公电话1")=>"TEL_NO_DEPT",_("业务电话")=>"TEL_NO_DEPT",_("商务电话")=>"TEL_NO_DEPT",_("工作电话")=>"TEL_NO_DEPT",
             _("公司传真")=>"FAX_NO_DEPT",_("业务传真")=>"FAX_NO_DEPT",_("工作传真")=>"FAX_NO_DEPT",_("配偶")=>"MATE",_("子女")=>"CHILD",_("公司")=>"DEPT_NAME",_("单位名称")=>"DEPT_NAME",_("附注")=>"NOTES",_("备注")=>"NOTES",
   );
   $fieldAttr = array(_("生日") => "date");
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
         if($DATA[$key]==_("女"))
            $DATA[$key]="1";
         else if($DATA[$key]==_("男"))
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
   //导入自定义字段
   if(count($EXT_DATA) > 0)
   {
      save_field_data("ADDRESS",$ADD_ID,$EXT_DATA);
   }
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);

$MSG = sprintf(_("共%d条数据导入!"), $ROW_COUNT);
Message("",$MSG);
?>
<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';" title="<?=_("返回")?>">
</div>

</body>
</html>