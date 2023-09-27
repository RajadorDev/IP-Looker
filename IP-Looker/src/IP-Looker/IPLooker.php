<?php

/*
  
  Rajador Developer

  ▒█▀▀█ ░█▀▀█ ░░░▒█ ░█▀▀█ ▒█▀▀▄ ▒█▀▀▀█ ▒█▀▀█ 
  ▒█▄▄▀ ▒█▄▄█ ░▄░▒█ ▒█▄▄█ ▒█░▒█ ▒█░░▒█ ▒█▄▄▀ 
  ▒█░▒█ ▒█░▒█ ▒█▄▄█ ▒█░▒█ ▒█▄▄▀ ▒█▄▄▄█ ▒█░▒█

  GitHub: https://github.com/RajadorDev

  Discord: rajadortv


*/


namespace IP_Looker; 

use pocketmine\Player;

use pocketmine\plugin\PluginBase;

class IPLooker extends PluginBase 
{
	
	protected $ip = null;
	
	protected $isLooking = false;
	
	protected $searched = false;
	
	protected static $instance = null;
	
	protected $commandPermission = null;
	
	protected $timeout = 20;
	
	protected $preferences = [];
	
	protected $waitingResponse = [];
	
	public function onEnable()
	{
			$this->getLogger()->info
			(
			PHP_EOL.
			'        §eIP-Looker        ' . PHP_EOL . 
			'§8> §fCreated by: §eRajador Developer' . PHP_EOL . 
			'§8> §9Discord: §erajadortv' . PHP_EOL .
			'§8> §fInstagram: §e@rajadortv' . PHP_EOL . 
			'§8> §cYouTube: §ehttps://www.youtube.com/channel/UC1UJFxth-YRkNuLBqBYyqbA'. PHP_EOL .
			'§8> §bGit§6Hub: §eRajadorDev'
			);
			
		$this->initConfig();
		$allowAll = false;
		if(isset($this->preferences['allow-all']) && $this->preferences['allow-all'] == 'true')
		{
			$allowAll = true;
		}
		$this->getServer()->getCommandMap()->register('iplooker', new IpLookerCommand($this, $this->commandPermission, $allowAll));
		self::$instance = $this;
		$this->startIpLooker();
	}
	
	public function initConfig()
	{
		@mkdir($this->getDataFolder());
		$this->saveResource('config.yml');
		$this->preferences = $this->getConfig()->getAll();
		$preferences = $this->preferences;
		
		if(isset($preferences['check-timout']) && is_numeric($timeout = $preferences['check-timout']))
		{
			$this->timeout = (int) $this->preferences['check-timout'];
		}
		
		if(isset($preferences['command-permission']))
		{
			$this->commandPermission = $preferences['command-permission'];
		}
		
	}
	
	public function getConfigValue(String $index, $default = false)
	{
		if(isset($this->preferences[$index]))
		{
			return $this->preferences[$index];
		}
		return $default;
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
		$this->getServer()->getScheduler()->scheduleAsyncTask(new IpLookerTask($this->timeout));
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
		foreach ($this->waitingResponse as $user)
		{
			if($user == 'console')
			{
				continue;
			}
			
			$user = $this->getServer()->getPlayerExact($user);
			if($user instanceof Player)
			{
				$user->sendMessage('§eIp found: §f'.$this->ip);
			}
			
		}
	}
	
	public function addWaiterForResponse(Player $p)
	{
		$this->waitingResponse[] = $p->getName();
	}
	
	
	
}
