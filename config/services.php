<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Third Party Services
  |--------------------------------------------------------------------------
  |
  | This file is for storing the credentials for third party services such
  | as Stripe, Mailgun, SparkPost and others. This file provides a sane
  | default location for this type of information, allowing packages
  | to have a conventional place to find your various credentials.
  |
  */

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
  ],

  'ses' => [
    'key' => env('SES_KEY'),
    'secret' => env('SES_SECRET'),
    'region' => 'us-east-1',
  ],

  'sparkpost' => [
    'secret' => env('SPARKPOST_SECRET'),
  ],

  'stripe' => [
    'model' => App\User::class,
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
  ],

  'facebook' => [
    'client_id' => env('FACEBOOK_APP_ID'),
    'client_secret' => env('FACEBOOK_APP_SECRET'),
    'redirect' => env('FACEBOOK_APP_CALLBACK_URL'),
  ],

  'github' => [
    'client_id' => env('GITHUB_APP_ID'),
    'client_secret' => env('GITHUB_APP_SECRET'),
    'redirect' => env('GITHUB_APP_CALLBACK_URL'),
  ],

  'twitter' => [
    'client_id' => env('TWITTER_APP_ID'),
    'client_secret' => env('TWITTER_APP_SECRET'),
    'redirect' => env('TWITTER_APP_CALLBACK_URL'),
  ],

  'google' => [
    'client_id' => env('GOOGLE_APP_ID'),
    'client_secret' => env('GOOGLE_APP_SECRET'),
    'redirect' => env('GOOGLE_APP_CALLBACK_URL'),
  ]

];
