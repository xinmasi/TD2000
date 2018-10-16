<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$PARA_ARRAY=get_sys_para("DESKTOP_SELF_DEFINE");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;

if(!find_id($DESKTOP_SELF_DEFINE, "POS"))
{
   Message(_("禁止"),_("系统禁止自定义桌面位置"));
   exit;
}

$HTML_PAGE_TITLE = _("自定义桌面模块");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css">
<script>
function func_insert()
{
 for (i=0; i<document.form1.select2.options.length; i++)
 {
   if(document.form1.select2.options[i].selected)
   {
     option_text=document.form1.select2.options[i].text;
     option_value=document.form1.select2.options[i].value;
     option_style_color=document.form1.select2.options[i].style.color;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;
     my_option.style.color=option_style_color;

     pos=document.form1.select2.options.length;
     document.form1.select1.options.add(my_option,pos);
     document.form1.select2.remove(i);
     i--;
  }
 }//for
}

function func_delete()
{
 for (i=0;i<document.form1.select1.options.length;i++)
 {
   if(document.form1.select1.options[i].selected)
   {
     option_text=document.form1.select1.options[i].text;
     option_value=document.form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     if(option_text.indexOf("[<?=_("必选")?>]")>0)
        continue;//  return;
     pos=document.form1.select2.options.length;
     document.form1.select2.options.add(my_option,pos);
     document.form1.select1.remove(i);
     i--;
  }
 }//for
}

function func_select_all1()
{
 for (i=document.form1.select1.options.length-1; i>=0; i--)
   document.form1.select1.options[i].selected=true;
}

function func_select_all2()
{
 for (i=document.form1.select2.options.length-1; i>=0; i--)
   document.form1.select2.options[i].selected=true;
}

function func_up()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("调整桌面模块的顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整桌面模块的顺序时，只能选择其中一项！")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=0)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i-1);
    document.form1.select1.remove(i+1);
    document.form1.select1.options[i-1].selected=true;
  }
}

function func_down()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("调整桌面模块的顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整桌面模块的顺序时，只能选择其中一项！")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=document.form1.select1.options.length-1)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i+2);
    document.form1.select1.remove(i);
    document.form1.select1.options[i+1].selected=true;
  }
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< document.form1.select1.options.length; i++)
   {
      options_value=document.form1.select1.options[i].value;
      fld_str+=options_value+",";
    }

   location="submit.php?POS=<?=$POS?>&FLD_STR=" + fld_str;
}
</script>

<body class="bodycolor">
<form method="post" name="form1">
<?
if($POS=="MYTABLE_LEFT")
   $MODULE_POS="l";
else
   $MODULE_POS="r";

//-- 排除隐藏的模块 --
$query = "SELECT * from MYTABLE where VIEW_TYPE!='3' order by MODULE_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $MODULE_STR_ALL.=$ROW["MODULE_ID"].",";

  if($ROW["MODULE_POS"]=="l")
     $MODULE_STR_LEFT.=$ROW["MODULE_ID"].",";
  else
     $MODULE_STR_RIGHT.=$ROW["MODULE_ID"].",";
}

if($MODULE_POS=="l")
   $MODULE_STR=$MODULE_STR_LEFT;
else
   $MODULE_STR=$MODULE_STR_RIGHT;

//-- 隐藏的模块 --
$query = "SELECT * from MYTABLE where MODULE_POS='$MODULE_POS' and VIEW_TYPE='3' order by MODULE_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $MODULE_STR_HIDDEN.=$ROW["MODULE_ID"].",";

$query = "SELECT MYTABLE_LEFT,MYTABLE_RIGHT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_MODULE_STR=$ROW[$POS];
   if($ROW["MYTABLE_LEFT"]=="ALL")
      $USER_MODULE_STR_ALL=$MODULE_STR_LEFT;
   else
      $USER_MODULE_STR_ALL=$ROW["MYTABLE_LEFT"];

   if($ROW["MYTABLE_RIGHT"]=="ALL")
      $USER_MODULE_STR_ALL.=$MODULE_STR_RIGHT;
   else
      $USER_MODULE_STR_ALL.=$ROW["MYTABLE_RIGHT"];
}

//-- 某侧必选模块 --
$query = "SELECT * from MYTABLE where MODULE_POS='$MODULE_POS' and VIEW_TYPE='2' order by MODULE_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MODULE_ID=$ROW["MODULE_ID"];
   if(!find_id($USER_MODULE_STR_ALL,$MODULE_ID))
      $USER_MODULE_STR.=$MODULE_ID.",";
}

//-- 所有必选模块 --
$query = "SELECT * from MYTABLE where VIEW_TYPE='2' order by MODULE_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MODULE_ID=$ROW["MODULE_ID"];
   $MODULE_STR_MUST.=$MODULE_ID.",";
}

if(strstr($USER_MODULE_STR,"ALL"))
   $USER_MODULE_STR=$MODULE_STR;
?>
<table class="table table-bordered" width="600">
    <thead>
        <tr>
          <td class="center" colspan="4" style="text-align: center"><?=_("信息中心设置")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="">
        <td align="center"><b><?=_("排序")?></b></td>
        <td align="center"><b><?=_("显示以下桌面模块")?></b></td>
        <td align="center"><b><?=_("选择")?></b></td>
        <td align="center" valign="top"><b><?=_("备选桌面模块")?></b></td>
        </tr>
        <tr>
        <td align="center" class="TableData"  style="vertical-align:middle;">
          <input type="button" class="btn btn-mini" value=" <?=_("↑")?> " onClick="func_up();">
          <br><br>
          <input type="button" class="btn btn-mini" value=" <?=_("↓")?> " onClick="func_down();">
        </td>
        <td valign="top" align="center" class="TableData" style="text-align:center">
        <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:250px;height:280px">
        <?
            $MODULE_STR_ARRAY=explode(",",$USER_MODULE_STR);
            $ARRAY_COUNT=sizeof($MODULE_STR_ARRAY);
            if($MODULE_STR_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
            for($I=0; $I<$ARRAY_COUNT; $I++)
            {
              $query = "select * from MYTABLE where MODULE_ID=".intval($MODULE_STR_ARRAY[$I]);
              $cursor=exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
                $MODULE_ID=$ROW["MODULE_ID"];
                $MODULE_NAME=unserialize($ROW["MODULE_NAME"]);
                $MODULE_FILE=$ROW["MODULE_FILE"];
                
                if(is_array($MODULE_NAME) && $MODULE_NAME[MYOA_LANG_COOKIE] != "")
                   $MODULE_NAME = $MODULE_NAME[MYOA_LANG_COOKIE];
                else if(is_array($MODULE_NAME) && $MODULE_NAME[MYOA_DEFAULT_LANG] != "")
                   $MODULE_NAME = $MODULE_NAME[MYOA_DEFAULT_LANG];
                else
                   $MODULE_NAME = substr($MODULE_FILE,0,-4);
              }
              else
                 continue;
        
              if(find_id($MODULE_STR_HIDDEN,$MODULE_ID))
                 continue;
              if(find_id($MODULE_STR_MUST,$MODULE_ID))
                 $MODULE_FILE.=_("[必选]");
        
        ?>
           <option value="<?=$MODULE_ID?>"><?=$MODULE_NAME?></option>
        
        <?
            }
        ?>
        </select>
        <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="btn btn-mini"style="margin-left: 130px;display: block;margin-top: 10px;">
        </td>
        
        <td align="center" class="TableData" style="vertical-align:middle;">
          <input type="button" class="btn btn-mini" value=" <?=_("←")?> " onClick="func_insert();">
          <br><br>
          <input type="button" class="btn btn-mini" value=" <?=_("→")?> " onClick="func_delete();">
        </td>
        
        <td align="center" valign="top" class="TableData" style="text-align:center">
        <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:250px;height:280px">
        <?
            $MODULE_STR_ARRAY=explode(",",$MODULE_STR_ALL);
            $ARRAY_COUNT=sizeof($MODULE_STR_ARRAY);
            if($MODULE_STR_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
            for($I=0;$I<$ARRAY_COUNT;$I++)
            {
              $query = "SELECT * from MYTABLE where MODULE_ID=".intval($MODULE_STR_ARRAY[$I]);
              $cursor=exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
                $MODULE_ID=$ROW["MODULE_ID"];
                $MODULE_NAME=unserialize($ROW["MODULE_NAME"]);
                $MODULE_FILE=$ROW["MODULE_FILE"];
                
                if(is_array($MODULE_NAME) && $MODULE_NAME[MYOA_LANG_COOKIE] != "")
                   $MODULE_NAME = $MODULE_NAME[MYOA_LANG_COOKIE];
                else if(is_array($MODULE_NAME) && $MODULE_NAME[MYOA_DEFAULT_LANG] != "")
                   $MODULE_NAME = $MODULE_NAME[MYOA_DEFAULT_LANG];
                else
                   $MODULE_NAME = substr($MODULE_FILE,0,-4);
              }
        
              if(!find_id($USER_MODULE_STR_ALL,$MODULE_ID)&&!find_id($MODULE_STR_MUST,$MODULE_ID))
              {
        ?>
           <option value="<?=$MODULE_ID?>"><?=$MODULE_NAME?></option>
        <?
              }
            }
        ?>
        </select>
        <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="btn btn-mini" style="margin-left: 130px;display: block;margin-top: 10px;">
        </td>
        </tr>
        <tr class="TableData">
        <td align="center" valign="top" colspan="4" style="text-align:center">
        <span><?=_("点击条目时，可以组合CTRL或SHIFT键进行多选")?></span><br>
          <input type="button" class="btn btn-primary" value="<?=_("保存设置")?>" onClick="mysubmit();">
        </td>
        </tr>
    </tbody>
</table>
</form>
</body>
</html>
