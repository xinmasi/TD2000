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
      <div align="center"><?=_("法律格式文书库")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="flgswenshu/gzwenshu.php"><?=_("公证文书")?></a></li>
        <li><a href="flgswenshu/gjpeichang.php"><?=_("国家赔偿类")?></a></li>
        <li><a href="flgswenshu/lvshiwenshu.php"><?=_("律师专用")?></a></li>
        <li><a href="flgswenshu/mscaijue.php"><?=_("民事裁决类")?></a></li>
        <li><a href="flgswenshu/mspanjue.php"><?=_("民事判决类")?></a></li>
        <li><a href="flgswenshu/mssusong.php"><?=_("民事诉讼文书")?></a></li>
        <li><a href="flgswenshu/xscaiding.php"><?=_("刑事裁定类")?></a></li>
        <li><a href="flgswenshu/xspanjue.php"><?=_("刑事判决类")?></a></li>
        <li><a href="flgswenshu/xssusong.php"><?=_("刑事诉讼文书")?></a></li>
        <li><a href="flgswenshu/xzsusong.php"><?=_("行政诉讼类")?></a></li>
        <li><a href="flgswenshu/zcwenshu.php"><?=_("仲裁文书")?></a></li>
      </ol>
      </td>
  </tr>
</table>
　　 
<br><center><input type="button" class="BigButton" value="<?=_("回主目录")?>" onclick="location='index.php';"></center><br>
</body>
</html>



