<?php

namespace Database\Seeders;

use App\Models\Category;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    public function run(): void {
        try {
            DB::beginTransaction();
            $categories = ["Alat Olahraga", "Alat Musik"];
            foreach ($categories as $item) {
                $category = new Category();
                $category->name = $item;
                $save = $category->save();

                if (!$save) throw new Exception("Cannot save category data!");
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error("[".get_class($this)."] Error: ".$e->getMessage()."");
        }
    }
}
