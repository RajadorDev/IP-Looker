<?php

namespace IP-Looker;

use pocketmine\Server;

use pocketmine\plugin\PluginBase;

class IpLookerTask extends PluginBase 
{
	
	const API = 'https://api.my-ip.io/ip';
	
	protected $timeout = 20;
	
	public function __construct($timeout = null)
	{
		if($timeout !== null)
		{
			$this->timeout = (int) $timeout;
		}
	}
	
	public function onRun()
	{
		$ch = curl_init(IpLookerTask::API);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0 PocketMine-MP"]);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
    $response = curl_exec($ch);
    if(curl_errno($ch))
    {
    	$this->setResult([false, curl_error($ch)]);
    }else{
      $this->setResult([true, $response]);
    }
    curl_close($ch);
	}
	
	public function onCompletion(Server $s)
	{
		$response = $this->getResult();
		IPLooker::getInstance()->finishIpLooker($response);
	}
	
}