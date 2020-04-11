<?php
error_reporting(0);
function getposition($country,$keyword,$site){
  $mypos = 'None';
$sFichierProxys     = 'scrap_google_proxys.txt';
$sProxyIpPort       = '';
$sProxyLoginMdp     = ''; 
if(@file_exists($sFichierProxys))
{
    $aProxys = @file($sFichierProxys, FILE_SKIP_EMPTY_LINES);
    
    if(!empty($aProxys) ||!is_array($aProxys))
    {
        $rand_keys          = array_rand($aProxys);
        $aProxy             = explode(':', $aProxys[$rand_keys]);
        $sProxyLoginMdp     = $aProxy[2].':'.$aProxy[3];
        $sProxyIpPort       = $aProxy[0].':'.$aProxy[1];
    }
}
if(!empty($sProxyIpPort) && !empty($sProxyLoginMdp)){ 
}else
{
	

$surlGoogle = 'https://www.google.'.$country.'/search?gl='.$country.'&hl=fr&num=100&pws=1&gws_rd=cr&q=';
// navigateurs desktop / mobile (UA)
$aNavigateurs = array(
    "desktop"   => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
    "mobile"    =>"Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25"
);

$aXpath = array(
'desktop'            =>  array(
    'urls'          => '//*[@id="rso"]/div/div/div[21]/div/div/div[1]/a', 
    'titles'        => '//div[@id="ires"]//h3[@class="r"]/a',
    'snippets'      => '//span[@class="st"]'
    ), 
'mobile' =>  array(
    'urls'          => '//a[@class="_Olt _bCp"]/@href', 
    'titles'        => '//div[@class="_H1m _ees"]',
    'snippets'      => '//div[@class="_H1m"]'
    )
);
$iDelai = 2;
$sVersion = 'desktop';
if(isset($_GET['mobile']) && $_GET['mobile']=='mobile'){$sVersion = 'mobile';}
$url_to_use = $surlGoogle.urlencode($keyword);
$nbr_tot_tab1 = 0; 
$tab02 = array();
$tab01 = array();
$doc = new DOMDocument;
$doc->loadHTMLFile($url_to_use);
$i= -1;
$items = $doc->getElementsByTagName('a');
foreach($items as $value) {
	$i++;
$attrs = $value->attributes;
$tab01[$i][0] = trim($value->nodeValue);
$tab01[$i][1] = trim($attrs->getNamedItem('href')->nodeValue . "\n");
};
$count = count($tab01) -1;
$j = -1;
for ($i=0; $i<$count; $i++) 
        {
			$findme = stripos($tab01[$i][1], "/url?q=");
			if ($findme === 0)
			{
				$j++;
		$tab02[$j] = $tab01[$i][1];
		$tab02[$j] = str_replace("/url?q=","",$tab02[$j]);
		$position_sa = stripos($tab02[$j], "&sa=");
		$count_total = iconv_strlen ($tab02[$j]);
		$newstring = substr($tab02[$j], 0, $position_sa);
		$tab02[$j] = $newstring;
    
		
			}
		}	
} 
for ($x = 0; $x<count($tab02)-1;$x++)
{
  if (strpos($tab02[$x], $site) != false)
    {
      $mypos = $x+1;
    }
}
return $mypos;
}
$pays = 'com';
$keyword = 'Developper Tools';
$site = 'wixlib.com';
echo "The Postion Of ".$site." Of Keyword  ".$keyword." In Google .".$pays." Is ".getposition($pays,$keyword,$site);
?>