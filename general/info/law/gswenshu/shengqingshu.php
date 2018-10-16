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
      <div align="center"><?=_("各类申请书")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="shengqingshu/shengqing00.php"><?=_("个人合伙申请开业登记表")?></a></li>
        <li><a href="shengqingshu/shengqing01.php"><?=_("减收免收公证费申请书")?></a></li>
        <li><a href="shengqingshu/shengqing02.php"><?=_("公证申请表")?></a></li>
        <li><a href="shengqingshu/shengqing03.php"><?=_("实用新型专利请求书")?></a></li>
        <li><a href="shengqingshu/shengqing04.php"><?=_("外观设计专利请求书")?></a></li>
        <li><a href="shengqingshu/shengqing05.php"><?=_("发明专利请求书")?></a></li>
        <li><a href="shengqingshu/shengqing06.php"><?=_("分公司设立登记申请书")?></a></li>
        <li><a href="shengqingshu/shengqing07.php"><?=_("企业名称预先核准申请书")?></a></li>
        <li><a href="shengqingshu/shengqing08.php"><?=_("公司（分公司）变更登记申请书")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>