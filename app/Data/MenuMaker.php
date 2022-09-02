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
                $restourantMenu[$reatourantID][] = $meal;
            } else {
                $dishes = array();
                $dishes[] = $meal;
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

    public static function getAllPermutations($array = []): array
    {
        if (empty($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $key => $value) {
            unset($array[$key]);
            $subPermutations = self::getAllPermutations($array);
            $result[] = [$key => $value];
            foreach ($subPermutations as $sub) {
                $result[] = array_merge([$key => $value], $sub);
            }
        }
        return $result;
    }
    public static function menuSortArray($meals = []): array
    {
        $sortedMealsByRestaurant = [];
        foreach ($meals as $mealKey => $item) {
            foreach ($item as $key => $val) {
                if (is_null($val)) {
                    unset($meals[$mealKey][$key]);
                    continue;
                }
                if ($key > 1) {
                    $sortedMealsByRestaurant[$item[0]][$mealKey][0] = $item[0];
                    $sortedMealsByRestaurant[$item[0]][$mealKey][1] = $item[1];
                    $sortedMealsByRestaurant[$item[0]][$mealKey]['items'][] = $val;
                    unset($meals[$mealKey][$key]);
                }
            }
        }
        return $sortedMealsByRestaurant;
    }
    public static function getMinimumPricesArray($sortedMealsByRestaurant = [], $dinner_arr_from_input = []): array
    {
        $minimumPrices = [];
        foreach ($sortedMealsByRestaurant as $restaurantId => $meals) {
            $minSum = PHP_INT_MAX;
            foreach (MenuMaker::getAllPermutations($meals) as $row) {
                $items = [];
                foreach ($row as $meal) {
                    $items = array_merge($items, $meal['items']);
                }
                $items = array_unique($items);
                $currentSum = array_sum(array_column($row, '1'));
                $diff = array_diff($dinner_arr_from_input, $items);
                if (!empty($diff)) {
                    continue;
                }
                if ($minSum == PHP_INT_MAX) {
                    $minSum = array_sum(array_column($row, '1'));
                }
                if ($currentSum < $minSum) {
                    $minSum = $currentSum;
                }
            }
            $minimumPrices[$restaurantId] = $minSum;
        }
        return $minimumPrices;
    }

    public static function searchBestPriceFromPossible($minimumPrices =[]): array
    {
        $foundedMinPrice = PHP_INT_MAX;
        $foundedRestaurantId = PHP_INT_MAX;
        foreach ($minimumPrices as $restaurantId => $minimumPrice) {
            if ($foundedMinPrice == PHP_INT_MAX && $foundedRestaurantId == PHP_INT_MAX) {
                $foundedMinPrice = $minimumPrice;
                $foundedRestaurantId = $restaurantId;
            }
            if ($minimumPrice < $foundedMinPrice && $minimumPrice) {
                $foundedMinPrice = $minimumPrice;
                $foundedRestaurantId = $restaurantId;
            }
        }
        return [$foundedMinPrice, $foundedRestaurantId];
    }

    public static function removeSpaces($items =[]): array
    {
        $items = array_map(function ($item) {
            return array_map('trim', $item);
        }, $items);
        return $items;
    }
}
