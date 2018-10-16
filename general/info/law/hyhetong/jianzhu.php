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
      <div align="center"><?=_("建筑安装工程")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="jianzhu/jianzhu01.php"><?=_("建设工程设计合同")?></a></li>
        <li><a href="jianzhu/jianzhu02.php"><?=_("建筑安装工程承包合同（一）")?></a></li>
        <li><a href="jianzhu/jianzhu05.php"><?=_("建筑安装工程承包合同（二）")?></a></li>
        <li><a href="jianzhu/jianzhu30.php"><?=_("建筑安装工程承包合同（三）")?></a></li>
        <li><a href="jianzhu/jianzhu46.php"><?=_("建筑安装工程承包合同（四）")?></a></li>
        <li><a href="jianzhu/jianzhu03.php"><?=_("建设工程征用土地合同")?></a></li>
        <li><a href="jianzhu/jianzhu04.php"><?=_("建设工程拆迁房屋合同")?></a></li>
        <li><a href="jianzhu/jianzhu06.php"><?=_("工程建设监理合同")?></a></li>
        <li><a href="jianzhu/jianzhu07.php"><?=_("港口施工合同")?></a></li>
        <li><a href="jianzhu/jianzhu08.php"><?=_("建筑安装工程招标书")?></a></li>
        <li><a href="jianzhu/jianzhu09.php"><?=_("建筑安装工程勘察合同")?></a></li>
        <li><a href="jianzhu/jianzhu10.php"><?=_("建筑安装工程技术咨询合同")?></a></li>
        <li><a href="jianzhu/jianzhu12.php"><?=_("《建设工程施工合同条件》和《建设工程施工合同协议条款》的使用说明")?></a></li>
        <li><a href="jianzhu/jianzhu13.php"><?=_("建筑安装工程分包合同（一）")?></a></li>
        <li><a href="jianzhu/jianzhu14.php"><?=_("建筑安装工程分包合同（二 ）")?></a></li>
        <li><a href="jianzhu/jianzhu15.php"><?=_("建筑安装工程设计合同")?></a></li>
        <li><a href="jianzhu/jianzhu16.php"><?=_("国际土木建筑工程合同")?></a></li>
        <li><a href="jianzhu/jianzhu17.php"><?=_("安装工程合同")?></a></li>
        <li><a href="jianzhu/jianzhu18.php"><?=_("工程分包合同格式")?></a></li>
        <li><a href="jianzhu/jianzhu19.php"><?=_("建筑工程承包合同")?></a></li>
        <li><a href="jianzhu/jianzhu20.php"><?=_("煤矿建筑安装工程承包合同")?></a></li>
        <li><a href="jianzhu/jianzhu21.php"><?=_("建设工程施工合同条件")?></a></li>
        <li><a href="jianzhu/jianzhu22.php"><?=_("建筑装饰工程施工合同（甲种本）")?></a></li>
        <li><a href="jianzhu/jianzhu23.php"><?=_("建筑装饰工程施工合同（乙种本）")?></a></li>
        <li><a href="jianzhu/jianzhu24.php"><?=_("建设工程承包合同协议条款")?></a></li>
        <li><a href="jianzhu/jianzhu25.php"><?=_("建设工程承包合同（一）")?></a></li>
        <li><a href="jianzhu/jianzhu39.php"><?=_("建设工程承包合同（二）")?></a></li>
        <li><a href="jianzhu/jianzhu26.php"><?=_("业主／咨询工程师标准服务协议书")?></a></li>
        <li><a href="jianzhu/jianzhu27.php"><?=_("建设工程勘察合同（一）")?></a></li>
        <li><a href="jianzhu/jianzhu28.php"><?=_("建设工程勘察合同（二）")?></a></li>
        <li><a href="jianzhu/jianzhu29.php"><?=_("建设工程勘察设计合同")?></a></li>
        <li><a href="jianzhu/jianzhu31.php"><?=_("国际ＢＯＴ投资合同格式")?></a></li>
        <li><a href="jianzhu/jianzhu32.php"><?=_("测绘合同")?></a></li>
        <li><a href="jianzhu/jianzhu33.php"><?=_("对外建筑工程承包合同")?></a></li>
        <li><a href="jianzhu/jianzhu34.php"><?=_("国际承包工程合同格式")?></a></li>
        <li><a href="jianzhu/jianzhu35.php"><?=_("国际工程承包合同")?></a></li>
        <li><a href="jianzhu/jianzhu36.php"><?=_("建设工程施工合同（示范文本）")?></a></li>
        <li><a href="jianzhu/jianzhu37.php"><?=_("港口工程施工合同")?></a></li>
        <li><a href="jianzhu/jianzhu38.php"><?=_("建设工程可行性研究合同")?></a></li>
        <li><a href="jianzhu/jianzhu40.php"><?=_("钻进工程承包合同")?></a></li>
        <li><a href="jianzhu/jianzhu41.php"><?=_("建筑施工物资租赁合同")?></a></li>
        <li><a href="jianzhu/jianzhu42.php"><?=_("工程建设监理合同标准条件")?></a></li>
        <li><a href="jianzhu/jianzhu43.php"><?=_("建设工程施工合同条件")?></a></li>
        <li><a href="jianzhu/jianzhu44.php"><?=_("建设工程施工合同协议条款")?></a></li>
        <li><a href="jianzhu/jianzhu45.php"><?=_("电力建设工程施工合同")?></a></li>
        <li><a href="jianzhu/jianzhu47.php"><?=_("建设工程勘察设计合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>