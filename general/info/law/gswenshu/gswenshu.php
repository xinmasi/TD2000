<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("公司常用文书库")?></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="gswenshu/gongsi00.php"><?=_("股份公司章程")?></a></li>
        <li><a href="gswenshu/gongsi01.php"><?=_("同意不对公司进行审计的声明")?></a></li>
        <li><a href="gswenshu/gongsi02.php"><?=_("私营有限责任公司章程")?></a></li>
        <li><a href="gswenshu/gongsi03.php"><?=_("有关公司董事会事项文书")?></a></li>
        <li><a href="gswenshu/gongsi04.php"><?=_("认股文书")?></a></li>
        <li><a href="gswenshu/gongsi05.php"><?=_("股份有限公司管理章程")?></a></li>
        <li><a href="gswenshu/gongsi06.php"><?=_("股权证")?></a></li>
        <li><a href="gswenshu/gongsi07.php"><?=_("支付股票现金收据")?></a></li>
        <li><a href="gswenshu/gongsi08.php"><?=_("企业资产出售单")?></a></li>
        <li><a href="gswenshu/gongsi09.php"><?=_("股份有限公司章程")?></a></li>
        <li><a href="gswenshu/gongsi10.php"><?=_("有限责任公司章程")?></a></li>
        <li><a href="gswenshu/gongsi11.php"><?=_("企业法人申请开业登记注册书")?></a></li>
        <li><a href="gswenshu/gongsi12.php"><?=_("设立股份有限公司的法律意见书")?></a></li>
        <li><a href="gswenshu/gongsi13.php"><?=_("企业申请营业登记书")?></a></li>
        <li><a href="gswenshu/gongsi14.php"><?=_("公司设立登记申请书")?></a></li>
        <li><a href="gswenshu/gongsi15.php"><?=_("企业申请筹建登记注册书")?></a></li>
        <li><a href="gswenshu/gongsi16.php"><?=_("企业法人申请变更登记注册书")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>