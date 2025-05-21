<?php

namespace Database\Seeders\Dhtt;

use App\Models\Setting;
use App\Services\SettingsManager;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'allow_registration'], ['value' => '0']);

        Setting::updateOrCreate(['key' => 'dhtt_email'], ['value' => 'dahunor@yahoo.com']);
        Setting::updateOrCreate(['key' => 'dhtt_phone'], ['value' => '0744-590775 (Daday Hunor)']);
        Setting::updateOrCreate(['key' => 'dhtt_facebook'], ['value' => '<a href="https://www.facebook.com/profile.php?id=61564229181928" class="facebook">#eke.csikszek</a>']);
        Setting::updateOrCreate(['key' => 'dhtt_info'], ['value' => 'A teljesítménytúrával kapcsolatos egyéb információkat az alábbi elérhetőségeken keresztül lehet igényelni:']);
        Setting::updateOrCreate(['key' => 'dhtt_location'], ['value' => 'Zsögödfürdő, Csíkszereda']);
        Setting::updateOrCreate(['key' => 'dhtt_google_map'], ['value' => 'https://maps.google.com/maps?q=strand%20jigodin&t=&z=13&ie=UTF8&iwloc=&output=embed']);
        Setting::updateOrCreate(['key' => 'dhtt_organizer_image'], ['value' => url('/img/cseke-logo.png')]);
        Setting::updateOrCreate(['key' => 'dhtt_organizer_url'], ['value' => url('/')]);

        SettingsManager::clearCache();
    }
}
