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
      <div align="center"><?=_("律师专用")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="lvshiwenshu/lvshiwenshu01.php"><?=_("案件集体讨论笔录")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu02.php"><?=_("调查笔录")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu03.php"><?=_("调解笔录")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu04.php"><?=_("会见犯罪嫌疑人、被告人笔录")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu05.php"><?=_("律师事务所调查专用介绍信")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu06.php"><?=_("律师事务所法律顾问单位基本情况登记表")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu07.php"><?=_("律师事务所函（担任诉讼代理人用）")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu08.php"><?=_("律师事务所函（委托调查用）")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu09.php"><?=_("律师事务所解答法律咨询登记表")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu10.php"><?=_("律师事务所介绍信")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu11.php"><?=_("律师事务所收结案表")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu12.php"><?=_("阅卷笔录")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu13.php"><?=_("财产分割协议")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu14.php"><?=_("财产留置通知书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu15.php"><?=_("财产留置异议书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu16.php"><?=_("代理非诉讼事务授权委托书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu17.php"><?=_("法律意见书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu18.php"><?=_("房屋产权证申请书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu19.php"><?=_("非诉讼事务委托代理协议")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu20.php"><?=_("合同审查意见书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu21.php"><?=_("律师催告函")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu22.php"><?=_("律师见证书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu23.php"><?=_("纳税担保书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu24.php"><?=_("聘请法律顾问合同")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu25.php"><?=_("授权声明")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu26.php"><?=_("土地使用权申请书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu27.php"><?=_("委托拍卖合同")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu28.php"><?=_("遗嘱")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu29.php"><?=_("债务追偿催促书")?></a></li>
        <li><a href="lvshiwenshu/lvshiwenshu30.php"><?=_("资信调查报告书")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>