<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            [
                'code_unit' => 'Tender'
            ],[
                'code_unit' => 'Berita'
            ],[
                'agama' => 'Perusahaan'
            ]
        ];

        try {
            DB::table('unit')->insert($units);
        } catch (\Exception $exception){

        }

    }
}
