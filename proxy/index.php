<?php
    @session_start();
    require 'config.php';
	require 'http.php';
    require 'URI.class.php';
    require 'ParseHtml.php';
	@set_time_limit(0);
 
	$http=new http_class;

	/* Connection timeout */
	$http->timeout=0;

	/* Data transfer timeout */
	$http->data_timeout=0;
	$http->debug=0;
	$http->html_debug=0;
	$http->follow_redirect=1;

	/*
	 *  How many consecutive redirected requests the class should follow.
	 */
	$http->redirection_limit=5;

	/*
	 *  If your DNS always resolves non-existing domains to a default IP
	 *  address to force the redirection to a given page, specify the
	 *  default IP address in this variable to make the class handle it
	 *  as when domain resolution fails.
	 */

    $http->exclude_address="";

    if($_GET['phpproxy']){
       if(session_is_registered('proxyserver')) session_unregister('proxyserver');
       if(session_is_registered('proxyport')) session_unregister('proxyport');
       if(session_is_registered('cookies')) session_unregister('cookies');
       if(session_is_registered('hide_mini_form')) session_unregister('hide_mini_form');
    }
    if($_GET[proxyserver]&&$permit_proxy==false){
        die("Do not permit to use proxy!");
    }
    if($_GET[proxyserver]&&$permit_proxy==true){
        $_SESSION['proxyserver']=$_GET[proxyserver];
    }
    if($_GET[proxyport]&&$permit_proxy==true){
        $_SESSION['proxyport']=$_GET[proxyport];
    }
    if($_SESSION['proxyserver']){
        $http->proxy_host_name =$_SESSION['proxyserver'];
    }
    if($_SESSION['proxyport']){
        $http->proxy_host_port =$_SESSION['proxyport'];
    }
    if($permit_proxy==false){
        $http->proxy_host_name =$proxy_host;
        $http->proxy_host_port =$proxy_port;
    }
    if($_GET['lang']){
        $my_lang=$_GET['lang'];
    }
    else{
        $my_lang=$default_lang;
    }
    if($_GET['hide_mini_form']){
        $_SESSION['hide_mini_form']="checked";
    }
	/*
	 *  If basic authentication is required, specify the user name and
	 *  password in these variables.
	 */

	$user="";
	$password="";
	$authentication=(strlen($user) ? UrlEncode($user).":".UrlEncode($password)."@" : "");

    foreach($_GET as $key=> $value) {
        $myquery.=$key."=".$value."&";
    }
    if($myquery) $myquery="?$myquery";
    $myquery=substr($myquery,0,strlen($myquery)-1);
    $path=$HTTP_SERVER_VARS["PHP_SELF"];

    if(!$_GET['url']&&!$_GET['phproxy']){
        if((strpos($path,"http/")==false)&&(strpos($path,"https/")==false)){
            //first run,print form,if your site is public
            if($is_public==true){
                $self_url = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS["SCRIPT_NAME"];
                $fp = @fopen(dirname($self_url)."/lang/".$my_lang.".php","r");
                while($data = fread($fp, 4096)) $content.=$data;
                echo $content;
                die();
            }
            //not public,only use to access to my site
            if($is_public==false){
                $url=$show_homepage_url;
            }
        }else{
            //parse url: ..phpproxy/index.php/http/your/url
            //to http://your/url
            if(strpos($path,"http/")!=false){
                $urlparts=explode('http/',$path);
                $url= "http://".$urlparts[1].$myquery;
            }
            if(strpos($path,"https/")!=false){
                $urlparts=explode('https/',$path);
                $url= "https://".$urlparts[1].$myquery;
            }
        }
    }
    else{
         $url=$_GET['url'];
    }

    $url_ext=strtolower(strrchr(basename($url),'.'));

    if (in_array($url_ext,$all_objects)){
        if(!in_array($url_ext,$ban_objects)){
	    $error=$http->GetRequestArguments($url,$arguments);
	    $error=$http->Open($arguments);
	    if($error=="")
	    {
		$error=$http->SendRequest($arguments);
		if($error=="")
		{
			$headers=array();
			$error=$http->ReadReplyHeaders($headers);
			if($error=="")
			{
				for(Reset($headers),$header=0;$header<count($headers);Next($headers),$header++)
				{
					$header_name=Key($headers);
					if(GetType($headers[$header_name])!="array")
					{
						switch(strtolower($header_name))
						{
							case "content-type":
							case "content-length":
								Header($header_name.": ".$headers[$header_name]);
                                break;
						}
					}
				}
				for(;;)
				{
					$error=$http->ReadReplyBody($body,1000);
					if($error!=""
					|| strlen($body)==0)
						break;
                    $content.=$body;
				}
                die($content);
			}
		}
		$http->Close();
	    }
        }
    }

    //post data to server,note:first run phpproxy,do not post data to server
    if($_GET && ($_GET['phpproxy']=="")){
       $error=$http->GetRequestArguments($url,$arguments);
       //if file is uploaded,post file to server
       if($_FILES){
           foreach($_FILES as $key=>$file){
              $data=@fread(fopen($file[tmp_name],'r'),filesize($file[tmp_name]));
              $postfiles[]=array(
                       'name' => $key,
					   'content-type' =>$file[type],
					   'filename' =>$file[name],
					   'data' =>$data
               );
           }
           $arguments["PostFiles"]=$postfiles;
           $arguments["Referer"]=$url;
           $http->Multipart_post=true;
       }

	   $arguments["RequestMethod"]="POST";
	   $arguments["PostValues"]=$_GET;
       $arguments["Referer"]=$url;
	   $error=$http->Open($arguments);

	   if($error=="")
	   {
		$error=$http->SendRequest($arguments);

		if($error=="")
		{
			$headers=array();
			$error=$http->ReadReplyHeaders($headers);
			if($error=="")
			{

                $url=strtolower($http->protocol)."://".$http->host_name.(($http->host_port==0 || $http->host_port==80) ? "" : ":".$http->host_port).$http->request_uri;

                $pri_cookies= $http->cookies;
                $_SESSION['cookies'][$http->host_name]=$pri_cookies;

				for(;;)
				{
					$error=$http->ReadReplyBody($body,1000);
					if($error!=""
					|| strlen($body)==0)
						break;
                    $content.=$body;
	 			}
                $myURI =& new Uri($url);
                $HtmlParser =& new ParseHtml;
                $HtmlParser->tag_attributes=$tag_attributes;
                $HtmlParser->tag_style=$tag_style;
                $HtmlParser->Parse($content,$myURI);
                $content = $HtmlParser->html;
                if(!$_SESSION['hide_mini_form']) show_mini_form();
                echo  $content;
			}
		}

		$http->Close();
	    }
        if(strlen($error))
		echo "<CENTER><H2>Error: ",$error,"</H2><CENTER>\n";
    }
    else
    {

	$error=$http->GetRequestArguments($url,$arguments);
	/* Set additional request headers */
	$arguments["Headers"]["Pragma"]="nocache";
/*
	Is it necessary to specify a certificate to access a page via SSL?
	Specify the certificate file this way.
	$arguments["SSLCertificateFile"]="my_certificate_file.pem";
	$arguments["SSLCertificatePassword"]="some certificate password";
*/
	$error=$http->Open($arguments);
	if($error=="")
	{
		$error=$http->SendRequest($arguments);

		if($error=="")
		{
            $pri_cookies= $http->cookies;
            $_SESSION['cookies'][$http->host_name]=$pri_cookies;
            if($pri_cookies) $_SESSION['cookies']=$pri_cookies;//$_SESSION['cookies'][$http->host_name]=$pri_cookies;

			$headers=array();
			$error=$http->ReadReplyHeaders($headers);
			if($error=="")
			{

                $url=strtolower($http->protocol)."://".$http->host_name.(($http->host_port==0 || $http->host_port==80) ? "" : ":".$http->host_port).$http->request_uri;
				for(;;)
				{
					$error=$http->ReadReplyBody($body,1000);
					if($error!=""
					|| strlen($body)==0)
						break;
                    $content.=$body;
				}
                $myURI =& new Uri($url);
                $HtmlParser =& new ParseHtml;
                $HtmlParser->tag_attributes=$tag_attributes;
                $HtmlParser->tag_style=$tag_style;
                $HtmlParser->Parse($content,$myURI);
                $content = $HtmlParser->html;
                if(!$_SESSION['hide_mini_form']) show_mini_form();
                echo  $content;
                
			}
		}
		$http->Close();
	}
	if(strlen($error))
		echo "<CENTER><H2>Error: ",$error,"</H2><CENTER>\n";
  }
  

  
  
  function show_mini_form(){
      global $url;
      global $http;

      $mini_form_code="<form action=\"\" method=get name=\"phpproxy\" >
       
        <input type=text  height=20 size=50 name=url value=\"$url\">
       
        <input type=text  height=20 size=15 name=proxyserver value=\"$http->proxy_host_name\">
       
        <input type=text  height=20 size=1 name=proxyport value=\"$http->proxy_host_port\">
        <input type=submit height=16 name=phpproxy value=\"Submit\">
        </form>\n";
      echo $mini_form_code;

  }
?>
