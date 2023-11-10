<?php
namespace Database\Seeders;

use Modules\School\Database\Seeders\SchoolCoreDatabaseSeeder;
use Modules\Reporting\Database\Seeders\CategoryDatabaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Class CoreTableSeeder.
 */
class CoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Reset cached roles and cores
        app()['cache']->forget('spatie.core.cache');

        $this->call(CategoryDatabaseSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
