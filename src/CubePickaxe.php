<?php

use pocketmine\plugin\PluginBase;
use CustomEnchantments\enchantment\Manager;
use CubePickaxe\enchantment\CubeEnchantment;
use CubePickaxe\EventHandler;
use CubePickaxe\command\CubeCommand;
use pocketmine\block\BlockTypeIds;

class CubePickaxe extends PluginBase
{
    public function onEnable() : void
    {
        Manager::register(new CubeEnchantment());
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
        $this->getServer()->getCommandMap()->register("", new CubeCommand);
    }

    /**
     * @param int $blockTypeId
     * @return bool
     */
    public static function isIndestructibleBlocks(int $blockTypeId) : bool
    {
        $indestructibleBlocks = [
            BlockTypeIds::AIR,
            BlockTypeIds::BARRIER,
            BlockTypeIds::BEDROCK,
            BlockTypeIds::END_PORTAL_FRAME,
            BlockTypeIds::NETHER_PORTAL,
            BlockTypeIds::REINFORCED_DEEPSLATE
        ];
        return in_array($blockTypeId, $indestructibleBlocks);
    }
}
