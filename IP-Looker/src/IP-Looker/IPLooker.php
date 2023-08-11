<?php

namespace IP-Looker; 

use pocketmine\plugin\PluginBase;

class IPLooker extends PluginBase 
{
	
	protected $ip = null;
	
	protected $isLooking = false;
	
	protected $searched = false;
	
	protected static $instance = null;
	
	public function onEnable()
	{
		$this->getServer()->getCommandMap()->register('iplooker', new IpLookerCommand($this));
		$this->startIpLooker();
	}
	
	public static function getInstance() : IPLooker 
	{
		return self::$instance;
	}
	
	/** @return String | null **/
	public function getServerIp() 
	{
		return $this->ip;
	}
	
	public function haveServerIp() : bool
	{
		return is_string($this->ip);
	}
	
	public function wasSearchedBefore() : bool 
	{
		return $this->searched;
	}
	
	public function isLookingForIp() : bool 
	{
		return $this->isLooking;
	}
	
	public function startIpLooker()
	{
		$this->getServer()->getScheduler()->scheduleAsyncTask(new IpLookerTask);
		$this->isLooking = true;
		$this->getLogger()->notice('Looking for server address....');
	}
	
	public function finishIpLooker(array $result)
	{
		$this->searched = true;
		$this->isLooking = false;
		if(!$result[0])
		{
			$error = isset($result[1]) ? $result[1] : 'Unknow ERROR';
			return $this->getLogger()->critical($error);
		}
		
		$this->ip = $result[1];
		$this->getLogger()->notice('§eIP found: §f' . $result[1]);
	}
	
	
	
}
