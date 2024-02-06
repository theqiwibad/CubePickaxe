<?php

namespace CubePickaxe;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Pickaxe;
use CustomEnchantments\enchantment\Custom;
use CubePickaxe\enchantment\CubeEnchantment;
use pocketmine\math\Vector3;
use CubePickaxe;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\particle\BlockBreakParticle;

class EventHandler implements Listener
{
    function BlockBreakEvent(BlockBreakEvent $event) : void
    {
        $item = $event->getItem();
        foreach ($item->getEnchantments() as $enchantment)
        {
            $customEnchantment = $enchantment->getType();
            if ($item instanceof Pickaxe and $customEnchantment instanceof Custom)
            {
                if ($customEnchantment->getId() === CubeEnchantment::CUBE)
                {
                    $damage = $item->getDamage();
                    $maxDurability = $item->getMaxDurability();
                    $player = $event->getPlayer();
                    $position = $event->getBlock()->getPosition();
                    $brokenBlocks = 0;
                    if ($damage >= $maxDurability - 108 and $damage <= $maxDurability - 27):
                        $player->sendTitle("§l§cDURABILITY", "§7Ваша кирка скоро сломается!");
                    endif;
                    for ($x = $position->getX() - 1; $x <= $position->getX() + 1; $x++)
                    {
                        for ($y = $position->getY() - 1; $y <= $position->getY() + 1; $y++)
                        {
                            for ($z = $position->getZ() - 1; $z <= $position->getZ() + 1; $z++)
                            {
                                $targetPosition = new Vector3($x, $y, $z);
                                $y = $targetPosition->getFloorY();
                                $world = $position->getWorld();
                                if ($y < $world::Y_MAX and $y > $world::Y_MIN)
                                {
                                    $targetBlock = $world->getBlock($targetPosition);
                                    if ($player->isSurvival())
                                    {
                                        $event->setDrops([]);
                                        foreach ($targetBlock->getDrops($item) as $drop):
                                            $world->dropItem($targetPosition->add(0.5, 0.5, 0.5), $drop);
                                        endforeach;
                                    }
                                    if (CubePickaxe::isIndestructibleBlocks($targetBlock->getTypeId()))
                                    {
                                        $world->setBlock($targetPosition, $targetBlock);
                                    } else
                                    {
                                        $world->setBlock($targetPosition, VanillaBlocks::AIR());
                                        $world->addParticle($targetPosition, new BlockBreakParticle($targetBlock));
                                        $brokenBlocks++;
                                    }
                                }
                            }
                        }
                    }
                    if ($player->isSurvival())
                    {
                        $item->applyDamage($brokenBlocks);
                        $player->getInventory()->setItemInHand($item);
                    }
                }
            }
        }
    }
}
