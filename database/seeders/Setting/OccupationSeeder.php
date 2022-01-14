<?php

namespace Database\Seeders\Setting;

use App\Models\Backend\Setting\Occupation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class OccupationTableSeeder
 * @package Database\Seeders\Seeting;
 */
class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $occupations = [
            ['name' => 'Accountant', 'remarks' => 'accountant', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:10:17', 'deleted_at' => NULL],
            ['name' => 'Agent', 'remarks' => 'agent', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:10:38', 'deleted_at' => NULL],
            ['name' => 'Athlete', 'remarks' => 'athlete', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:10:52', 'deleted_at' => NULL],
            ['name' => 'Auditor', 'remarks' => 'auditor', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:00', 'deleted_at' => NULL],
            ['name' => 'Bank officer', 'remarks' => 'bank officer', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2021-12-16 23:35:28', 'deleted_at' => NULL],
            ['name' => 'Beautician', 'remarks' => 'beautician', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:13', 'deleted_at' => NULL],
            ['name' => 'Bell boy', 'remarks' => 'bell boy', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:24', 'deleted_at' => NULL],
            ['name' => 'Business visa', 'remarks' => 'business visa', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Businessman business woman', 'remarks' => 'businessman business woman', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:41', 'deleted_at' => NULL],
            ['name' => 'Caddy', 'remarks' => 'caddy', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:50', 'deleted_at' => NULL],
            ['name' => 'Cargo freight assistant', 'remarks' => 'cargo freight assistant', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:11:59', 'deleted_at' => NULL],
            ['name' => 'Cashier', 'remarks' => 'cashier', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:12:09', 'deleted_at' => NULL],
            ['name' => 'Chef', 'remarks' => 'chef', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:12:19', 'deleted_at' => NULL],
            ['name' => 'Civil servant', 'remarks' => 'civil servant', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Cleaner', 'remarks' => 'cleaner', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Clerk', 'remarks' => 'clerk', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:12:35', 'deleted_at' => NULL],
            ['name' => 'Coach', 'remarks' => 'coach', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:13:01', 'deleted_at' => NULL],
            ['name' => 'Construction worker', 'remarks' => 'construction worker', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Consultant', 'remarks' => 'consultant', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Cook', 'remarks' => 'cook', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:13:13', 'deleted_at' => NULL],
            ['name' => 'Doctor', 'remarks' => 'doctor', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2021-12-16 23:35:25', 'deleted_at' => NULL],
            ['name' => 'Driver', 'remarks' => 'driver', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Engineer', 'remarks' => 'engineer', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Entertainer', 'remarks' => 'entertainer', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-11-23 12:13:28', 'deleted_at' => NULL],
            ['name' => 'Factory worker', 'remarks' => 'factory worker', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
            ['name' => 'Garden worker', 'remarks' => 'garden worker', 'additional_info' => '{}', 'enabled' => 'yes', 'created_at' => '2020-08-28 21:48:50', 'updated_at' => '2020-08-28 21:48:50', 'deleted_at' => NULL],
        ];
        foreach ($occupations as $occupation) {
            try {
                Occupation::create($occupation);
            } catch (\PDOException $exception) {
                throw new \PDOException($exception->getMessage());
            }
        }
    }
}
