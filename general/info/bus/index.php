<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����·�߲�ѯ");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function Check1()
{
   if(document.form1.START.value=="" || document.form1.END.value=="")
   {
   	 alert("<?=_("��ʼվ���յ�վ������Ϊ�գ��������벻��������")?>");
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
   	 alert("<?=_("��·��ѯ�й�����·����Ϊ�գ�")?>");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("ѡ�����")?> </span><br>
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
  &nbsp;&nbsp;<input type="button" value="<?=_("�½�����")?>" class="BigButton" title="<?=_("�½�����")?>" onclick="New1()">
  &nbsp;&nbsp;<input type="button" value="<?=_("�½���·")?>" class="BigButton" title="<?=_("�½�������·")?>" onclick="New2()">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("����վ��ѯ")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
  <?=_("��ʼվ��")?><input type="text" name="START" size="10" maxlength="50" class="BigInput" value="<?=$START_NAME?>" title="<?=_("��ʼվ���յ�վ�������벻��������")?>">&nbsp;&nbsp;
    <?=_("�յ�վ��")?><input type="text" name="END" size="10" maxlength="50" class="BigInput" value="<?=$END_NAME?>" title="<?=_("��ʼվ���յ�վ�������벻��������")?>">
  	<input type="button" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("��ʼ��ѯ")?>" onclick="Check1()">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("����·��ѯ")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
  <?=_("������·��")?><input type="text" name="LINEID" size="10" maxlength="50" class="Biginput" value="<?=$LINEID?>" title="<?=_("���빫����·�����������֣�")?>">
    <input type="button" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("��ʼ��ѯ")?>" onclick="Check2()">
</span>
</div>
</form>

<br>
<?
Message("",_("���ݽ����ο�"));
?>
</body>
</html>
