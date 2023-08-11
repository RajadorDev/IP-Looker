<?php

namespace IPLooker; //TODO fzr depois

use pocketmine\plugin\PluginBase;

class IPLooker extends PluginBase 
{
	
	protected $ip = null;
	
	protected $isLooking = false;
	
	public function onEnable()
	{
		
	}
	
	public function isLookingForIp() : bool 
	{
		return $this->isLooking;
	}
	
	public function startIpLooker()
	{
		
	}
	
	public function finishIpLooker(array $result)
	{
		if($result[0] == false)
		{
			$error = isset($result[1]) ? $result[1] : 'Unknow ERROR';
			return $this->getLogger()->critical($error);
		}
		
		$this->ip = $result[1];
		$this->getLogger()->notice('§eIP found: §f' . $result[1]);
	}
	
	
	
}
