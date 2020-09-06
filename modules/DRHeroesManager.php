<?php


require_once('Heroes/DRStandardHero.class.php');

/* 
 * Base Game Heroes
 */


/* Novice */
require_once('Heroes/DRSpellsword.class.php');
require_once('Heroes/DRKnight.class.php');
require_once('Heroes/DRHalfGoblin.class.php');
require_once('Heroes/DRMercenary.class.php');
require_once('Heroes/DRMinstrel.class.php');
require_once('Heroes/DREnchantress.class.php');
require_once('Heroes/DROccultist.class.php');
require_once('Heroes/DRCrusader.class.php');

/* Master */
require_once('Heroes/DRBattlemage.class.php');
require_once('Heroes/DRChieftain.class.php');
require_once('Heroes/DRCommander.class.php');
require_once('Heroes/DRDragonSlayer.class.php');
require_once('Heroes/DRBard.class.php');
require_once('Heroes/DRBeguiler.class.php');
require_once('Heroes/DRNecromancer.class.php');
require_once('Heroes/DRPaladin.class.php');

/* 
 * Heroes Pack 1 
 */

/* Novice */
require_once('HeroesPackOne/DRAlchemist.class.php');
require_once('HeroesPackOne/DRViking.class.php');
require_once('HeroesPackOne/DRScout.class.php');
require_once('HeroesPackOne/DRSorceress.class.php');


/* Master */
require_once('HeroesPackOne/DRThaumaturge.class.php');
require_once('HeroesPackOne/DRUndeadViking.class.php');
require_once('HeroesPackOne/DRDungeoneer.class.php');
require_once('HeroesPackOne/DRDrakeKin.class.php');


class DRHeroesManager
{
    static function getHero($heroComponent, $game)
    {
        if ($heroComponent == null) {
            return new DRStandardHero($game, null);
        } else {
            $hero_type = $heroComponent['type'] . '_' . $heroComponent['value'];
            $card = $game->card_types[$hero_type];
            $class = $game->card_types[$hero_type]['heroclass'];
            return new $class($game, $card);
        }
    }
}
