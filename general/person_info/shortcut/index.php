<?
include_once("inc/auth.inc.php");

//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("菜单快捷组");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<script>
function func_insert()
{
 for (i=0; i<form1.select2.options.length; i++)
 {
   if(form1.select2.options[i].selected && form1.select2.options[i].value!="MENU_SORT")
   {
     option_text=form1.select2.options[i].text;
     option_value=form1.select2.options[i].value;
     option_style_color=form1.select2.options[i].style.color;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;
     my_option.style.color=option_style_color;

     pos=form1.select2.options.length;
     form1.select1.options.add(my_option,pos);
     form1.select2.remove(i);
     i--;
  }
 }//for
}

function func_delete()
{
 for (i=0;i<form1.select1.options.length;i++)
 {
   if(form1.select1.options[i].selected)
   {
     option_text=form1.select1.options[i].text;
     option_value=form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     pos=form1.select2.options.length;
     form1.select2.options.add(my_option,pos);
     form1.select1.remove(i);
     i--;
  }
 }//for
}

function func_select_all1()
{
 for (i=form1.select1.options.length-1; i>=0; i--)
   form1.select1.options[i].selected=true;
}

function func_select_all2()
{
 for (i=form1.select2.options.length-1; i>=0; i--)
   form1.select2.options[i].selected=true;
}

function func_up()
{
  sel_count=0;
  for (i=form1.select1.options.length-1; i>=0; i--)
  {
    if(form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("调整菜单快捷组的项目顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整菜单快捷组的项目顺序时，只能选择其中一项！")?>");
     return;
  }

  i=form1.select1.selectedIndex;

  if(i!=0)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=form1.select1.options[i].text;
    my_option.value=form1.select1.options[i].value;

    form1.select1.options.add(my_option,i-1);
    form1.select1.remove(i+1);
    form1.select1.options[i-1].selected=true;
  }
}

function func_down()
{
  sel_count=0;
  for (i=form1.select1.options.length-1; i>=0; i--)
  {
    if(form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("调整菜单快捷组的项目顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整菜单快捷组的项目顺序时，只能选择其中一项！")?>");
     return;
  }

  i=form1.select1.selectedIndex;

  if(i!=form1.select1.options.length-1)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=form1.select1.options[i].text;
    my_option.value=form1.select1.options[i].value;

    form1.select1.options.add(my_option,i+2);
    form1.select1.remove(i);
    form1.select1.options[i+1].selected=true;
  }
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< form1.select1.options.length; i++)
   {
      options_value=form1.select1.options[i].value;
      fld_str+=options_value+",";
    }

   location="submit.php?FLD_STR=" + fld_str;
}
</script>


<body class="bodycolor">
<form name="form1">
<?
$query = "select SHORTCUT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
   $SHORTCUT=$ROW["SHORTCUT"];

$USER_FUNC_ID_STR=$_SESSION["LOGIN_FUNC_STR"];
?>
<table class="table table-bordered" width="800">
    <thead>
    <tr>
      <td class="center" colspan="4"><?=_("菜单快捷组定义")?></td>
    </tr>
    </thead>
  <tr class="">
    <td align="center"><b><?=_("排序")?></b></td>
    <td align="center"><b><?=_("菜单快捷组项目")?></b></td>
    <td align="center"><b><?=_("选择")?></b></td>
    <td align="center" valign="top"><b><?=_("备选菜单项")?></b></td>
  </tr>
  <tr>
    <td align="center" class="TableData"  style="vertical-align:middle;">
      <input type="button" class="btn btn-mini" value=" <?=_("↑")?> " onClick="func_up();">
      <br><br>
      <input type="button" class="btn btn-mini" value=" <?=_("↓")?> " onClick="func_down();">
    </td>
    <td valign="top" align="center" class="TableData" style="text-align:center;">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:250px;height:300px;">
<?
        $FUNCTION_ARRAY = TD::get_cache('SYS_FUNCTION_ALL_'.bin2hex(MYOA_LANG_COOKIE));
        $SHORTCUT_ARRAY=explode(",",$SHORTCUT);
        $ARRAY_COUNT=sizeof($SHORTCUT_ARRAY);
        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
          if($SHORTCUT_ARRAY[$I]=="")
             break;
          $FUNC_ID=$SHORTCUT_ARRAY[$I];
          $FUNC_NAME=$FUNCTION_ARRAY[$FUNC_ID]["FUNC_NAME"];

          if(find_id($USER_FUNC_ID_STR,$FUNC_ID))
          {
?>
       <option value="<?=$FUNC_ID?>"><?=$FUNC_NAME?></option>

<?
          }
        }
?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="btn btn-mini" style="margin-left: 130px;display: block;margin-top: 10px;">
    </td>
    <td align="center" class="TableData"  style="vertical-align:middle;">
      <input type="button" class="btn btn-mini" value=" <?=_("←")?> " onClick="func_insert();">
      <br><br>
      <input type="button" class="btn btn-mini" value=" <?=_("→")?> " onClick="func_delete();">
    </td>

    <td align="center" valign="top" class="TableData" style="text-align:center;">
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:250px;height:300px;">
<?
$FUNCTION_ARRAY=array_values($FUNCTION_ARRAY);//print_r($FUNCTION_ARRAY);
$FUNC_COUNT=count($FUNCTION_ARRAY);
for($I=0; $I< $FUNC_COUNT; $I++)
{
   $FUNC_ID=$FUNCTION_ARRAY[$I]["FUNC_ID"];
   $MENU_ID=$FUNCTION_ARRAY[$I]["MENU_ID"];
   $FUNC_NAME=$FUNCTION_ARRAY[$I]["FUNC_NAME"];
   $FUNC_CODE=$FUNCTION_ARRAY[$I]["FUNC_CODE"];

   if(strlen($MENU_ID)!=2 && (substr($FUNC_CODE,0,1)=="@" || !find_id($USER_FUNC_ID_STR, $FUNC_ID) || find_id($SHORTCUT, $FUNC_ID)))
      continue;
   if(strlen($MENU_ID)==2)
   {
?>
<option value="MENU_SORT">-----[<?=$FUNC_NAME?>]-----</option>
<?
   }
   else
   {
?>
<option value="<?=$FUNC_ID?>"><?=$FUNC_NAME?></option>
<?
   }
}//for
?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="btn btn-mini" style="margin-left: 130px;display: block;margin-top: 10px;">
    </td>
  </tr>

  <tr class='TableData'>
    <td align="center" valign="top" colspan="4" class="" style="text-align:center">
    <span><?=_("点击条目时，可以组合CTRL或SHIFT键进行多选")?></span><br>
      <input type="button" class="btn btn-primary" value="<?=_("保存设置")?>" onClick="mysubmit();">
    </td>
  </tr>
</table>
</form>
</body>
</html>
