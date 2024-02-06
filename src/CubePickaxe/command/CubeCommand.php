<?php

namespace CubePickaxe\command;

use pocketmine\command\Command;
use pocketmine\permission\PermissionManager;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\item\Pickaxe;
use pocketmine\item\enchantment\EnchantmentInstance;
use CustomEnchantments\enchantment\Manager;
use CubePickaxe\enchantment\CubeEnchantment;
use CustomEnchantments\utils\Utils;

class CubeCommand extends Command
{
    public const COMMAND_PERMISSION_CUBE = "command.permission.cube";

    public function __construct()
    {
        parent::__construct("cube", "Зачарование кирки на §bКуб");
        $root = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
        DefaultPermissions::registerPermission(new Permission(self::COMMAND_PERMISSION_CUBE), [$root]);
        $this->setPermission(self::COMMAND_PERMISSION_CUBE);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if ($sender instanceof Player)
        {
            $inventory = $sender->getInventory();
            $item = $inventory->getItemInHand();
            if  ($item instanceof Pickaxe)
            {
                $item->addEnchantment(new EnchantmentInstance(Manager::getEnchantmentById(CubeEnchantment::CUBE)));
                Utils::setCustomName($item);
                $inventory->setItemInHand($item);
                $sender->sendMessage("Вы§9 зачаровали§f кирку в руке");
            } else
            {
                $sender->sendMessage("Возьмите в руку§9 кирку§f, которую хотите зачаровать");
            }
        } else
        {
            $sender->sendMessage("§cИспользуйте только в игре");
        }
    }
}
