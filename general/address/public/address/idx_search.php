<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("通讯簿");
include_once("inc/header.inc.php");
?>


<script>
function add_detail(ADD_ID)
{
 URL="add_detail.php?ADD_ID="+ADD_ID;
 myleft=(screen.availWidth-750)/2;
 window.open(URL,"detail","height=620,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function delete_add(ADD_ID)
{
 msg='<?=_("确认要删除该联系人吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ADD_ID=" + ADD_ID;
  window.location=URL;
 }
}

</script>

<body class="bodycolor">
<div class="PageHeader">
   <div class="title"><?=_("索引结果")?><?=_("【")?><?=$TABLE_STR?><?=_("】")?></div>
</div>
<?
$SYS_PARA_ARRAY = get_sys_para("ADDRESS_SHOW_FIELDS");
$HRMS_OPEN_FIELDS=$SYS_PARA_ARRAY["ADDRESS_SHOW_FIELDS"];
if($HRMS_OPEN_FIELDS=="")
    $HRMS_OPEN_FIELDS="PSN_NAME,SEX,DEPT_NAME,TEL_NO_DEPT,TEL_NO_HOME,MOBIL_NO,EMAIL,|"._("姓名,性别,单位名称,工作电话,家庭电话,手机,电子邮件,");
 if($HRMS_OPEN_FIELDS=="" || $HRMS_OPEN_FIELDS=="|")
   $HRMS_OPEN_FIELDS="PSN_NAME,SEX,DEPT_NAME,TEL_NO_DEPT,TEL_NO_HOME,MOBIL_NO,EMAIL,|"._("姓名,性别,单位名称,工作电话,家庭电话,手机,电子邮件,");    
 $OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
 $FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
 $NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);
 
 $ADD_COUNT=0;

 $ID_ARRAY=explode(",",$ID_STR);
 $ARRAY_COUNT=sizeof($ID_ARRAY);
 for($I=0;$I<$ARRAY_COUNT-1;$I++)
 {
    $ADD_ID=$ID_ARRAY[$I];
    $query="select * from ADDRESS where ADD_ID='$ADD_ID' order by PSN_NO";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $ADD_COUNT++;
       $GROUP_ID=$ROW["GROUP_ID"];       

       $query1 = "select * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW1=mysql_fetch_array($cursor1))
          $GROUP_NAME=$ROW1["GROUP_NAME"];
       if($GROUP_ID==0)
          $GROUP_NAME=_("默认");

       for($J=0;$J<count($FIELD_ARRAY);$J++)
       {
         if($FIELD_ARRAY[$J]=="")
            continue;
         $$FIELD_ARRAY[$J]=$ROW["$FIELD_ARRAY[$J]"];
        
         if($FIELD_ARRAY[$J]=="SEX")
         {
            if($$FIELD_ARRAY[$J]=="0")
              $$FIELD_ARRAY[$J]=_("男");
            else if($$FIELD_ARRAY[$J]=="1")
              $$FIELD_ARRAY[$J]=_("女");
            else
              $$FIELD_ARRAY[$J]="";  
         }
         
         if($FIELD_ARRAY[$J]=="MOBIL_NO")
         {
            $MOBIL_NO_STR.=$$FIELD_ARRAY[$J].",";   
         }
       }


       if($ADD_COUNT==1)
       {
?>

    <table class="TableList" width="95%">

<?
       }
?>
    <tr class="TableData">
      <?
      for($J=0;$J<count($FIELD_ARRAY);$J++)
      {
         if($FIELD_ARRAY[$J]=="")
            continue;
        
         switch($FIELD_ARRAY[$J])
         {
           case "PSN_NAME":           
               echo "<td nowrap align='center'><a href='javascript:add_detail(".$ADD_ID.");'>".$$FIELD_ARRAY[$J]."</a></td>";
               break;
           case "MOBIL_NO":
               echo "<td nowrap align='center'><A href='/general/mobile_sms/?TO_ID1=".$$FIELD_ARRAY[$J].",'>".$$FIELD_ARRAY[$J]."</A></td>";
               break;
           case "EMAIL":
               echo "<td nowrap align='center'><a href='mailto:".$$FIELD_ARRAY[$J]."'>".$$FIELD_ARRAY[$J]."</a></td>";
               break;
           default:
               echo "<td nowrap align='center'>".$$FIELD_ARRAY[$J]."</td>";
               break;
         }
      }
      ?>
      <td nowrap align="center"><?=$GROUP_NAME?></a></td>
      <td nowrap align="center" width="100">
          <a href="javascript:add_detail('<?=$ADD_ID?>');"> <?=_("详情")?></a>
      </td>
    </tr>
<?
    }
 }

 if($ADD_COUNT==0)
 {
   Message("",_("无符合条件的联系人"));
 }
 else
 {
?>
   <thead class="TableHeader">
   <?
   $col_count=0;
   for($I=0;$I<count($FIELD_ARRAY);$I++)
   {
     if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
         continue;
     $col_count++;
     if($FIELD_ARRAY[$I]=="MOBIL_NO")
        echo "<td nowrap align='center'>"._("手机")." <a href='javascript:form1.submit();'>"._("群发")."</a></td>";
     else
        echo "<td nowrap align='center'>".$NAME_ARRAY[$I]."</td>";
     
   }
   ?> 
      <td nowrap align="center"><?=_("组名")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
   </table>
<?
 }
?>
<form name="form1" method="post" action="/general/mobile_sms/new/index.php">
   <input type="hidden" name="TO_ID1" value="<?=$MOBIL_NO_STR?>">
</form>
</body>
</html>