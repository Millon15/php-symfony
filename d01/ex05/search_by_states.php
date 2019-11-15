<?php

function search_by_states(string $states_or_capitals): void
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

    $capitals_flipped = array_flip($capitals);
    $states_flipped = array_flip($states);

    foreach (explode(',', $states_or_capitals) as $state_or_capital) {
        $state_or_capital = trim($state_or_capital);

        if ($capital = $capitals[$states[$state_or_capital]] ?? null) {
            echo "$capital is the capital of $state_or_capital." . PHP_EOL;
        } elseif ($state = $states_flipped[$capitals_flipped[$state_or_capital]] ?? null) {
            echo "$state_or_capital is the capital of $state." . PHP_EOL;
        } else {
            echo "$state_or_capital is neither a capital nor a state." . PHP_EOL;
        }
    }
}

// search_by_states("Oregon, trenton, Topeka, NewJersey");
