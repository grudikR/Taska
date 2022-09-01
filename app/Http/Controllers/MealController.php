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

    /**
     * Import User data through sheet
     *
     * @return void
     */
    public function import(Request $request)
    {
        $meals = Excel::toArray(new MealsImport(), $request->file('csvfile'))[0];
        $dinner = $request->input('dinnerItems');

        $meals = MenuMaker::createMenuFromInputs($meals);
        $dinner_arr = explode(",", $dinner);
        $dinner_arr = array_map('trim', $dinner_arr);
        /*
                $ttt = collect();
                foreach ($meals as $it) {
                    $tmp_ob = new Meal($it[0], $it[1], explode(",", trim($it[2])));
                    $ttt->push($tmp_ob);
                }
                $meals = $ttt;
        */
        $min_price = PHP_INT_MAX;
        $min_id = 'none';

        $list_of_restourant = array_keys($meals);
        foreach ($list_of_restourant as $restourant_id) {
            $a = array_fill_keys($dinner_arr, PHP_INT_MAX);
            $one_restourant = $meals[$restourant_id];
            var_dump($dinner_arr);
            var_dump($a);
            echo "<br/>";
            foreach ($one_restourant as $restourant_item) {
                foreach ($restourant_item->GetItemsArray() as $one_restourant_pos) {
                    echo "<br/>";
                    var_dump($one_restourant_pos);
                    if (array_key_exists($one_restourant_pos, $a))
                        echo "YES";
                    else
                        echo "NO";

                    echo "<br/>";

                    if (array_key_exists($one_restourant_pos, $a) && $restourant_item->GetPrice() < $a[$one_restourant_pos])
                    {
                        $a[$one_restourant_pos] = $restourant_item->GetPrice();
                    }
                }

                if (count(array_keys($a, PHP_INT_MAX)) == 0)
                {
                    $suma = array_sum($a);

                    if ($suma < $min_price)
                    {
                        $min_price = $suma;
                        $min_id = $restourant_item->GetId();
                    }
                }
            }
        }

        return view('meals')
            ->with('min_price', $min_price)
            ->with('min_id', $min_id);
    }
}
