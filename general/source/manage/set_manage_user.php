<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("指定资源管理员");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">

<script>

function func_find(select_obj,option_text)
{
 pos=option_text.indexOf("] ")+1;
 option_text=option_text.substr(0,pos);

 for (j=0; j<select_obj.options.length; j++)
 {
   str=select_obj.options[j].text;
   if(str.indexOf(option_text)>=0)
      return j;
 }//for

 return j;
}

function func_color(select_obj)
{
 font_color="red";
 option_text="";
 for (j=0; j<select_obj.options.length; j++)
 {
   str=select_obj.options[j].text;
   if(str.indexOf(option_text)<0)
   {
      if(font_color=="red")
         font_color="blue";
      else
         font_color="red";
   }
   select_obj.options[j].style.color=font_color;

   pos=str.indexOf("] ")+1;
   option_text=str.substr(0,pos);
 }//for

 return j;
}

function func_insert()
{
 for (i=form1.select2.options.length-1; i>=0; i--)
 {
   if(form1.select2.options[i].selected)
   {
     option_text=form1.select2.options[i].text;
     option_value=form1.select2.options[i].value;
     option_style_color=form1.select2.options[i].style.color;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;
     my_option.style.color=option_style_color;

     pos=func_find(form1.select1,option_text);
     form1.select1.options.add(my_option,pos);
     form1.select2.remove(i);
  }
 }//for

 func_init();
}

function func_delete()
{
 for (i=form1.select1.options.length-1; i>=0; i--)
 {
   if(form1.select1.options[i].selected)
   {
     option_text=form1.select1.options[i].text;
     option_value=form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     pos=func_find(form1.select2,option_text);
     form1.select2.options.add(my_option,pos);
     form1.select1.remove(i);
  }
 }//for

 func_init();
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

function func_init()
{
  func_color(form1.select2);
  func_color(form1.select1);
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< form1.select1.options.length; i++)
   {
      options_value=form1.select1.options[i].value;
      fld_str+=options_value+",";
    }
   document.form1.FLD_STR.value=fld_str;
   document.form1.submit();
}

<?
if($VIEW_TYPE=="")
   $VIEW_TYPE=0;
?>
function set_view_type()
{
   location="set_manage_user.php?SOURCEID=<?=$SOURCEID?>&VIEW_TYPE=<?=(1-$VIEW_TYPE)?>";
}
</script>


<body class="bodycolor" onLoad="func_init();">
<form action="manage_user_submit.php" method="post" name="form1">
<?
$query = "select * from OA_SOURCE where SOURCEID='$SOURCEID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $MANAGE_USER = $ROW["MANAGE_USER"];
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("指定资源管理员")?></span>
    &nbsp;
    <select class="BigSelect" name="VIEW_TYPE" onChange="set_view_type();">
    <option value="0"><?=_("按部门选择人员")?></option>
    <option value="1" <?if($VIEW_TYPE==1)echo "selected";?>><?=_("按角色选择人员")?></option>
    </select>
    </td>
  </tr>
</table>

<br>

<table width="500" border="1" cellspacing="0" cellpadding="3" align="center"  class="big" >
  <tr class="TableHeader">
    <td align="center"><b><?=_("已选人员")?></b></td>
    <td align="center">&nbsp;</td>
    <td align="center" valign="top"><b><?=_("备选人员")?></b></td>
  </tr>
  <tr>
    <td valign="top" align="center" class="TableContent">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:200px;">
      <?
        $WHERE_STR=" where 1=1";
        if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
        {
            if(get_manage_dept_ids($_SESSION['LOGIN_UID']))
            {
                $dept_ids = get_manage_dept_ids($_SESSION['LOGIN_UID']);
            }
            else
            {
                $dept_ids = $_SESSION["LOGIN_DEPT_ID"];
            }
            $WHERE_STR.=" and DEPARTMENT.DEPT_ID in (".$dept_ids.")";
        }
        $query = "SELECT * from USER,USER_PRIV,DEPARTMENT ".$WHERE_STR." and USER.DEPT_ID=DEPARTMENT.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0')";
        if($VIEW_TYPE==0)
           $query.= " order by DEPT_NO,USER.DEPT_ID,PRIV_NO,USER_NO,USER_NAME";
        else
           $query.= " order by PRIV_NO,DEPT_NO,USER_NO,USER_NAME";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
          $USER_ID=$ROW["USER_ID"];
          $USER_NAME=$ROW["USER_NAME"];
          $DEPT_ID1=$ROW["DEPT_ID"];
          $USER_PRIV=$ROW["USER_PRIV"];

          if($VIEW_TYPE==0)
          {
            $query1 = "SELECT * from DEPARTMENT where DEPT_ID=" .$DEPT_ID1;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $GROUP_NAME=$ROW["DEPT_NAME"];
          }
          else
          {
            $query1 = "SELECT * from USER_PRIV where USER_PRIV=" .$USER_PRIV;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $GROUP_NAME=$ROW["PRIV_NAME"];
          }

          if(find_id($MANAGE_USER,$USER_ID))
          {
       ?>
       <option value="<?=$USER_ID?>">[<?=$GROUP_NAME?>] <?=$USER_NAME?></option>

       <?
          }
        }
       ?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="SmallButton">
    </td>

    <td align="center" class="TableContent">
      <input type="button" class="SmallButton" value=" <?=_("←")?> " onClick="func_insert();">
      <br><br>
      <input type="button" class="SmallButton" value=" <?=_("→")?> " onClick="func_delete();">
    </td>

    <td align="center" valign="top" class="TableContent">
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:200px;height:200px;">
      <?
        $WHERE_STR=" where 1=1";
        if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
        {
            if(get_manage_dept_ids($_SESSION['LOGIN_UID']))
            {
                $dept_ids = get_manage_dept_ids($_SESSION['LOGIN_UID']);
            }
            else
            {
                $dept_ids = $_SESSION["LOGIN_DEPT_ID"];
            }
            $WHERE_STR.=" and DEPARTMENT.DEPT_ID in (".$dept_ids.")";
        }
        $query = "SELECT * from USER,USER_PRIV,DEPARTMENT ".$WHERE_STR." and USER.DEPT_ID=DEPARTMENT.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0')";
        if($VIEW_TYPE==0)
           $query.= " order by DEPT_NO,USER.DEPT_ID,PRIV_NO,USER_NO,USER_NAME";
        else
           $query.= " order by PRIV_NO,DEPT_NO,USER_NO,USER_NAME";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
          $USER_ID=$ROW["USER_ID"];
          $USER_NAME=$ROW["USER_NAME"];
          $DEPT_ID1=$ROW["DEPT_ID"];
          $USER_PRIV=$ROW["USER_PRIV"];

          if($VIEW_TYPE==0)
          {
            $query1 = "SELECT * from DEPARTMENT where DEPT_ID=" .$DEPT_ID1;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $GROUP_NAME=$ROW["DEPT_NAME"];
          }
          else
          {
            $query1 = "SELECT * from USER_PRIV where USER_PRIV=" .$USER_PRIV;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $GROUP_NAME=$ROW["PRIV_NAME"];
          }

          if(!find_id($MANAGE_USER,$USER_ID))
          {
       ?>
       <option value="<?=$USER_ID?>">[<?=$GROUP_NAME?>] <?=$USER_NAME?></option>

       <?
          }
        }
       ?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="SmallButton">
    </td>
  </tr>

  <tr class='TableData'>
    <td align="center" valign="top" colspan="3"  class="TableContent">
    <?=_("点击条目时，可以组合CTRL或SHIFT键进行多选")?><br>
      <input type="button" class="BigButton" value="<?=_("保存")?>" onClick="mysubmit();">&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close()">
      <input type="hidden" name="SOURCEID" value="<?=$SOURCEID?>">
      <input type="hidden" name="FLD_STR" value="">

    </td>
  </tr>
</table>
</form>
</body>
</html>
