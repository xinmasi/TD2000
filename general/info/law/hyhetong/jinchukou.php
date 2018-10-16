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
      <div align="center"><?=_("进出口合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="300" valign="top" class="TableData"> 
      <ol>
        <li><a href="jinchukou/jinkouht01.php"><?=_("进口合同")?></a></li>
        <li><a href="jinchukou/jinkouht02.php"><?=_("国际技术转让加设备进口合同")?></a></li>
        <li><a href="jinchukou/jinkouht03.php"><?=_("中外货物买卖合同（一）")?></a></li>
        <li><a href="jinchukou/jinkouht04.php"><?=_("中外货物买卖合同（二）")?></a></li>
        <li><a href="jinchukou/jinkouht05.php"><?=_("中外货物买卖合同（三）")?></a></li>
        <li><a href="jinchukou/jinkouht06.php"><?=_("外货物买卖合同（Ｃ＆Ｆ或ＣＩＦ条款）")?></a></li>
        <li><a href="jinchukou/jinkouht07.php"><?=_("成交确认书（远洋）")?></a></li>
        <li><a href="jinchukou/jinkouht08.php"><?=_("商业合同")?></a></li>
        <li><a href="jinchukou/jinkouht09.php"><?=_("货物进口合同（一）")?></a></li>
        <li><a href="jinchukou/jinkouht10.php"><?=_("货物进口合同（二）")?></a></li>
        <li><a href="jinchukou/jinkouht11.php"><?=_("货物出口合同（一）")?></a></li>
        <li><a href="jinchukou/jinkouht12.php"><?=_("货物出口合同（二）")?></a></li>
        <li><a href="jinchukou/jinkouht13.php"><?=_("国际贸易合同（ＤＡＦ）")?></a></li>
        <li><a href="jinchukou/jinkouht14.php"><?=_("（现汇）外贸合同书（之一）")?></a></li>
        <li><a href="jinchukou/jinkouht15.php"><?=_("（外汇）外贸合同书（之二）")?></a></li>
        <li><a href="jinchukou/jinkouht16.php"><?=_("（现汇）外贸合同书（之三）")?></a></li>
        <li><a href="jinchukou/jinkouht17.php"><?=_("（易货）外贸合同书（之四）")?></a></li>
        <li><a href="jinchukou/jinkouht18.php"><?=_("（易货）外贸合同书（之五）")?></a></li>
        <li><a href="jinchukou/jinkouht19.php"><?=_("出口合同")?></a></li>
        <li><a href="jinchukou/jinkouht20.php"><?=_("国际采购招标合同")?></a></li>
        <li><a href="jinchukou/jinkouht21.php"><?=_("国际货物买卖合同")?></a></li>
        <li><a href="jinchukou/jinkouht22.php"><?=_("一般货物出口合同格式")?></a></li>
        <li><a href="jinchukou/jinkouht23.php"><?=_("补偿贸易设备进口合同")?></a></li>
        <li><a href="jinchukou/jinkouht24.php"><?=_("转让技术秘密和补偿贸易合作生产合同")?></a></li>
        <li><a href="jinchukou/jinkouht25.php"><?=_("国际货物贸易合同（格式合同）")?></a></li>
        <li><a href="jinchukou/jinkouht26.php"><?=_("民间贸易协议书")?></a></li>
        <li><a href="jinchukou/jinkouht27.php"><?=_("成套设备项目合同（一）")?></a></li>
        <li><a href="jinchukou/jinkouht28.php"><?=_("成套设备项目合同（二）")?></a></li>
        <li><a href="jinchukou/jinkouht29.php"><?=_("成交确认书（日本)")?></a></li>
        <li><a href="jinchukou/jinkouht30.php"><?=_("补偿贸易购销合同")?></a></li>
        <li><a href="jinchukou/jinkouht31.php"><?=_("中外补偿贸易类合同")?></a></li>
        <li><a href="jinchukou/jinkouht32.php"><?=_("成交确认书（港澳台）")?></a></li>
        <li><a href="jinchukou/jinkouht33.php"><?=_("中外补偿贸易合同")?></a></li>
        <li><a href="jinchukou/jinkouht34.php"><?=_("反购贸易(COUNTERTRADE)")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>