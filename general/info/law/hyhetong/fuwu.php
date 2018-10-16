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
      <div align="center"><?=_("服务行业")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="fuwu/fuwuht01.php"><?=_("居间合同")?></a></li>
        <li><a href="fuwu/fuwuht02.php"><?=_("家务服务合同 ")?></a></li>
        <li><a href="fuwu/fuwuht03.php"><?=_("广告合同书")?></a></li>
        <li><a href="fuwu/fuwuht04.php"><?=_("聘用律师合同")?></a></li>
        <li><a href="fuwu/fuwuht05.php"><?=_("咨询服务合同")?></a></li>
        <li><a href="fuwu/fuwuht06.php"><?=_("聘请法律顾问合同格式")?></a></li>
        <li><a href="fuwu/fuwuht07.php"><?=_("律师聘请合同文本格式")?></a></li>
        <li><a href="fuwu/fuwuht08.php"><?=_("广告发布业务合同（一）")?></a></li>
        <li><a href="fuwu/fuwuht09.php"><?=_("广告发布业务合同（二）")?></a></li>
        <li><a href="fuwu/fuwuht10.php"><?=_("聘用专（兼）职律师合同")?></a></li>
        <li><a href="fuwu/fuwuht11.php"><?=_("农村邮递代办合同")?></a></li>
        <li><a href="fuwu/fuwuht12.php"><?=_("成立维修服务中心协议书")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>