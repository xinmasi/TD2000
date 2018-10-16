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
        <li><a href="gswenshu/gswenshu.php"><?=_("公司常用文书")?></a></li>
        <li><a href="gswenshu/shengqingshu.php"><?=_("各类申请书")?></a></li>
        <li><a href="gswenshu/shenfenzm.php"><?=_("身份证明文件")?></a></li>
        <li><a href="gswenshu/weituoshu.php"><?=_("各类授权委托书")?></a></li>
      </ol>
    </td>
  </tr>
</table>

<br><center><input type="button" class="BigButton" value="<?=_("回主目录")?>" onclick="location='index.php';"></center><br>

</body>
</html>