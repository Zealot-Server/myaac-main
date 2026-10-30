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
    // The USD 0.50 minimum matches Stripe's card-payment floor.
    // Keep every package at USD 0.01 per Zealot Coin.
    'coins_50' => ['coins' => 50, 'bonus' => 0, 'price' => 0.5],
    'coins_100' => ['coins' => 100, 'bonus' => 0, 'price' => 1.0],
    'coins_250' => ['coins' => 250, 'bonus' => 0, 'price' => 2.5],
    'coins_500' => ['coins' => 500, 'bonus' => 0, 'price' => 5.0],
    'coins_750' => ['coins' => 750, 'bonus' => 0, 'price' => 7.5],
    'coins_1000' => ['coins' => 1000, 'bonus' => 0, 'price' => 10.0],
    'coins_1500' => ['coins' => 1500, 'bonus' => 0, 'price' => 15.0],
    'coins_2500' => ['coins' => 2500, 'bonus' => 0, 'price' => 25.0],
    'coins_5000' => ['coins' => 5000, 'bonus' => 0, 'price' => 50.0],
    'coins_10000' => ['coins' => 10000, 'bonus' => 0, 'price' => 100.0],
  ],
];
