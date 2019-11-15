<?php

function capital_city_from(string $city): string
{
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

    return ($capitals[$states[$city]] ?? 'Unknown') . PHP_EOL;
}

// echo capital_city_from('Oregon');
// echo capital_city_from('Origan');
