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
      <div align="center"><?=_("刑事诉讼文书")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="xssusong/xinshiws00.php"><?=_("刑事自诉状")?></a></li>
        <li><a href="xssusong/xinshiws01.php"><?=_("刑事附带民事诉状")?></a></li>
        <li><a href="xssusong/xinshiws02.php"><?=_("刑事答辩状")?></a></li>
        <li><a href="xssusong/xinshiws03.php"><?=_("刑事上诉状")?></a></li>
        <li><a href="xssusong/xinshiws04.php"><?=_("刑事自诉案件反诉状")?></a></li>
        <li><a href="xssusong/xinshiws05.php"><?=_("授权委托书")?></a></li>
        <li><a href="xssusong/xinshiws06.php"><?=_("接受指定辩护函")?></a></li>
        <li><a href="xssusong/xinshiws07.php"><?=_("接受为被告人担任辩护人委托协议")?></a></li>
        <li><a href="xssusong/xinshiws08.php"><?=_("律师会见在押犯罪嫌疑人申请书")?></a></li>
        <li><a href="xssusong/xinshiws09.php"><?=_("提请收集、调取证据申请书")?></a></li>
        <li><a href="xssusong/xinshiws10.php"><?=_("通知证人出庭申请书")?></a></li>
        <li><a href="xssusong/xinshiws11.php"><?=_("重新鉴定、勘验申请书")?></a></li>
        <li><a href="xssusong/xinshiws12.php"><?=_("延期审理申请书")?></a></li>
        <li><a href="xssusong/xinshiws13.php"><?=_("解除强制措施申请书")?></a></li>
        <li><a href="xssusong/xinshiws14.php"><?=_("取保候审申请书")?></a></li>
        <li><a href="xssusong/xinshiws15.php"><?=_("刑事起诉书")?></a></li>
        <li><a href="xssusong/xinshiws16.php"><?=_("传唤通知书")?></a></li>
        <li><a href="xssusong/xinshiws17.php"><?=_("责令具结悔过通知书")?></a></li>
        <li><a href="xssusong/xinshiws18.php"><?=_("辩护律师查阅、摘抄、复制案件诉讼文书、技术性鉴定材料登记表")?></a></li>
        <li><a href="xssusong/xinshiws19.php"><?=_("不服不起诉决定申诉书")?></a></li>
        <li><a href="xssusong/xinshiws20.php"><?=_("不服罚款、拘留决定的复议申请书")?></a></li>
        <li><a href="xssusong/xinshiws21.php"><?=_("撤诉申请书")?></a></li>
        <li><a href="xssusong/xinshiws22.php"><?=_("调查取证申请书")?></a></li>
        <li><a href="xssusong/xinshiws23.php"><?=_("法庭辩护词")?></a></li>
        <li><a href="xssusong/xinshiws24.php"><?=_("回避复议申请书")?></a></li>
        <li><a href="xssusong/xinshiws25.php"><?=_("会见在押犯罪嫌疑人申请表")?></a></li>
        <li><a href="xssusong/xinshiws26.php"><?=_("减刑、假释申请书")?></a></li>
        <li><a href="xssusong/xinshiws27.php"><?=_("回避申请书")?></a></li>
        <li><a href="xssusong/xinshiws28.php"><?=_("解除强制措施申请书")?></a></li>
        <li><a href="xssusong/xinshiws29.php"><?=_("控告状")?></a></li>
        <li><a href="xssusong/xinshiws30.php"><?=_("律师会见在押犯罪嫌疑人的函")?></a></li>
        <li><a href="xssusong/xinshiws31.php"><?=_("立案监督请求书")?></a></li>
        <li><a href="xssusong/xinshiws32.php"><?=_("接受指定辩护函")?></a></li>
        <li><a href="xssusong/xinshiws33.php"><?=_("为犯罪嫌疑人提供法律帮助委托协议")?></a></li>
        <li><a href="xssusong/xinshiws34.php"><?=_("刑事案件代理委托协议")?></a></li>
        <li><a href="xssusong/xinshiws35.php"><?=_("刑事辩护委托协议")?></a></li>
        <li><a href="xssusong/xinshiws36.php"><?=_("刑事辩护授权委托书")?></a></li>
        <li><a href="xssusong/xinshiws37.php"><?=_("刑事辩护律师事务所函")?></a></li>
        <li><a href="xssusong/xinshiws38.php"><?=_("律师事务所受理刑事案件批办单")?></a></li>
        <li><a href="xssusong/xinshiws39.php"><?=_("为犯罪嫌疑人提供法律帮助律师事务所函")?></a></li>
        <li><a href="xssusong/xinshiws40.php"><?=_("通知证人出庭申请书")?></a></li>
        <li><a href="xssusong/xinshiws41.php"><?=_("提请收集、调取证据申请书")?></a></li>
        <li><a href="xssusong/xinshiws42.php"><?=_("取保候审申请书")?></a></li>
        <li><a href="xssusong/xinshiws43.php"><?=_("刑事代理授权委托书")?></a></li>
        <li><a href="xssusong/xinshiws44.php"><?=_("刑事申诉状")?></a></li>
        <li><a href="xssusong/xinshiws45.php"><?=_("刑事自诉状")?></a></li>
        <li><a href="xssusong/xinshiws46.php"><?=_("刑事附带民事诉状")?></a></li>
        <li><a href="xssusong/xinshiws47.php"><?=_("重新鉴定、勘验申请书")?></a></li>
        <li><a href="xssusong/xinshiws48.php"><?=_("刑事代理词")?></a></li>
        <li><a href="xssusong/xinshiws49.php"><?=_("律师事务所调查专用证明（刑事）")?></a></li>
        <li><a href="xssusong/xinshiws50.php"><?=_("延期审理申请书")?></a></li>
        <li><a href="xssusong/xinshiws51.php"><?=_("刑事自诉案件反诉状")?></a></li>
        <li><a href="xssusong/xinshiws52.php"><?=_("刑事上诉状")?></a></li>
        <li><a href="xssusong/xinshiws53.php"><?=_("刑事抗诉请求书")?></a></li>
        <li><a href="xssusong/xinshiws54.php"><?=_("刑事答辩状")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>