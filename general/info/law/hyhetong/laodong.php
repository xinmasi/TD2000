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
      <div align="center"><?=_("劳动合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="laodong/laodonght00.php"><?=_("劳动合同")?></a></li>
        <li><a href="laodong/laodonght01.php"><?=_("企业雇员的劳动合同（样本）")?></a></li>
        <li><a href="laodong/laodonght02.php"><?=_("甘肃省劳动局私营企业职工劳动合同书")?></a></li>
        <li><a href="laodong/laodonght03.php"><?=_("企业雇员的劳动合同样本")?></a></li>
        <li><a href="laodong/laodonght04.php"><?=_("深圳市外商投资企业劳动合同书")?></a></li>
        <li><a href="laodong/laodonght05.php"><?=_("国营企业职工劳动合同书")?></a></li>
        <li><a href="laodong/laodonght06.php"><?=_("全员劳动合同书")?></a></li>
        <li><a href="laodong/laodonght07.php"><?=_("劳动合同变更书")?></a></li>
        <li><a href="laodong/laodonght08.php"><?=_("劳动合同续订书")?></a></li>
        <li><a href="laodong/laodonght09.php"><?=_("内蒙古自治区劳动人事厅临时工（季节工、农民轮换工）劳动合同书")?></a></li>
        <li><a href="laodong/laodonght10.php"><?=_("劳动合同书")?></a></li>
        <li><a href="laodong/laodonght11.php"><?=_("聘请外籍工作人员合同")?></a></li>
        <li><a href="laodong/laodonght12.php"><?=_("出国留学协议书")?></a></li>
        <li><a href="laodong/laodonght13.php"><?=_("合同制工人招聘合同")?></a></li>
        <li><a href="laodong/laodonght14.php"><?=_("停薪留职合同")?></a></li>
        <li><a href="laodong/laodonght15.php"><?=_("借调合同")?></a></li>
        <li><a href="laodong/laodonght16.php"><?=_("聘用退休人员合同")?></a></li>
        <li><a href="laodong/laodonght17.php"><?=_("上海某有限公司劳动合同")?></a></li>
        <li><a href="laodong/laodonght18.php"><?=_("中外合资企业劳动合同")?></a></li>
        <li><a href="laodong/laodonght19.php"><?=_("一般劳动合同")?></a></li>
        <li><a href="laodong/laodonght20.php"><?=_("劳动合同书使用说明")?></a></li>
        <li><a href="laodong/laodonght21.php"><?=_("沈阳某国有企业农民合同制职工劳动合同")?></a></li>
        <li><a href="laodong/laodonght22.php"><?=_("某国有企业临时工劳动合同")?></a></li>
        <li><a href="laodong/laodonght23.php"><?=_("外商投资企业劳动合同")?></a></li>
        <li><a href="laodong/laodonght24.php"><?=_("岗位劳动合同")?></a></li>
        <li><a href="laodong/laodonght25.php"><?=_("某乡村集体所有制企业职工劳动合同")?></a></li>
        <li><a href="laodong/laodonght26.php"><?=_("生产合同(一级)")?></a></li>
        <li><a href="laodong/laodonght27.php"><?=_("专项协议书")?></a></li>
        <li><a href="laodong/laodonght28.php"><?=_("国际劳务合同")?></a></li>
        <li><a href="laodong/laodonght29.php"><?=_("中外劳务合同")?></a></li>
        <li><a href="laodong/laodonght30.php"><?=_("劳务输出类合同")?></a></li>
        <li><a href="laodong/laodonght31.php"><?=_("中外劳动技术服务合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>