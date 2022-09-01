<?php

namespace App\Data;

class MenuMaker
{
    public static function createMenuFromInputs($keyWords)
    {
        $restourantMenu = array();
        foreach ($keyWords as $item) {
            $meal = self::splitStringToMakeMeal($item);

            $reatourantID = $item[0];

            if (array_key_exists($reatourantID, $restourantMenu)) {
                $dishes = $restourantMenu[$reatourantID];
                array_push($restourantMenu[$reatourantID], $meal);
            } else {
                $dishes = array();
                array_push($dishes, $meal);
                $restourantMenu[$reatourantID] = $dishes;
            }
        }
        return $restourantMenu;
    }

    private static function splitStringToMakeMeal($items): Meal
    {
        $m = new Meal(0,0, array());
        $length = count($items);
        for ($i = 0; $i < $length; $i++) {
            switch ($i) {
                case 0 :
                    $m->SetId($items[$i]);
                    break;
                case 1 :
                    $m->SetPrice($items[$i]);
                    break;
                default:
                    if ($items[$i] != null)
                        $m->addItemToMeal($items[$i]);
                    break;
            }
        }
        return $m;
    }
}
