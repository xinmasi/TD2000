<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加自定义字段");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($CODE_TYPE=="1")
{
   $TYPENAME ="";
   $TYPEVALUE ="";
}
if($CODE_TYPE=="2")
{
   $TYPECODE ="";
}

if($TYPENAME!="")
{
   $TYPEVALUE=str_replace("，",",",$TYPEVALUE);
   if(substr($TYPEVALUE,-1)==",")
      $TYPEVALUE=substr($TYPEVALUE,0,-1);
   $TYPEVALUE_ARRAY=explode(",",$TYPEVALUE);
   $TYPENAME=str_replace("，",",",$TYPENAME);
   if(substr($TYPENAME,-1)==",")
      $TYPENAME=substr($TYPENAME,0,-1);
   $TYPENAME_ARRAY=explode(",",$TYPENAME);
   
   if(count($TYPENAME_ARRAY)!=count($TYPEVALUE_ARRAY))
   {
      Message("",_("选项名称和选项的值 项数不相等"));
      Button_Back();
      exit;
   }
   if(count($TYPEVALUE_ARRAY)!=count(array_unique($TYPEVALUE_ARRAY)))
   {
      Message("",_("选项的值 中有重复数值"));
      Button_Back();
      exit;
   }
   if(!(array_search("",$TYPEVALUE_ARRAY)===false))
   {
      Message("",_("选项的值 中不能有空值"));
      Button_Back();
      exit;
   }
}

if($ISQUERY=="on")
   $ISQUERY=1;
if($ISGROUY=="on")
   $ISGROUY=1;

//如果字段ID为空则是为插入更新数据
if($FIELDNO=="")
{
   $maxcount=0;
   $query = "SELECT FIELDNO from PROJ_FIELDSETTING where TYPE_CODE_NO='$PAR_ID'";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $FIELDNO=$ROW["FIELDNO"];
      if(intval(substr($FIELDNO,7))> $maxcount)
         $maxcount=intval(substr($FIELDNO,7));
   }
 	 $FIELDNO ="USERDEF".($maxcount+1);

 	 $query="insert into PROJ_FIELDSETTING(TYPE_CODE_NO,FIELDNO,FIELDNAME,ORDERNO,STYPE,TYPENAME,TYPEVALUE,TYPECODE,ISQUERY,ISGROUY,IS_SHOWLIST) values ('$CODE_ID','$FIELDNO','$FIELDNAME','$ORDERNO','$STYPE','$TYPENAME','$TYPEVALUE','$TYPECODE','$ISQUERY','$ISGROUY','$IS_SHOWLIST ')";
 	 
}
else
{
   $query="update PROJ_FIELDSETTING set FIELDNAME='$FIELDNAME',ORDERNO='$ORDERNO',STYPE='$STYPE',TYPENAME='$TYPENAME',TYPEVALUE='$TYPEVALUE',TYPECODE='$TYPECODE',ISQUERY='$ISQUERY',ISGROUY='$ISGROUY',IS_SHOWLIST='$IS_SHOWLIST' where TYPE_CODE_NO='$CODE_ID' and FIELDNO='$FIELDNO'";
}

exequery(TD::conn(),$query);
header("location: index.php?CODE_ID=$CODE_ID&CODE_NAME=$TT_CODE_NAME");
?>

</body>
</html>
