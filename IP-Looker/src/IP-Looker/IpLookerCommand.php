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

use pocketmine\command\{CommandSender, Command};

class IpLookerCommand extends Command 
{
	
	protected $system = null;
	
	public function __construct(IPLooker $system, $permission = 'iplooker.adm', bool $allowAll = false)
	{
		$this->system = $system;
		parent::__construct
		(
			'ip',
			'Show server ip address',
			null,
			['serverip', 'iplooker', 'address', 'adr']
		);
		if(!is_null($permission) && !$allowAll)
		{
			$this->setPermission($permission);
		}
	}
	
	public function execute(CommandSender $p, string $label, array $args)
	{
		
		$prefix = '§8-==(§eIPLooker§8)==-';
		
		if($this->testPermission($p))
		{
			
			if(isset($args[0]) && $args[0] == 'retry')
			{
				if (!$this->system->isLookingForIp())
				{
					$p->sendMessage
					(
						$prefix . PHP_EOL . 
						'§8> §eLooking for server address....'
					);
					$this->system->startIpLooker();
				}else{
					$p->sendMessage
					(
						$prefix . PHP_EOL .
						'§8> §ePlease wait, the address is still being sought'
					);
				}
				return true;
			}
			
			
			
			if($this->system->haveServerIp())
			{
				$ip = $this->system->getServerIp();
				$p->sendMessage
				(
					$prefix.PHP_EOL.
					'§8> §eServer Address: §f' . $ip
				);
			}elseif($this->system->isLookingForIp())
			{
				$p->sendMessage
				(
					$prefix . PHP_EOL .
					'§8> §ePlease wait, the address is still being sought'
				);
			}elseif($this->system->wasSearchedBefore())
			{
				$p->sendMessage
				(
					$prefix . PHP_EOL .
					'§8> §cIP not found, maybe the server is offline' . PHP_EOL .
					'§8> §cTo retry use: §f/' . $label . ' retry'
				);
				
			}
		}
	}
	
}