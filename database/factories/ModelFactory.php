<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

//Define address
$factory->define(App\Http\Models\Types\Address::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
        'street_name' => $faker->streetAddress,
        'street_number' => $faker->streetName,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => $faker->randomNumber(5),
    ];
});

//Define client sha1(preg_replace("/(.*?)@(.*)/", "$1", $username))
$factory->define(App\Http\Models\Client::class, function (Faker\Generator $faker) {
    $username = $faker->email;
    
    return [
        'id' => $faker->Uuid,
        'username' => $username,
        'password' => $faker->firstName,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

//Define account
$factory->define(App\Http\Models\Account::class, function (Faker\Generator $faker) {
    return [
        'id' =>  $faker->Uuid,
        'type' => $faker->randomElement(array("Savings", "Checking", "Credit Card")),
        'alias' => $faker->word,
        'balance' => $faker->randomFloat(),
        'account_number' => $faker->creditCardNumber,
    ];
});

//Define accountType
$factory->define(App\Http\Models\Types\AccountType::class, function (Faker\Generator $faker) {
    return [
        'id' =>  $faker->Uuid,
        'type' => $faker->randomElement(array("Savings", "Checking", "Credit Card")),
    ];
});

//Define languages
$factory->define(App\Http\Models\Types\Language::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
        'type' => $faker->randomElement(array("English", "French", "Spanish")),
    ];
});

//Define branch
$factory->define(App\Http\Models\Branch::class, function (Faker\Generator $faker) {
    return [
        'id' =>  $faker->Uuid,
        'name' => $faker->domainWord,
        'hours' => $faker->Uuid,
        'tel' => $faker->phoneNumber,
    ];
});

//Define Atm
$factory->define(App\Http\Models\Atm::class, function (Faker\Generator $faker) {
    return [
        'id' =>  $faker->Uuid,
        'alias' => $faker->domainWord,
        'atm_balance' => $faker->randomFloat(),
    ];
});

//Define Geocode
$factory->define(App\Http\Models\Types\Geocode::class, function (Faker\Generator $faker) {
    return [
        'id' =>  $faker->Uuid,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
    ];
});

//Define deposit
$factory->define(App\Http\Models\Deposit::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
    ];
});

//Define withdrawal
$factory->define(App\Http\Models\Withdrawal::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
    ];
});

//Define transfer
$factory->define(App\Http\Models\Transfer::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
    ];
});

//Define transaction
$factory->define(App\Http\Models\Types\Transaction::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->Uuid,
        'status' => $faker->word,
        'description' => $faker->word,
        'amount' => $faker->randomFloat(),
        'opening_balance' => $faker->randomFloat(),
        'closing_balance' => $faker->randomFloat(),
    ];
});