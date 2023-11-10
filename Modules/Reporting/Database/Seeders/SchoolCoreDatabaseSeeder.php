<?php

namespace Modules\Reporting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reporting\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class ReportingCoreDatabaseSeeder extends Seeder
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
                'reporting_core_code'              => "major",
                'reporting_core_name'              => "Jurusan",
                'reporting_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'reporting_core_code'              => "recruitment_status",
                'reporting_core_name'              => "Status Rekrutan",
                'reporting_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'reporting_core_code'              => "skills",
                'reporting_core_name'              => "Status Rekrutan",
                'reporting_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'reporting_core_code'              => "certificate",
                'reporting_core_name'              => "Status Rekrutan",
                'reporting_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($reportingcores as $reportingcore_data) {
            $reportingcore = Core::firstOrCreate($reportingcore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
