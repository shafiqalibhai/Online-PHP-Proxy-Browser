<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>[=PhpProxy=] ���ߴ���--></title>
<script>
function chg_lang(){
   document.phpproxy.submit();
}
</script>
</head>
<body bgcolor="#3399CC" text="#000000">
<form action="" method=post name="phpproxy" id="phpproxy">
  <blockquote>
    <div align="left">
      <p><strong><font size="5" face="Arial, Helvetica, sans-serif">[=PhpProxy=]
        ���ߴ���--></font></strong></p>
      <p> <font size="5">Url&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:
        <input type=text  height=20 name=url >
        <font size="3">��Ҫ���ʵ���ַ</font><br>
        <font size="2"> һ��ɲ���ñ���������ip</font><font size="2">�������Ҫ�ô������������� ���������
        ip</font><br>
        Proxy Server:
        <input type=text  height=20 name=proxyserver>
        </font><font size="5"><br>
        <font size="5"> Proxy Port&nbsp;&nbsp;:
        <input type=text  height=20 name=proxyport >
        <font size="3">����������Ķ˿ں�(Ĭ��Ϊ80)</font> </font></p>
      <p>
        <font size="5"> Language&nbsp;&nbsp;&nbsp;&nbsp;:
        <select  height=20 name=lang onchange="chg_lang()">
        <option value="" >====ѡ �� �� ��====</option>
        <?
        if ($dh = opendir(getcwd())) {
            while (($file = readdir($dh)) !== false) {
               if ($file != '..' && $file != '.'){
                   $p=pathinfo($file);
                   $value=substr($file,0,strlen($file)-strlen($p['extension'])-1);
                   echo "<option value=\"$value\" >==$value==</option>";

               }

            }
            closedir($dh);
        }
        ?>
        </select>
      <p><br>
        <input type=submit height=16 name=phpproxy value="�����ҳ">
        <input type="checkbox" name="hide_mini_form" onclick="{document.phpproxy.hide_mini_form.value=document.phpproxy.hide_mini_form.value="checked" ? "":"checked";}">Hide Form
      </p>
      </div>
  </blockquote>
</form>

<blockquote>
  <p><tt>�����б�(version 2.1)��</tt></p>
  <p><tt>1.֧��ͼ����ʾ��</tt></p>
  <p><tt>2.�����������get��post���ݣ����Դ���cookie������֧���ϴ��ļ���</tt></p>
  <p><tt>3.֧��http��httpsЭ�飬�� https ��Ҫ php ��װ openssl ģ�顣</tt></p>
  <p><tt>4.֧�ִ�����������á�</tt>
  <p><tt>5.�����������ļ�,���������ø����,���Կ���phpproxy,Ҳ�ɽ�ָ��ĳ����ҳ��</tt></p>
  <br>
  </p>
  <p>&nbsp; </p>
</blockquote>
<div align="center">

  <p><font size="3">]<a href="http://yabsoft.biz" target="_blank">��վ��</a>[===]<a href="http://members.lycos.co.uk/dotop/phpBB2" target="_blank">
    ��̳֧��</a>[</font></p>
  <p><font size="3">PhpProxy&copy;�Ų���2004-2005</font></p>
</div>
