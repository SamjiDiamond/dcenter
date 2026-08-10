<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Settings::find(1)) {
            return;
        }

        $settings = new Settings();
        $settings->id = 1;
        $settings->funding_fee = 80.00;
        $settings->save();

        $this->command->info('Settings row seeded (funding_fee = 80.00).');
    }
}
