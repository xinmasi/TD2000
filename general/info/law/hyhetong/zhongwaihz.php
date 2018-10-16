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
      <div align="center"><?=_("中外合资合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zhongwaihz/heziht23.php"><?=_("合资合同")?></a></li>
        <li><a href="zhongwaihz/heziht00.php"><?=_("合资经营合同")?></a></li>
        <li><a href="zhongwaihz/heziht20.php"><?=_("合作经营企业合同")?></a></li>
        <li><a href="zhongwaihz/heziht21.php"><?=_("中外合作公司章程")?></a></li>
        <li><a href="zhongwaihz/heziht01.php"><?=_("中外合资经营企业合同")?></a></li>
        <li><a href="zhongwaihz/heziht18.php"><?=_("中外合资经营企业章程")?></a></li>
        <li><a href="zhongwaihz/heziht04.php"><?=_("中外合作经营企业合同")?></a></li>
        <li><a href="zhongwaihz/heziht05.php"><?=_("工业类合同参考格式（样本一）")?></a></li>
        <li><a href="zhongwaihz/heziht30.php"><?=_("工业类合同参考格式（")?></a><a href="zhongwaihz/heziht30.php"><?=_("样本二")?></a><a href="zhongwaihz/heziht30.php">）</a></li>
        <li><a href="zhongwaihz/heziht02.php"><?=_("工业类合同参考格式（样本三）")?></a></li>
        <li><a href="zhongwaihz/heziht25.php"><?=_("工业类合同参考格式（样本四）")?></a></li>
        <li><a href="zhongwaihz/heziht31.php"><?=_("工业类合同参考格式（样本五）")?></a></li>
        <li><a href="zhongwaihz/heziht07.php"><?=_("金融类合同参考格式（样本一）")?></a></li>
        <li><a href="zhongwaihz/heziht06.php"><?=_("金融类合同参考格式（样本二）")?></a></li>
        <li><a href="zhongwaihz/heziht27.php"><?=_("技术类合同参考格式（样本一）")?></a></li>
        <li><a href="zhongwaihz/heziht19.php"><?=_("技术类合同参考格式（样本二）")?></a></li>
        <li><a href="zhongwaihz/heziht03.php"><?=_("计算机技术及服务合资经营合同")?></a></li>
        <li><a href="zhongwaihz/heziht08.php"><?=_("设立中外合资经营企业合同（汽车制造）")?></a></li>
        <li><a href="zhongwaihz/heziht09.php"><?=_("设立中外合资经营企业合同（皮革制品）")?></a></li>
        <li><a href="zhongwaihz/heziht10.php"><?=_("设立中外合资经营企业合同（技术服务）")?></a></li>
        <li><a href="zhongwaihz/heziht11.php"><?=_("设立中外合资经营企业合同（塑料制品）")?></a></li>
        <li><a href="zhongwaihz/heziht14.php"><?=_("设立中外合资经营企业合同（锅炉生产）")?></a></li>
        <li><a href="zhongwaihz/heziht29.php"><?=_("设立中外合资经营企业合同（钻头生产）")?></a></li>
        <li><a href="zhongwaihz/heziht32.php"><?=_("设立中外合资经营企业合同（医药）")?></a></li>
        <li><a href="zhongwaihz/heziht12.php"><?=_("投资设立饭店类企业合同（二）")?></a></li>
        <li><a href="zhongwaihz/heziht13.php"><?=_("设立中外计算机（硬件）产品合资经营企业合同")?></a></li>
        <li><a href="zhongwaihz/heziht28.php"><?=_("设立中外合资银行合同")?></a></li>
        <li><a href="zhongwaihz/heziht15.php"><?=_("中外合资经营企业合同范本（草案）")?></a></li>
        <li><a href="zhongwaihz/heziht16.php"><?=_("投资设立合资银行合同")?></a></li>
        <li><a href="zhongwaihz/heziht17.php"><?=_("饭店类合同参考格式（样本）")?></a></li>
        <li><a href="zhongwaihz/heziht24.php"><?=_("医药类合同参考格式（样本）")?></a></li>
        <li><a href="zhongwaihz/heziht22.php"><?=_("中外农副产品合作经营合同")?></a></li>
        <li><a href="zhongwaihz/heziht26.php"><?=_("投资设立融资租赁公司合同")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>