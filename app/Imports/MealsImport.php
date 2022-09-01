<?php

namespace App\Imports;

use App\Models\Meal;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;

class MealsImport implements ToArray, ToCollection
{
    public function array(array $array)
    {
        return $array;
    }

    public function collection(Collection $collection)
    {
        return $collection;
    }
}
