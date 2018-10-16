var CRSAt="#0040`";var CRSDQuote="#0022`";var CRSQuote="#0027`";var CRSSep="#FE54`";var CRSPercent="#0025`";var CRSBlank="#0000`";var CRSLimiter="#_#";var CRSNewLine="#0010`";var CRSEComma="#002C`";var CRSESemiColon="#003B`";var CRSColon="#002E`";var CRSSubSign="#002D`";var CRSGreaterSign="#003E`";var CRSUpRight="#007C`";var CRSUpRight1="#XX7C`";var CRSAnd="#0026`";var CRSLess="#003C`";var CRSSep2="\u2522\u2527";var CRSSlash="#002F`";var CRSDataSetLabel="\u6570\u636e\u6e90_";var CRS_WRITE_EXEC="\u521d\u59cb\u586b\u62a5\u65f6\u81ea\u52a8\u6267\u884c";var CRS_EDIT_EXEC="\u4fee\u6539\u62a5\u8868\u65f6\u81ea\u52a8\u6267\u884c";var CRS_READ_EXEC="\u67e5\u9605\u62a5\u8868\u65f6\u81ea\u52a8\u6267\u884c";var CRS_CHANGE_EXEC="\u7b5b\u9009\u6761\u4ef6\u6539\u53d8\u540e\u81ea\u52a8\u6267\u884c";var CRS_SAVE_EXEC="\u4fdd\u5b58\u65f6\u81ea\u52a8\u6267\u884c";var CRS_SAVE_EXEC2="\u4fdd\u5b58\u65f6";var CRS_MANUL_EXEC="\u624b\u52a8\u6267\u884c";var CRS_DELETE_EXEC="\u5220\u9664\u65f6";var CRSSelect="LadbvmasSvo";var CRSInsert="NNvc3MeTEOE";var CRSUpdate="fZ7fKKYSyO.";var CRSDelete="0QizsF99v8.";var CRSDrop="BVX1unO7ESA";var CRSCreate="ST9zt7043Pk";String.prototype.replaceAll=stringReplaceAll;var arr_kid_map=[];function stringReplaceAll(f,e){if(this==undefined||this==null||this==""){return this}var d=new RegExp(f.replace(/([\(\)\[\]\{\}\^\$\+\-\*\?\.\"\'\|\/\\])/g,"\\$1"),"ig");return this.replace(d,e)}String.prototype.trim=function(){return(this.replace(/^(( )|(\u3000)|\r|\n|\t)*/g,"")).replace(/(( )|(\u3000)|\r|\n|\t)*$/g,"")};Array.prototype.indexOf=quick_search;function quick_search(d){d=","+d+",";var c=new RegExp(d,[""]);return((","+this.toString()+",").replace(c,"\u2522").replace(/[^,\u2522]/g,"")).indexOf("\u2522")}Array.prototype.length_without_null=length_without_null;function length_without_null(){var f=0;for(var d=0,e=this.length;d<e;d++){if(this[d]!=undefined&&this[d]!=null&&this[d]!=""){f++}}return f}function HandleMemoStr(d,c){if(c==undefined){c=true}if(d==null||d==""||typeof d!="string"){return d}if(c){d=d.replaceAll("@",CRSAt);d=d.replaceAll(" ",CRSBlank);d=d.replaceAll('"',CRSDQuote);d=d.replaceAll("'",CRSQuote);d=d.replaceAll("&",CRSAnd);d=d.replaceAll(";",CRSSep);d=d.replaceAll(".",CRSColon);d=d.replaceAll("%",CRSPercent);d=d.replaceAll("\r",CRSNewLine);d=d.replaceAll("\n",CRSNewLine);d=d.replaceAll(",",CRSEComma);d=d.replaceAll("->",CRSSubSign+CRSGreaterSign);d=d.replaceAll(">",CRSGreaterSign);d=d.replaceAll("<",CRSLess)}else{d=d.replaceAll(CRSAt,"@");d=d.replaceAll(CRSBlank," ");d=d.replaceAll(CRSDQuote,'"');d=d.replaceAll(CRSQuote,"'");d=d.replaceAll(CRSAnd,"&");d=d.replaceAll(CRSSep,";");d=d.replaceAll(CRSColon,".");d=d.replaceAll(CRSPercent,"%");d=d.replaceAll(CRSNewLine,"\r");d=d.replaceAll(CRSNewLine,"\n");d=d.replaceAll(CRSEComma,",");d=d.replaceAll(CRSSubSign+CRSGreaterSign,"->");d=d.replaceAll(CRSGreaterSign,">");d=d.replaceAll(CRSLess,"<");d=d.replaceAll("&gt;",">");d=d.replaceAll("&lt;","<")}return d}function HandleMemoStr2(d,c){if(c==undefined){c=true}if(d==null||d==""){return d}if(c){d=d.replaceAll(">",CRSGreaterSign);d=d.replaceAll("<",CRSLess)}else{d=d.replaceAll(CRSGreaterSign,">");d=d.replaceAll(CRSLess,"<");d=d.replaceAll("&gt;",">");d=d.replaceAll("&lt;","<")}return d}function indexOf(h,g){for(var e=0,f=h.length;e<f;e++){if(h[e]==g){return e}}return -1}function indexOfLetter(d){var c="ABCDEFGHIJKLMNOPQRSTUVWXYZ";return c.indexOf(d)}function intPower(g,h){var e=1;for(var f=1;f<=h;f++){e*=g}return e}function columnIndexByName(f){var d=0;for(var e=f.length;e>=1;e--){d+=intPower(26,f.length-e)*(indexOfLetter(f.charAt(e-1))-indexOfLetter("A")+1)}return --d}function convert_digit(a){return"ABCDEFGHIJKLMNOPQRSTUVWXYZ".charAt(a)}function columnNameByIndex(b){var c="",e=b+1;do{var d=(e-1)%26;c=convert_digit(d)+c;e=Math.floor((e-1)/26)}while(e!=0);return c}function cellNameToIndex(g){g=g.toUpperCase();var e={pg:"",x:-1,y:-1};var f=0;var h=g.indexOf("!");if(h!=-1){e.pg=g.substr(0,h);g=g.substring(h+1,g.length)}while(f<g.length&&g.charAt(f)>="A"&&g.charAt(f)<="Z"){f++}e.x=columnIndexByName(g.substr(0,f));e.y=parseInt(g.substr(f,g.length-f))-1;return e}function cellIndexToName(f){var d=columnNameByIndex(f.x);var e=f.y+1;return d+e}function rectNameToIndex(f){if(f.indexOf(":")==-1){f+=":"+f}var e=f.split(":");var d=[];d.push(cellNameToIndex(e[0]));d.push(cellNameToIndex(e[1]));return d}function rectIndexToName(b){return cellIndexToName(b[0])+":"+cellIndexToName(b[1])}function getColPlatKId(b){return String(b).replaceAll("{","").replaceAll("}","").replaceAll("-","")}function getColRealKId(b){return"{"+b.substr(0,8)+"-"+b.substr(8,4)+"-"+b.substr(12,4)+"-"+b.substr(16,4)+"-"+b.substr(20)+"}"}function format_text(e,d){if(e==undefined){return""}if(e==undefined||d==""){return d}if(typeof crsReport!="undefined"){var f=crsReport.crsTableIndex.getAColSchema(e)}else{var f=d.colModel.formatoptions;d=e}if(f.datatype=="\u65e5\u671f\u578b"&&f.displaystyle.length>0){d=format_text_date(d,f)}if(f.datatype=="\u6570\u503c\u578b"&&f.displaystyle.length>0){d=format_text_number(d,f)}if(f.datatype=="\u8d27\u5e01\u578b"&&f.displaystyle.length>0){d=format_text_money(d,f)}if(f.datatype=="\u5b57\u7b26\u578b"&&f.displaystyle.length>0){d=d.trim()}else{if(f.datatype=="\u5b57\u7b26\u578b"){d=d.replaceAll("\n","<br>")}}return d}function format_text_money(h,g){if(h==undefined){return""}if(g==undefined){return h}if(g.colModel!=undefined){g=g.colModel.formatoptions}var e="";switch(HandleMemoStr(g.displaystyle,false)){case"_($#,##0_) ($#,##0)":var f=(h>0)?number_format(h):"("+number_format(h.replaceAll("-",""))+")";e="\uffe5"+f;break;case"_($#,##0_) [Red]($#,##0)":var f=(h>0)?number_format(h):'<font color="red">('+number_format(h.replaceAll("-",""))+")</font>";e="\uffe5"+f;break;case"_($#,##0.00_) ($#,##0.00)":var f=(h>0)?number_format(h,2,".",","):"("+number_format(h.replaceAll("-",""),2,".",",")+")";e="\uffe5"+f;break;case"_($#,##0.00_) [Red]($#,##0.00)":var f=(h>0)?number_format(h,2,".",","):'<font color="red">('+number_format(h.replaceAll("-",""),2,".",",")+")</font>";e="\uffe5"+f;break;case'_($* #,##0_) _($* (#,##0) _($* "-"_) _(@_)':var f=(h>0)?number_format(h):"("+number_format(h.replaceAll("-",""))+")";e="\uffe5"+f;break;case'_(* #,##0.00_) _(* (#,##0.00) _(* "-"??_) _(@_)':var f=(h>0)?number_format(h,2,".",","):"("+number_format(h.replaceAll("-",""),2,".",",")+")";e=f;break;case'_($* #,##0.00_) _($* (#,##0.00) _($* "-"??_) _(@_)':var f=(h>0)?number_format(h,2,".",","):"("+number_format(h.replaceAll("-",""),2,".",",")+")";e="\uffe5"+f;break;case"\u5927\u5199":e=(h==0)?"\u96f6\u5706\u6574":numToCny(h);break;default:e=h}return e}function format_text_char(d,c){if(HandleMemoStr(c.displaystyle,false)&&is_blank_char(d)){d=d.replace(/\s/g,"");d=d.replace(/<\/?.+?>/g,"");d=d.replace(/[\r\n]/g,"")}return d}function format_text_date(v,q){if(v==undefined){return""}if(q==undefined){return v}if(q.colModel!=undefined){q=q.colModel.formatoptions}var u="";var t="";var d="";var z="";if(v.indexOf(":")!=-1){if(v.indexOf(" ")==-1){v="0000-00-00 "+v}var h=v.split(" ");var A=h[0].split("-");var w=h[1].split(":");var x=w.length;u=w[0];if(x>1){t=w[1]}if(x>2){d=w[2]}if(x>3){z=w[3]}}else{if(v.indexOf("-")!=-1){var A=v.split("-")}else{if(v.indexOf(".")!=-1){var A=v.split(".")}else{return v}}}var m=A[0];var y=A[1];var r=A[2];if(y==undefined){y=0}if(r==undefined){r=0}switch(HandleMemoStr(q.displaystyle,false).toLowerCase()){case"yyyy\u5e74":v=m;break;case"yyyy\u5e74mm\u6708dd\u65e5":v=m+"\u5e74"+y+"\u6708"+r+"\u65e5";break;case"yyyy\u5e74mm\u6708":v=m+"\u5e74"+y+"\u6708";break;case"yyyy.mm.dd":v=m+"."+y+"."+r;break;case"yyyy.mm":v=m+"."+y;break;case"mm:ss:ms":v=t+"."+d+"."+z;break;case"m/d/yy":y=y>9?y:y.substr(1);r=r>9?r:r.substr(1);m=m.substr(2,3);v=y+"/"+r+"/"+m;break;case"dd/mm/yy":m=m.substr(2,3);v=y+"/"+r+"/"+m;break;case"dd.mmmm.yy":y=is_date_month_str(y);m=m.substr(2,3);v=r+"."+y+"."+m;break;case"d-mmm-y":y=is_date_month_str(y,1);r=r>9?r:r.substr(1);m=m.substr(3);v=r+"-"+y+"-"+m;break;case"d-mmm":y=is_date_month_str(y,1);r=r>9?r:r.substr(1);v=r+"-"+y;break;case"mmmm-yy":y=is_date_month_str(y);m=m.substr(2,3);v=y+"-"+m;break;case"h:mm AM/PM":u=u>9?u:u.substr(1);var s=u<12?"AM":"PM";v=u+":"+t+" "+s;break;case"h:mm:ss AM/PM":u=u>9?u:u.substr(1);var s=u<12?"AM":"PM";v=u+":"+t+":"+d+" "+s;break;case"h:mm":u=u>9?u:u.substr(1);v=u+":"+t;break;case"h:mm:ss":u=u>9?u:u.substr(1);v=u+":"+t+":"+d;break;case"m/d/y h:mm":r=r>9?r:r.substr(1);u=u>9?u:u.substr(1);m=m.substr(3);v=y+"/"+r+"/"+m+" "+u+":"+t+":"+d;break;case"mm:ss":v=t+":"+d;break;case"[h]:mm:ss":u=u>9?u:u.substr(1);v="["+u+"]:"+t+":"+d;break;case"mm:ss.ms":v=t+":"+d+":"+z;break}return v}function format_text_number(k,j){if(k==undefined){return""}if(j==undefined){return k}if(j.colModel!=undefined){j=j.colModel.formatoptions}var l="";switch(HandleMemoStr(j.displaystyle,false)){case"0":l=number_format(k,0,".","");break;case"0.0":l=number_format(k,1,".","");break;case"0.00":l=number_format(k,2,".","");break;case"0.000":l=number_format(k,3,".","");break;case"0.0000":l=number_format(k,4,".","");break;case"0.00000":l=number_format(k,5,".","");break;case"0.000000":l=number_format(k,6);break;case"#,##0":l=number_format(k);break;case"#,##0.0":l=number_format(k,1);break;case"#,##0.00":l=number_format(k,2);break;case"#,##0.000":l=number_format(k,3);break;case"#,##0.0000":l=number_format(k,4);break;case"#,##0.00000":l=number_format(k,5);break;case"#,##0.000000":l=number_format(k,6);break;case"0%":l=number_format(k*100,0,".","")+"%";break;case"0.0%":l=number_format(k*100,1,".","")+"%";break;case"0.00%":l=number_format(k*100,2,".","")+"%";break;case"0.00E+00":if(k<0){return""}var n=k.split(".");var i=n[0].length;if(i>3){if(n[0].substr(3,1)>4){var h=n[0].substr(1,1)+(parseInt(n[0].substr(2,1))+1)}else{var h=n[0].substr(1,2)}}else{var h=n[0].substr(1,2)}var m=i-1>10?i-1:"0"+i-1;l=n[0].substr(0,1)+"."+h+"E"+m;break;case"# ?/?":l=k;break;case"# ??/??":l=k;break;case"(#,##0_) (#,##0)":l=(k>0)?number_format(k):"("+number_format(k.replaceAll("-",""))+")";break;case"(#,##0_) [Red](#,##0)":l=(k>0)?number_format(k):'<font color="red">('+number_format(k.replaceAll("-",""))+")</font>";break;case"(#,##0.00_) (#,##0.00)":l=(k>0)?number_format(k,2,".",","):"("+number_format(k.replaceAll("-",""),2,".",",")+")";break;case"(#,##0.00_) [Red](#,##0.00)":l=(k>0)?number_format(k,2,".",","):'<font color="red">('+number_format(k.replaceAll("-",""),2,".",",")+")</font>";break;case'_(* #,##0_) _(* (#,##0) _(* "-"_) _(@_)':l=(k>0)?number_format(k):"("+number_format(k.replaceAll("-",""))+")";break;case"# #0.0E+0":if(k<0){return""}var n=k.split(".");var i=n[0].length;if(i>3){if(n[0].substr(3,1)>4){var h=n[0].substr(1,1)+(parseInt(n[0].substr(2,1))+1)}else{var h=n[0].substr(1,2)}}else{var h=n[0].substr(1,2)}var m=i-1>10?i-1:"0"+i-1;l=number_format(n[0])+"."+h+"E"+m;break;default:l=k}return l}function is_digit_char(b){if(b>="0"&&b<="9"){return true}else{return false}}function is_letter_char(b){if(b.toUpperCase()>="A"&&b.toUpperCase()>="Z"){return true}else{return false}}function is_han_char(b){if(b.charCodeAt(0)>=128){return true}else{return false}}function is_blank_char(b){if(b==" "||b=="\t"||b=="\r"){return true}else{return false}}function is_id_start_char(b){if(is_letter_char(b)||is_han_char(b)||b=="@"){return true}else{return false}}function is_id_other_char(b){if(is_id_start_char(b)||IsDigitChar(b)||b=="_"){return true}else{return false}}function in_assign_user(l,p,t){var m=false;if(t!=""){var q=t.split(",");for(var d=0,o=q.length;d<o;d++){if(q[d]+"."+p){m=true;break}else{var r=crsReport.userCache.get_dept_and_priv(q[d]);var n=","+r.other_dept+r.dept+",",s=","+r.other_priv+r.priv+",";if(n.indexOf(","+l+",")!=-1||s.indexOf(","+p+",")!=-1){m=true;break}}}}return m}function in_assign_role(j,i){var k=false;if(j!=""){var l=j.split(",");for(var g=0,h=l.length;g<h;g++){if(l[g]!=""&&i.indexOf(","+l[g]+",")!=-1){k=true;break}}}return k}function is_ref_cell(b){if(b!=""&&b.charAt(0)=="{"){return true}else{return false}}function is_date_month_str(f,g){var h=["January","February","March","April","May","June","July","August","September","October","November","December"];var e=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];return g?e[f-1]:h[f-1]}function is_color_position(d){var c=["#000000","#FFFFFF","#FF0000","#00FF00","#0000FF","#FFFF00","#00FFFF","#FF00FF","#008000","#800000","#000080","#808000","#008080","#800080","#C0C0C0","#808080","#9999FF","#339966","#FFFFCC","#FFCCFF","#006666","#80FF80","#6600CC","#CCCCFF","#000080","#00FFFF","#FFFF00","#FF00FF","#008080","#008000","#800080","#0000FF","#CC00FF","#FFCCFF","#FFCCCC","#FFFF99","#CC99FF","#99FFCC","#99CCFF","#CCFF99","#6633FF","#CC33CC","#CC9900","#CCFF00","#99FF00","#66FF00","#666699","#969696","#330066","#993366","#330000","#333300","#339900","#339966","#333399","#333333"];return d<c.length?c[d]:"#FFFFFF"}function number_format(o,r,m,p){o=(o+"").replace(/[^0-9+\-Ee.]/g,"");var s=!isFinite(+o)?0:+o,t=!isFinite(+r)?0:Math.abs(r),k=(typeof p==="undefined")?",":p,q=(typeof m==="undefined")?".":m,l="",n=function(c,a){var b=Math.pow(10,a);return""+Math.round(c*b)/b};l=(t?n(s,t):""+Math.round(s)).split(".");if(l[0].length>3){l[0]=l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,k)}if((l[1]||"").length<t){l[1]=l[1]||"";l[1]+=new Array(t-l[1].length+1).join("0")}return l.join(q)}function numToCny(g){var a="";var p=["\u4e07","\u4ebf","\u4e07","\u5143",""];var l={2:["\u89d2","\u5206",""],4:["\u4edf","\u4f70","\u62fe",""]};var c=["\u96f6","\u58f9","\u8d30","\u53c1","\u8086","\u4f0d","\u9646","\u67d2","\u634c","\u7396"];if(((g.toString()).indexOf(".")>16)||(isNaN(g))){return""}g=((Math.round(g*100)).toString()).split(".");g=(g[0]).substring(0,(g[0]).length-2)+"."+(g[0]).substring((g[0]).length-2,(g[0]).length);g=((Math.pow(10,19-g.length)).toString()).substring(1)+g;var f,m,e,a,d,b,h,o,n=[];for(f=0,m="";f<5;f++,e=f*4+Math.floor(f/4)){a=g.substring(e,e+4);for(d=0,b="",h=a.length;((d<h)&&(parseInt(a.substring(d),10)!=0));d++){n[d%2]=c[a.charAt(d)]+((a.charAt(d)==0)?"":l[h][d]);if(!((n[0]==n[1])&&(n[0]==c[0]))){if(!((n[d%2]==c[0])&&(b=="")&&(m==""))){b+=n[d%2]}}}o=b+((b=="")?"":p[f]);if(!((o==c[0])&&(m==""))){m+=o}}m=(m=="")?c[0]+p[3]:m;if(m!=""){if(m.substr(m.length-1)=="\u4e07"||m.substr(m.length-1)=="\u4ebf"){m+="\u5143"}if(m.substr(m.length-1)!="\u5206"){m+="\u6574"}}return m}function getGuidGenerator(){var b=function(){return(((1+Math.random())*65536)|0).toString(16).substring(1)};return"{"+(b()+b()+"-"+b()+"-"+b()+"-"+b()+"-"+b()+b()+b()).toUpperCase()+"}"}function update_ref(s,z,p,q,y,x){var i="";var v=q.split(",");for(var w=0,t=v.length;w<t;w++){var r=rectNameToIndex(v[w]);if(!x){var o="y"}else{var o="x"}if(y=="ins"){if(r[0][o]>=z){r[0][o]+=p;r[1][o]+=p}else{if(r[1][o]>=z){r[1][o]+=p}}if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(y=="app"){if(r[0][o]>z){r[0][o]+=p;r[1][o]+=p}else{if(r[1][o]>=z){r[1][o]+=p}}if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(y=="del"){if(r[1][o]<z){if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(r[0][o]<z&&r[1][o]<=(z+p-1)){r[1][o]=z-1;if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(r[0][o]<z&&r[1][o]>(z+p-1)){var u=r[1][o];r[1][o]=z-1;if(i!=""){i+=","}i+=rectIndexToName(r);r[1][o]=u;r[0][o]=z+p;r[0][o]-=p;r[1][o]-=p;if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(r[0][o]<=z+p-1&&r[1][o]>(z+p-1)){r[0][o]=z+p;r[0][o]-=p;r[1][o]-=p;if(i!=""){i+=","}i+=rectIndexToName(r)}else{if(r[0][o]>z+p-1){r[0][o]-=p;r[1][o]-=p;if(i!=""){i+=","}i+=rectIndexToName(r)}}}}}}}}}return i}function obj_length(c){var d=0;for(s_key in c){if(c[s_key]!=undefined){d++}}return d}function get_delimiter_key(g,h){var e=0,f="";for(s_key in g){if(typeof g[s_key]=="function"){continue}if(e>0){f+=h}f+=s_key;e++}return f}var b_showing=false;function show_hint(b){if(b_showing){return}b_showing=true;if(crsReport.b_mobile){showLoading(b)}else{$("#loading").show()}}function hide_hint(){if(!b_showing){return}b_showing=false;if(crsReport.b_mobile){hideLoading()}else{$("#loading").hide()}}function ajaxFileUpload(f,i,h,e,g){$("#loading").ajaxStart(function(){$(this).show()}).ajaxComplete(function(){$(this).hide()});$.ajaxFileUpload({url:"/general/reportshop/utils/upload.php",secureuri:false,fileElementId:f,dataType:"json",data:{action:"upload",fileid:f,filetype:i,json:1,newid:g},success:function(a,b){h(a.new_name,e)},error:function(b,c,a){crsReport.show_hint(a)},async:false});return false}function MapKey(b){return b}function get_color(h){var g=(h&parseInt("0x0000FF")).toString(16);if(g.length<2){g="0"+g}var e=((h&parseInt("0x00FF00"))>>8).toString(16);if(e.length<2){e="0"+e}var f=((h&parseInt("0xFF0000"))>>16).toString(16);if(f.length<2){f="0"+f}return"#"+g+e+f}function crs_decode(b){var c=base64decode(b);var h=zip_inflate(c);var a=unescape(h);return a}function crs_encode(b){var c=escape(b);var h=zip_deflate(c);var a=base64encode(h);return a}function set_statis(c,d){$.ajax({url:"/general/reportshop/utils/set_statis.php",type:"post",data:{cur_rep:crsReport.s_postfix,begin_time:c,end_time:d,repid:crsReport.repId,userid:crsReport.userId,writetime:crsReport.writeTime,openmode:crsReport.openMode,P:crsReport.s_P},success:function(a){if(1){$("#load_time",window.parent.document).html(a)}},async:true})}function getCursorPos(i){i.focus();var g=document.selection.createRange();var f=g.duplicate();i.select();var j=document.selection.createRange();var h=0;while(f.compareEndPoints("StartToStart",j)>0){f.moveStart("character",-1);h++}g.select();return h}function setCursorPos(d,f){var e=d.createTextRange();e.moveStart("character",f);e.collapse(true);e.select()}function my_md5(a){return $.md5(a)}var userAgent=navigator.userAgent.toLowerCase();var is_opera=userAgent.indexOf("opera")!=-1&&opera.version();var is_moz=(navigator.product=="Gecko")&&userAgent.substr(userAgent.indexOf("firefox")+8,3);var ua_match=/(trident)(?:.*rv:([\w.]+))?/.exec(userAgent)||/(msie) ([\w.]+)/.exec(userAgent);var is_ie=ua_match&&(ua_match[1]=="trident"||ua_match[1]=="msie")?true:false;function LoadDialogWindow(b,d,f,e,c,a){if(is_ie){window.showModalDialog(b,d,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+c+"px;dialogHeight:"+a+"px;dialogTop:"+e+"px;dialogLeft:"+f+"px",true)}else{window.open(b,d,"height="+a+",width="+c+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+e+",left="+f+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true)}}function show_seal(b,c){var a="/module/sel_seal/?ITEM="+b+"&callback="+c;loc_y=loc_x=200;if(is_ie){loc_x=document.body.scrollLeft+event.clientX-100;loc_y=document.body.scrollTop+event.clientY+170}LoadDialogWindow(a,self,loc_x,loc_y,300,350)}function addSeal2(d,c){if(!has_usb_key()){alert("\u7b7e\u7ae0\u524d\uff0c\u8bf7\u60a8\u5728\u5ba2\u6237\u673a\u4e0a\u63d2\u5165\u6709\u6548\u7684USB KEY\u3002");return}try{if(crsReport.DWebSignSeal.FindSeal(d+"_hw",2)!=""){alert("\u60a8\u5df2\u7ecf\u7b7e\u7ae0\uff0c\u8bf7\u5148\u5220\u9664\u5df2\u6709\u7b7e\u7ae0\uff01");return}var i=SetStore(d);crsReport.DWebSignSeal.SetPosition(0,0,"SIGN_POS_"+d);if(typeof c=="undefined"){show_seal(d,"addSeal2")}else{var g=location.href;var h=g.indexOf("/",8);g=g.substring(0,h);var a=g+"/module/sel_seal/get.php?ID="+c;var b=crsReport.DWebSignSeal.AddSeal(a,d+"_hw")}DWebSignSeal.SetSealSignData(d+"_hw",i);DWebSignSeal.SetMenuItem(d+"_hw",261)}catch(f){}}function addSeal(d,e){try{crsReport.DWebSignSeal.SetCurrUser(crsReport.s_writer+"["+crsReport.crsUserCache.get_name_by_id(crsReport.s_writer)+"]")}catch(f){crsReport.show_hint("\u7b7e\u7ae0\u52a0\u8f7d\u5931\u8d25\uff0c\u8bf7\u68c0\u67e5\u63a7\u4ef6\u662f\u5426\u6b63\u786e\u5b89\u88c5\uff01")}try{if(crsReport.DWebSignSeal.FindSeal(d+"_seal",2)!=""){crsReport.show_hint("\u60a8\u5df2\u7ecf\u7b7e\u7ae0\uff0c\u8bf7\u5148\u5220\u9664\u5df2\u6709\u7b7e\u7ae0\uff01");return}SetStore();crsReport.DWebSignSeal.SetPosition(200,200,"SIGN_POS_"+d);crsReport.DWebSignSeal.addSeal("",d+"_seal");crsReport.DWebSignSeal.SetSealSignData(d+"_seal",str);crsReport.DWebSignSeal.SetMenuItem(d+"_seal",261)}catch(f){crsReport.show_hint("\u7b7e\u7ae0\u52a0\u8f7d\u5931\u8d25\uff0c\u8bf7\u68c0\u67e5\u63a7\u4ef6\u662f\u5426\u6b63\u786e\u5b89\u88c5\uff01")}}function handWrite(e,f){if(!has_usb_key()){alert("\u7b7e\u7ae0\u524d\uff0c\u8bf7\u60a8\u5728\u5ba2\u6237\u673a\u4e0a\u63d2\u5165\u6709\u6548\u7684USB KEY\u3002");return}try{if(typeof(f)=="string"){f=Number(f)}if(crsReport.DWebSignSeal.FindSeal(e+"_hw",2)!=""){crsReport.show_hint("\u60a8\u5df2\u7ecf\u7b7e\u7ae0\uff0c\u8bf7\u5148\u5220\u9664\u5df2\u6709\u7b7e\u7ae0\uff01");return}var g=SetStore(e);crsReport.DWebSignSeal.SetPosition(0,0,"SIGN_POS_"+e);crsReport.DWebSignSeal.HandWrite(0,f,e+"_hw");crsReport.DWebSignSeal.SetSealSignData(e+"_hw",g);crsReport.DWebSignSeal.SetMenuItem(e+"_hw",261)}catch(h){crsReport.show_hint("\u7b7e\u7ae0\u52a0\u8f7d\u5931\u8d25\uff0c\u8bf7\u68c0\u67e5\u63a7\u4ef6\u662f\u5426\u6b63\u786e\u5b89\u88c5\uff01")}}function GetDataStr(l){var o="";var k=$("#SIGN_POS_"+l);var p=$(k).attr("sign_check");if(p!=""){var i=p.split(",");for(var n=0,j=i.length;n<j;n++){if(i[n]!=""){var m=crsReport.crsCommon.get_row_seq($(k).parent("td"));o+=crsReport.crsCommon.get_cell_value(i[n],m)+"\u2522"}}}return o}function SetStore(e){try{var f=GetDataStr(e);crsReport.DWebSignSeal.SetSignData("-");crsReport.DWebSignSeal.SetSignData("+DATA:"+f)}catch(d){}return f}function has_usb_key(){var c=true;var a=$("#tdPass");if(a.length>0){c=OpenDevice(a[0]);if(c){var b=READ_KEYUSER(a[0]);if(b!=crsReport.s_writer){c=false}}}return c};
