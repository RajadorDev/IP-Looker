<?php

namespace IP-Looker;

use pocketmine\command\{CommandSender, Command};

use IPLooker;

class IpLookerCommand extends Command 
{
	
	protected $system = null;
	
	public function __construct(IPLooker $system)
	{
		$this->system = $system;
	}
	
	public function execute(CommandSender $p, String $label, array $args)
	{
		if($this->testPermission($p))
		{
			$prefix = '§8-==(§eIPLooker§8)==-';
			if($this->system->haveServerIp())
			{
				$ip = $this->system->getServerIp();
				$p->sendMessage
				(
					$prefix.PHP_EOL.
					'§8> §eServer Address: §f' . $ip
				);
			}
		}
	}
	
}