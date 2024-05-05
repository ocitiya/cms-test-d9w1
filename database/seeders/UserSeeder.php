<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {
    public function run(): void {
        try {
            DB::beginTransaction();

            $user = new User();
            $user->email = "muhammadrasyidi17@gmail.com";
            $user->password = "12345678";
            $save = $user->save();

            if (!$save) throw new Exception("Cannot save user data!");
    
            $account = new Account();
            $account->user_id = $user->id;
            $account->name = "Muhammad Rasyidi";
            $account->position = "Web Programmer";
            $save = $account->save();

            if (!$save) throw new Exception("Cannot save account data!");

            DB::commit();
        } catch (Exception $e) {
            $this->command->error("[".get_class($this)."] Error: ".$e->getMessage()."");
            DB::rollBack();
        }
    }
}
