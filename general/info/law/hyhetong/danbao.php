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
      <div align="center"><?=_("担保")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="danbao/danbaoht00.php"><?=_("财产抵押书")?></a></li>
        <li><a href="danbao/danbaoht01.php"><?=_("抵押协议")?></a></li>
        <li><a href="danbao/danbaoht02.php"><?=_("抵押合同（一）")?></a></li>
        <li><a href="danbao/danbaoht03.php"><?=_("抵押合同（二）")?></a></li>
        <li><a href="danbao/danbaoht04.php"><?=_("保证合同")?></a></li>
        <li><a href="danbao/danbaoht05.php"><?=_("反担保书")?></a></li>
        <li><a href="danbao/danbaoht06.php"><?=_("借款抵押合同")?></a></li>
        <li><a href="danbao/danbaoht07.php"><?=_("财产抵押合同")?></a></li>
        <li><a href="danbao/danbaoht08.php"><?=_("定期存单质押书")?></a></li>
        <li><a href="danbao/danbaoht09.php"><?=_("股权质押合同")?></a></li>
        <li><a href="danbao/danbaoht10.php"><?=_("动产质押合同")?></a></li>
        <li><a href="danbao/danbaoht11.php"><?=_("定金合同")?></a></li>
        <li><a href="danbao/danbaoht12.php"><?=_("物业股权抵押合同")?></a></li>
        <li><a href="danbao/danbaoht13.php"><?=_("中国人民建设银行抵押协议")?></a></li>
        <li><a href="danbao/danbaoht14.php"><?=_("抵押物清单")?></a></li>
        <li><a href="danbao/danbaoht15.php"><?=_("无条件的不可撤销的保证书")?></a></li>
        <li><a href="danbao/danbaoht16.php"><?=_("不可撤销担保书")?></a></li>
        <li><a href="danbao/danbaoht17.php"><?=_("信用担保书")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>