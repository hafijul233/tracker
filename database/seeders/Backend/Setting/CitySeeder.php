<?php

namespace Database\Seeders\Backend\Setting;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        Model::unguard();
        DB::disableQueryLog();
        DB::table('cities')->truncate();
        DB::unprepared(file_get_contents(__DIR__ . '/cities/019.sql'));
        DB::enableQueryLog();
/*        $countryIdArray = Country::all()->pluck('id')->toArray();
        foreach ($countryIdArray as $countryId) {
            $filename = __DIR__ . DIRECTORY_SEPARATOR . "cities" . DIRECTORY_SEPARATOR . str_pad($countryId, 3, "0", STR_PAD_LEFT) . '.sql';
            try {
                if (file_exists($filename)) {
                    $sql = file_get_contents($filename);
                    if (strlen($sql) > 0) {
                        DB::unprepared($sql);
                        sleep(1);
                        $this->command->info("Time : " . date("H:i:s") . " Country : " . $countryId);
                    }
                } else
                    throw new FileNotFoundException("File Not Found. Location: " . $filename);
            } catch (\PDOException $exception) {
                throw new \PDOException($exception->getMessage());
            }
        }*/
    }
}