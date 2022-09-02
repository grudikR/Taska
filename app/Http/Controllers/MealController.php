<?php

namespace App\Http\Controllers;

use App\Imports\MealsImport;
use App\Data\Meal;
use App\Data\MenuMaker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MealController extends Controller
{
    /**
     * Return view with data
     *
     * @return void
     */
    public function index()
    {
        $meals = Meal::all();
        return view('meals', compact('meals'));
    }

    /**
     * Import User View
     *
     * @return void
     */
    public function importMeal()
    {
        return view('import');
    }

    function getAllPermutations($array = [])
    {
        if (empty($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $key => $value) {
            unset($array[$key]);
            $subPermutations = $this->getAllPermutations($array);
            $result[] = [$key => $value];
            foreach ($subPermutations as $sub) {
                $result[] = array_merge([$key => $value], $sub);
            }
        }
        return $result;
    }

    /**
     * Import User data through sheet
     *
     * @return void
     */
    public function import(Request $request)
    {
        if(!$request->filled('dinnerItems')){
            return redirect()->back()->with('failed', 'No dinner items input!');
        }

        if (!$request->file('csvfile')) {
            return redirect()->back()->with('failed', 'No file input!');
        }

        $meals = Excel::toArray(new MealsImport(), $request->file('csvfile'))[0];
        $dinner = $request->input('dinnerItems');
        $meals = array_map(function ($item) {
            return array_map('trim', $item);
        }, $meals);
        /*$meals = MenuMaker::createMenuFromInputs($meals);*/
        $dinner_arr_from_input = explode(",", $dinner);
        $dinner_arr_from_input = array_map('trim', $dinner_arr_from_input);

        $min_price = PHP_INT_MAX;
        $min_id = 'none';
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
        $minimumPrices = [];
        foreach ($sortedMealsByRestaurant as $restaurantId => $meals) {
            $minSum = null;
            foreach ($this->getAllPermutations($meals) as $row) {
                $items = [];
                foreach ($row as $meal) {
                    $items = array_merge($items, $meal['items']);
                }
                $items = array_unique($items);
                $currentSum = array_sum(array_column($row, '1'));
                $diff = array_diff($dinner_arr_from_input, $items);
                if (is_null($minSum) && empty($diff)) {
                    $minSum = array_sum(array_column($row, '1'));
                }

                if ($currentSum < $minSum && empty($diff)) {
                    $minSum = $currentSum;
                }
            }
            $minimumPrices[$restaurantId] = $minSum;
        }
        $foundedMinPrice = null;
        $foundedRestaurantId = null;
        foreach ($minimumPrices as $restaurantId => $minimumPrice) {
            if (is_null($foundedMinPrice) && is_null($foundedRestaurantId)) {
                $foundedMinPrice = $minimumPrice;
                $foundedRestaurantId = $restaurantId;
            }
            if ($minimumPrice < $foundedMinPrice && $minimumPrice) {
                $foundedMinPrice = $minimumPrice;
                $foundedRestaurantId = $restaurantId;
            }
        }

        return view('meals')
            ->with('min_price', $foundedMinPrice)
            ->with('min_id', $foundedRestaurantId);
    }
}
