<?php

$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];
$capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka',
];

function capital_city_from(string $city): string
{
	global $states;
	global $capitals;

    return ($capitals[$states[$city]] ?? 'Unknown') . PHP_EOL;
}
