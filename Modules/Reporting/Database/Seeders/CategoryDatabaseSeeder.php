<?php

namespace Modules\Reporting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reporting\Entities\Type;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        Schema::disableForeignKeyConstraints();

        // Add the master administrator, user id of 1df
        $reportingcores = [
            [
                'code'                      => "bul",
                'name'                      => "Bullying",
                'description'               => "Bullying",
            ],
            [
                'code'                      => "tkr",
                'name'                      => "Tindak Kekerasan",
                'description'               => "Tindak Kekerasan",
            ],
            [
                'code'                      => "tps",
                'name'                      => "Tindak Pelecehan Seksual",
                'description'               => "Tindak Pelecehan Seksual",
            ],
            [
                'code'                      => "dll",
                'name'                      => "Lain-lain",
                'description'               => "Lain-lain",
            ],
        ];

        foreach ($reportingcores as $reportingcore_data) {
            $reportingcore = Type::firstOrCreate($reportingcore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
