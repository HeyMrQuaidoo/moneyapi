<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Client;
use App\Http\Models\Branch;
use App\Http\Models\Atm;
use App\Http\Models\Account;
use App\Http\Models\Types\Address;
use App\Http\Models\Types\Geocode;
use App\Http\Models\Types\Language;
use App\Http\Models\Deposit;
use App\Http\Models\Transfer;
use App\Http\Models\Withdrawal;
use App\Http\Models\Types\Transaction;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $faker = Faker::create();
        
        //Create account Types
        $account_types = array("Savings", "Checking", "Credit Card");
        
        for ($x = 0; $x < 3; $x++) {
            factory(App\Http\Models\Types\AccountType::class)->create([
                'type' => $account_types[$x],
            ]);
        }
        
        //Create account Languages
        $languages = array("English", "French", "Spanish");
        
        for ($x = 0; $x < 3; $x++) {
            $lang = factory(App\Http\Models\Types\Language::class)->create([
                'type' => $languages[$x],
            ]);
            
            //Create 3 atms per language and create associated geocode
            $atm = factory(App\Http\Models\Atm::class, 3)->make();
            $lang->atms()->saveMany($atm);
            $atm->each(function($u) use ($faker) {
                $u->geocode()->save(factory(App\Http\Models\Types\Geocode::class)->make());
                $u->save();
            });
            
            $lang->save();
        }
        
        //Create clients, address and accounts associated with client
        $client = factory(App\Http\Models\Client::class, 3)->create()->each(function($u) {
            $num = (int)rand(1, 3);
            $accounts = factory(App\Http\Models\Account::class, $num)->make();
            
            if($num == 1){
                $u->accounts()->save($accounts);
            }else{
                $u->accounts()->saveMany($accounts);
            }
            
            $u->address()->save(factory(App\Http\Models\Types\Address::class)->make());
            $u->save();
        });
        
        //Create branches with associated addresses
        factory(App\Http\Models\Branch::class, 20)->create()->each(function($u) {
            $u->address()->save(factory(App\Http\Models\Types\Address::class)->make());
            $u->save();
        });
        
        
        $transactionAccounts = Account::all();
        $c = count($transactionAccounts);
        for ($a = 0; $a < 3; $a++) {
            $transactionAccounts->map(function ($item, $key)  use ($a, $c){
                
                $transactionItem;
                $num = (int)rand(2, 7);
                $aux = Account::where('id', '!=' , $item->id)->orWhereNull('id')->get();
                
                switch($a){
                    case 0:
                        for ($x = 0; $x < $num; $x++) {
                            $transactionItem = factory(App\Http\Models\Deposit::class)->create([
                                'payee_id' => $item->id,
                            ]);
                            $transactionItem->transaction()->save(factory(App\Http\Models\Types\Transaction::class)->make());
                        }
                    break;
                    
                    case 1:
                        for ($x = 0; $x < $num; $x++) {
                            $transactionItem = factory(App\Http\Models\Withdrawal::class)->create([
                                'payer_id' => $item->id,
                            ]);
                            $transactionItem->transaction()->save(factory(App\Http\Models\Types\Transaction::class)->make());
                        }
                    break;
                    
                    case 2:
                        for ($x = 0; $x < $num; $x++) {
                            $b = (int)rand(1, $c - 2);
                            $transactionItem = factory(App\Http\Models\Transfer::class)->create([
                                'payer_id' => $item->id,
                                'payee_id' => $aux[$b]->id,
                            ]);
                            $transactionItem->transaction()->save(factory(App\Http\Models\Types\Transaction::class)->make());
                        }
                    break;
                }
            });
        }
            
        Model::reguard();
       
       $this->call('OAuthClientsSeeder');
       //$this->call('OAuthUsersSeeder');
       
    }
}
