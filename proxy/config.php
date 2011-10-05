<?
//if you set $is_public=true,anyone can use your phpproxy to surf!
$is_public=false;
//default language
$default_lang="English";
//if $is_public=false,you need specialfied the url;open phpproxy,you will directly browser $show_homepage_url.
$show_homepage_url="http://www.google.com";
//permit users to set proxyhost;
$permit_proxy=true;
$proxy_host="216.17.167.116";
$proxy_port="80";
//disabled javascript?
$disabled_js=false;
//list javascript functions that you want to be baned
$disabled_js_funcs="";
//list urls,eg: $block_popup_url=array("ads.com");that will block any url that contains "ads.com"
$block_popup_url=array();
//list filetypes which do not need to be parsed for changing url
$all_objects = array('.jpg','.jpeg','.gif','.png','.css','.swf','.js','.zip','.gz','.tar','.rar','.exe','.pdf','.xls','.doc','.ppt','.mpg','.mpeg','.mp3','.mid','.midi','.wav','.java','.jar','.class','.xml');
//list filetype baned,this may accelerate the speed of browsering
$ban_objects=array();

//generally,do not change anything below
$tag_attributes = array("A" => "HREF",
                            "LINK" => "HREF",
                            "IMG" => "SRC",
                            "FORM" => "ACTION",
                            "TD"  =>"BACKGROUND",
                            "BODY"  =>"BACKGROUND",
                            "TABLE"  =>"BACKGROUND",
                            "META" => "URL",
                            "SCRIPT" => "SRC"
                            );

//replace $key($url)
$tag_script = array("open"=> "(",
                    ".action"=> "="
                    //"href"=> "=",
                    );
//replace $key($url)
$tag_style = array("url"=> "("
                   );


?>
