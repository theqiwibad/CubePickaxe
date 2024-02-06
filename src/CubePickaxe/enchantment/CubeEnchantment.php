<?php

namespace CubePickaxe\enchantment;

use CustomEnchantments\enchantment\Custom;
use pocketmine\item\enchantment\Rarity;

class CubeEnchantment extends Custom
{
    public const CUBE = 100;

    public function __construct()
    {
        parent::__construct(self::CUBE, "Cube", "Куб", Rarity::MYTHIC, 1);
    }
}
