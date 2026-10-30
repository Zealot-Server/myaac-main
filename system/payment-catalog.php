<?php

/**
 * Public Zealot Coins catalog.
 *
 * Prices are stored in the currency's major unit. The Checkout flow converts
 * them to the integer minor unit required by Stripe.
 */
return [
  'currency' => 'USD',
  'coins_packages' => [
    // Stripe requires card payments in USD to be at least USD 0.50.
    'coins_25' => ['coins' => 25, 'bonus' => 0, 'price' => 0.5],
    'coins_100' => ['coins' => 100, 'bonus' => 0, 'price' => 0.92],
    'coins_250' => ['coins' => 250, 'bonus' => 0, 'price' => 2.3],
    'coins_500' => ['coins' => 500, 'bonus' => 0, 'price' => 4.6],
    'coins_750' => ['coins' => 750, 'bonus' => 0, 'price' => 6.9],
    'coins_1000' => ['coins' => 1000, 'bonus' => 0, 'price' => 9.2],
    'coins_1500' => ['coins' => 1500, 'bonus' => 0, 'price' => 13.8],
    'coins_2500' => ['coins' => 2500, 'bonus' => 0, 'price' => 23.0],
    'coins_5000' => ['coins' => 5000, 'bonus' => 0, 'price' => 46.0],
    'coins_10000' => ['coins' => 10000, 'bonus' => 0, 'price' => 92.0],
  ],
];
