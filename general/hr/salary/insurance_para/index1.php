<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("����ϵ������");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script language="javascript"> 
function isNumberchar(num) 
 { 
    var pattern =/^100$|^100\.[0]{1,2}$|^[0-9]{1,2}\.[0-9]{1,2}$|^[0-9]{1,2}$/;
    if(!pattern.exec(num.value))
    {  
       alert("<?=_('����ϵ������Ϊ0-100��������С����֮����ౣ����λ!')?>");
      return (false);
    } 
 } 

</script>
<script Language="JavaScript">
</script>
<?
$query = "SELECT * from HR_INSURANCE_PARA";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 if($ROW=mysql_fetch_array($cursor))
 { 
    $PENSION_U_PAY=$ROW["PENSION_U_PAY"];
    $PENSION_U_PAY_ADD=$ROW["PENSION_U_PAY_ADD"];
    $PENSION_P_PAY=$ROW["PENSION_P_PAY"];
    $PENSION_P_PAY_ADD=$ROW["PENSION_P_PAY_ADD"];
    $HEALTH_P_PAY=$ROW["HEALTH_P_PAY"];
    $HEALTH_P_PAY_ADD=$ROW["HEALTH_P_PAY_ADD"];
    $HEALTH_U_PAY=$ROW["HEALTH_U_PAY"];
    $HEALTH_U_PAY_ADD=$ROW["HEALTH_U_PAY_ADD"];
    $UNEMPLOYMENT_P_PAY=$ROW["UNEMPLOYMENT_P_PAY"];
    $UNEMPLOYMENT_P_PAY_ADD=$ROW["UNEMPLOYMENT_P_PAY_ADD"];
    $UNEMPLOYMENT_U_PAY=$ROW["UNEMPLOYMENT_U_PAY"];
    $UNEMPLOYMENT_U_PAY_ADD=$ROW["UNEMPLOYMENT_U_PAY_ADD"];
    $HOUSING_P_PAY=$ROW["HOUSING_P_PAY"];
    $HOUSING_P_PAY_ADD=$ROW["HOUSING_P_PAY_ADD"];
    $HOUSING_U_PAY=$ROW["HOUSING_U_PAY"];
    $HOUSING_U_PAY_ADD=$ROW["HOUSING_U_PAY_ADD"];
    $INJURY_U_PAY=$ROW["INJURY_U_PAY"];
    $INJURY_U_PAY_ADD=$ROW["INJURY_U_PAY_ADD"];
    $MATERNITY_U_PAY=$ROW["MATERNITY_U_PAY"];
    $MATERNITY_U_PAY_ADD=$ROW["MATERNITY_U_PAY_ADD"]; 
    $YES_OTHER = $ROW["YES_OTHER"];    
 }

?>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/sys_config.gif" align="absmiddle"><span class="big3"><?=_("����ϵ������")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" >
<table class="TableBlock" width="60%" align="center">
    <tr>
    	<td class="TableContent" size="40"><?=_("��ʾ���ã�")?><input type="checkbox" name="YES_OTHER"  id="YES_OTHER" <?if($YES_OTHER==1||$YES_OTHER=="") echo "checked";?>>
    		<label for="YES_OTHER"><?=_("�Ƿ���ʾ�ڹ�����Ŀ��")?></label></td>
      </tr>
    <tr >
      <td nowrap  class="TableContent"><?=_("���ϱ���ϵ����")?></td>
    </tr>
    <tr >
      <td nowrap class="TableData" style="padding-left:10px;" ><?=_("����֧����")?>
      <INPUT type="text" name="PENSION_P_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$PENSION_P_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="PENSION_P_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$PENSION_P_PAY_ADD?>" style="text-align: right;"></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="PENSION_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$PENSION_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="PENSION_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$PENSION_U_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap  class="TableContent"><?=_("ҽ�Ʊ���ϵ����")?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("����֧����")?>
      <INPUT type="text" name="HEALTH_P_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HEALTH_P_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="HEALTH_P_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HEALTH_P_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="HEALTH_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HEALTH_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="HEALTH_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HEALTH_U_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap  class="TableContent"><?=_("ʧҵ����ϵ����")?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("����֧����")?>
      <INPUT type="text" name="UNEMPLOYMENT_P_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$UNEMPLOYMENT_P_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="UNEMPLOYMENT_P_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$UNEMPLOYMENT_P_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="UNEMPLOYMENT_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$UNEMPLOYMENT_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="UNEMPLOYMENT_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$UNEMPLOYMENT_U_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap  class="TableContent"><?=_("ס��������ϵ����")?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("����֧����")?>
      <INPUT type="text" name="HOUSING_P_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HOUSING_P_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="HOUSING_P_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HOUSING_P_PAY_ADD?>" style="text-align: right;">
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="HOUSING_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HOUSING_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="HOUSING_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$HOUSING_U_PAY_ADD?>" style="text-align: right;">
    </tr>
     <tr>
      <td nowrap  class="TableContent"><?=_("���˱���ϵ����")?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="INJURY_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$INJURY_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="INJURY_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$INJURY_U_PAY_ADD?>" style="text-align: right;">
    </tr>
     <tr>
      <td nowrap  class="TableContent"><?=_("��������ϵ����")?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" style="padding-left:10px;"><?=_("��λ֧����")?>
      <INPUT type="text" name="MATERNITY_U_PAY" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$MATERNITY_U_PAY?>" style="text-align: right;">%  +
      <INPUT type="text" name="MATERNITY_U_PAY_ADD" class=BigInput size="15" onblur="isNumberchar(this)" value="<?=$MATERNITY_U_PAY_ADD?>" style="text-align: right;">
    </tr>
    
   <tr align="center" class="TableControl">
      <td colspan=2 nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">
        <input type="reset" value="<?=_("����")?>" class="BigButton">
      </td>
   </tr>
  </table>
</form>
</body>
</html>