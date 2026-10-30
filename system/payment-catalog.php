<?php

/**
 * Public Zealot Coins catalog.
 *
 * Prices are stored in the currency's major unit. The Checkout flow converts
 * them to the integer minor unit required by Stripe.
 */
return [
    'currency' => 'BRL',
    'coins_packages' => [
        'coins_25' => ['coins' => 25, 'bonus' => 0, 'price' => 1.25],
        'coins_100' => ['coins' => 100, 'bonus' => 0, 'price' => 5.00],
        'coins_250' => ['coins' => 250, 'bonus' => 0, 'price' => 12.50],
        'coins_500' => ['coins' => 500, 'bonus' => 0, 'price' => 25.00],
        'coins_750' => ['coins' => 750, 'bonus' => 0, 'price' => 37.50],
        'coins_1000' => ['coins' => 1000, 'bonus' => 0, 'price' => 50.00],
        'coins_1500' => ['coins' => 1500, 'bonus' => 0, 'price' => 75.00],
        'coins_2500' => ['coins' => 2500, 'bonus' => 0, 'price' => 125.00],
        'coins_5000' => ['coins' => 5000, 'bonus' => 0, 'price' => 250.00],
        'coins_10000' => ['coins' => 10000, 'bonus' => 0, 'price' => 500.00],
    ],
];
