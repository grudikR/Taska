<?php

namespace App\Data;

class Meal
{
    private float $price;
    private array $items;
    private int $restourantlID;

    public function GetId(): int
    {
        return $this->restourantlID;
    }
    public function SetId($restourantlID)
    {
        $this->restourantlID = $restourantlID;
    }

    public function GetPrice(): float
    {
        return $this->price;
    }
    public function SetPrice($price)
    {
        $this->price = $price;
    }

    public function GetItems(): string
    {
        return implode(',', $this->items);
    }

    public function GetItemsArray()
    {
        $this->items = array_map('trim', $this->items);
        return $this->items;
    }

    public function SetItems($items)
    {
        $this->items = $items;
    }

    public function __construct($restourantlID, $price, $items)
    {
        $this->price = $price;
        $this->items = $items;
        $this->restourantlID = $restourantlID;
    }

    public function isContainsItem($dish): bool
    {
		return in_array(trim($dish), $this->items);
	}

    public function isContainsAllItems($dishes): bool
    {
		return !array_diff($dishes, $this->items);
	}

    public function addItemToMeal($s)
    {
        if (empty($this->items))
            $this->items = array();
        $this->items[] = $s;
    }
}
