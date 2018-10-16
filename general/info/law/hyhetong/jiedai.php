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
      <div align="center"><?=_("借款贷款")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="jiedai/jiedaiht00.php"><?=_("专项贷款委托合同")?></a></li>
        <li><a href="jiedai/jiedaiht01.php"><?=_("城市土地开发和商品房借款合同（一）")?></a></li>
        <li><a href="jiedai/jiedaiht02.php"><?=_("城镇土地开发和商品房借款合同（二）")?></a></li>
        <li><a href="jiedai/jiedaiht03.php"><?=_("中国农业银行贷款凭证")?></a></li>
        <li><a href="jiedai/jiedaiht04.php"><?=_("基本建设储备借款合同")?></a></li>
        <li><a href="jiedai/jiedaiht05.php"><?=_("配套人民币借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht06.php"><?=_("买方信贷、政府贷款和混合借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht07.php"><?=_("出口商品生产中短期借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht08.php"><?=_("技术改造借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht09.php"><?=_("职工住房抵押贷款合同")?></a></li>
        <li><a href="jiedai/jiedaiht10.php"><?=_("单位住房借款合同")?></a></li>
        <li><a href="jiedai/jiedaiht11.php"><?=_("补偿贸易借款合同")?></a></li>
        <li><a href="jiedai/jiedaiht12.php"><?=_("流动资金借款合同")?></a></li>
        <li><a href="jiedai/jiedaiht13.php"><?=_("基本建设借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht14.php"><?=_("固定资产借贷合同")?></a></li>
        <li><a href="jiedai/jiedaiht15.php"><?=_("信托贷款财产抵押契约")?></a></li>
        <li><a href="jiedai/jiedaiht16.php"><?=_("中国农业银行抵押借款协议书")?></a></li>
        <li><a href="jiedai/jiedaiht17.php"><?=_("中国农业银行担保借款协议书")?></a></li>
        <li><a href="jiedai/jiedaiht18.php"><?=_("中国农业银行抵押借款合同")?></a></li>
        <li><a href="jiedai/jiedaiht19.php"><?=_("对外承包项目人民币借款合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>