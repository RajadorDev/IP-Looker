<?php

namespace IP-Looker; 

use pocketmine\plugin\PluginBase;

class IPLooker extends PluginBase 
{
	
	protected $ip = null;
	
	protected $isLooking = false;
	
	protected $searched = false;
	
	protected static $instance = null;
	
	protected $timeout = 20;
	
	protected $preferences = [];
	
	public function onEnable()
	{
			$this->getLogger()->info
			(
			'        §eIP-Looker        ' . PHP_EOL . 
			'§8> §fCreated by: §eRajador Developer' . PHP_EOL . 
			'§8> §9Discord: §erajadortv' . PHP_EOL .
			'§8> §fInstagram: §e@rajadortv' . PHP_EOL . 
			'§8> §cYouTube: §ehttps://www.youtube.com/channel/UC1UJFxth-YRkNuLBqBYyqbA'. PHP_EOL .
			'§8> §bGit§6Hub: §eRajadorDev'
			);
	
		$this->getServer()->getCommandMap()->register('iplooker', new IpLookerCommand($this));
		$this->initConfig();
		$this->startIpLooker();
	}
	
	public function initConfig()
	{
		$this->saveResource('config.yml');
		$this->preferences = $this->getConfig()->getAll();
		if(isset($preferences['check-timout']) && is_numeric($timeout = $this->preferences['check-timout']))
		{
			$this->timeout = (int) $this->preferences['check-timout'];
		}
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
