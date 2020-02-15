<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPA extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpa", "Asks the player if you can teleport to them", "<player>", false, ["call", "tpask"]);
        $this->setPermission("essentials.tpa");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::GOLD . "[Error] Player not found");
            return false;
        }
        if($player->getName() === $sender->getName()){
            $sender->sendMessage(TextFormat::GOLD . "[Error] You can't teleport to yourself");
            return false;
        }
        $this->getAPI()->requestTPTo($sender, $player);
        $player->sendMessage(TextFormat::RED . $sender->getName() . TextFormat::AQUA . " wants to teleport to you, please use:\n/tpaccept to accepts the request\n/tpdeny to decline the invitation");
        $sender->sendMessage(TextFormat::YELLOW . "Teleport request sent to " . $player->getDisplayName() . "!");
        return true;
    }
} 
