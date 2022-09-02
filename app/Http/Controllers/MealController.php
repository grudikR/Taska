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
     * @return \Illuminate\Http\RedirectResponse
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
        $meals = MenuMaker::removeSpaces($meals);

        $dinner_arr_from_input = array_map('trim', explode(",", $dinner));

        $minimumPrices = MenuMaker::getMinimumPricesArray(MenuMaker::menuSortArray($meals), $dinner_arr_from_input);
        $foundedRes = MenuMaker::searchBestPriceFromPossible($minimumPrices);

        return view('meals')
            ->with('min_price', $foundedRes[0])
            ->with('min_id', $foundedRes[1]);
    }
}
