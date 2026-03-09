<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = Setting::getDefaults();

        foreach ($defaults as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_bool($data['value']) ? ($data['value'] ? '1' : '0') : (string) $data['value'],
                    'type' => $data['type']
                ]
            );
        }

        $this->command->info('Default settings have been seeded.');
    }
}
