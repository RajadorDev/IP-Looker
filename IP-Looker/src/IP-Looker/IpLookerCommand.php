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
			
			if(isset($args[0]) && $args[0] == 'retry')
			{
				if (!$this->system->isLookingForIp())
				{
					$p->sendMessage
					(
						$prefix . PHP_EOL . 
						'§8> §eLooking for server address....'
					);
				}else{
					$p->sendMessage
					(
						$prefix . PHP_EOL .
						'§8> §ePlease wait, the address is still being sought'
					);
				}
				return true;
			}
			
			
			$prefix = '§8-==(§eIPLooker§8)==-';
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