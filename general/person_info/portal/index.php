<?
include_once("inc/auth.inc.php");
include_once('inc/utility_portal.php');

//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("门户设置");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
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
        alert("<?=_("调整门户顺序时，请选择其中一项！")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("调整门户顺序时，只能选择其中一项！")?>");
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
        alert("<?=_("调整门户顺序时，请选择其中一项！")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("调整门户顺序时，只能选择其中一项！")?>");
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

<body class="bodycolor content-body" onLoad="parent.window.document.getElementById('c_main').height=document.body.scrollHeight;">
<form name="form1">
<?
$PORTAL = "";
$query = "select PORTAL from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
    $PORTAL_ID_STR = $ROW["PORTAL"];

$PORTAL_ARRAY = get_my_portals_info();
?>
<table class="table table-bordered" style="width: 800px;">
    <colgroup>
        <col width="40">
        <col width="200">
        <col width="40">
        <col width="200">
    </colgroup>
    <thead>
        <tr>
            <td class="center" colspan="4"><?=_("门户设置")?></td>
        </tr>
    </thead>
    <tbody style="text-align: center;">
        <tr>
            <td><b><?=_("排序")?></b></td>
            <td><b><?=_("登录打开的门户")?></b></td>
            <td><b><?=_("选择")?></b></td>
            <td valign="top"><b><?=_("备选门户")?></b></td>
        </tr>
        
          <tr>
    <td align="center" class="TableData" style=" vertical-align: middle; ">
      <input type="button" class="btn btn-mini" value=" <?=_("↑")?> " onClick="func_up();">
      <br><br>
      <input type="button" class="btn btn-mini" value=" <?=_("↓")?> " onClick="func_down();">
    </td>
    <td valign="top" align="center" class="TableData" style="text-align:center">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:250px;height:200px;">
<?
$PORTAL_ID_ARRAY = explode(',', $PORTAL_ID_STR);
foreach($PORTAL_ID_ARRAY as $PORTAL_ID)
{
   if(trim($PORTAL_ID) != '' && is_array($PORTAL_ARRAY[$PORTAL_ID]))
   {
?>
      <option value="<?=$PORTAL_ARRAY[$PORTAL_ID]['portal_id']?>"><?=$PORTAL_ARRAY[$PORTAL_ID]['portal_name']?></option>
<?
   }
}
?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="btn btn-mini" style="margin-left: 130px;display: block;margin-top: 10px;">
    </td>
    <td align="center" class="TableData" style=" vertical-align: middle; ">
      <input type="button" class="btn btn-mini" value=" <?=_("←")?> " onClick="func_insert();">
      <br><br>
      <input type="button" class="btn btn-mini" value=" <?=_("→")?> " onClick="func_delete();">
    </td>

    <td align="center" valign="top" class="TableData" style="text-align:center">
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:250px;height:200px;">
<?
foreach($PORTAL_ARRAY as $PORTAL)
{
   if(!find_id($PORTAL_ID_STR, $PORTAL['portal_id']))
   {
?>
      <option value="<?=$PORTAL['portal_id']?>"><?=$PORTAL['portal_name']?></option>
<?
   }
}
?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="btn btn-mini" style="margin-left: 130px;display: block;margin-top: 10px;">
    </td>
  </tr>

  <tr>
    <td align="center" valign="top" colspan="4" style="text-align:center;background-color:#fff;">
    <span><?=_("点击条目时，可以组合CTRL或SHIFT键进行多选")?></span><br>
      <input type="button" class="btn btn-primary" value="<?=_("保存设置")?>" onClick="mysubmit();">
    </td>
  </tr>
        
    </tbody>

</table>
</form>
</body>
</html>
