<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("公交路线查询");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function Check1()
{
   if(document.form1.START.value=="" || document.form1.END.value=="")
   {
   	 alert("<?=_("起始站与终到站均不能为空！可以输入不完整名称")?>");
     return (false);
   }
   else
     document.form1.submit();
}

function New1()
{
	 url1="new_city.php";
   location=url1;
}

function New2()
{
	 url2="new.php?CITY_ID="+document.form1.CITY.value;
   location=url2;
}

function Check2()
{
   if(document.form1.LINEID.value=="")
   {
   	 alert("<?=_("线路查询中公交线路不能为空！")?>");
     return (false);
   }
   else
     document.form1.submit();
}
</script>


<body class="bodycolor" onload="document.form1.START.focus();">

<form action="search.php"  name="form1">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("选择城市")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
  <select name="CITY" class="BigSelect">
  	<?
  	mysql_select_db("BUS", TD::conn());

   $query="SELECT * from city";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $ID=$ROW["id"];
      $CITY_ID=$ROW["city_id"];
      $CITY_NAME=$ROW["city_name"];
    ?>
   <option value="<?=$CITY_ID?>" <?if($BUS_CITY=="$CITY_ID")echo "selected";?>><?=$CITY_NAME?> </option>
   
   <?
   }
  	?>
  </select>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
  &nbsp;&nbsp;<input type="button" value="<?=_("新建城市")?>" class="BigButton" title="<?=_("新建城市")?>" onclick="New1()">
  &nbsp;&nbsp;<input type="button" value="<?=_("新建线路")?>" class="BigButton" title="<?=_("新建公交线路")?>" onclick="New2()">
<?
}
?>
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("按车站查询")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
  <?=_("起始站：")?><input type="text" name="START" size="10" maxlength="50" class="BigInput" value="<?=$START_NAME?>" title="<?=_("起始站与终到站可以输入不完整名称")?>">&nbsp;&nbsp;
    <?=_("终到站：")?><input type="text" name="END" size="10" maxlength="50" class="BigInput" value="<?=$END_NAME?>" title="<?=_("起始站与终到站可以输入不完整名称")?>">
  	<input type="button" value="<?=_("查询")?>" class="BigButton" title="<?=_("开始查询")?>" onclick="Check1()">
</span>
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("按线路查询")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
  <?=_("公交线路：")?><input type="text" name="LINEID" size="10" maxlength="50" class="Biginput" value="<?=$LINEID?>" title="<?=_("输入公交线路（阿拉伯数字）")?>">
    <input type="button" value="<?=_("查询")?>" class="BigButton" title="<?=_("开始查询")?>" onclick="Check2()">
</span>
</div>
</form>

<br>
<?
Message("",_("数据仅供参考"));
?>
</body>
</html>
