<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use function setting;

class Settings extends Component
{
    public ?string $email = '';

    public ?string $phone = '';

    public ?string $facebook = '';

    public ?string $info = '';

    public ?string $location = '';

    public ?string $google_map = '';

    public ?string $organizer_url = '';

    public ?string $organizer_image = '';

    public function mount(): void
    {
        $this->email = setting('dhtt_email', '');
        $this->phone = setting('dhtt_phone', '');
        $this->facebook = setting('dhtt_facebook', '');
        $this->info = setting('dhtt_info', '');
        $this->location = setting('dhtt_location', '');
        $this->google_map = setting('dhtt_google_map', '');
        $this->organizer_url = setting('dhtt_organizer_url', '');
        $this->organizer_image = setting('dhtt_organizer_image', '');
    }

    public function save(): void
    {
        $this->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'facebook' => 'nullable|string',
            'info' => 'nullable|string',
            'location' => 'nullable|string',
            'organizer_url' => 'nullable|url',
            'organizer_image' => 'nullable|url',
            'google_map' => [
                'required',
                'regex:/^(https:\/\/www\.google\.com\/maps\/embed\?.*pb=.+|https:\/\/maps\.google\.com\/maps\?.*output=embed.*)$/',
            ],
        ]);

        foreach ($this->fields() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \App\Facades\Settings::clearCache();

        flash('Beállítások sikeresen frissítve!')->success();
    }

    public function fields(): array
    {
        return [
            'dhtt_email' => $this->email,
            'dhtt_phone' => $this->phone,
            'dhtt_facebook' => $this->facebook,
            'dhtt_info' => $this->info,
            'dhtt_location' => $this->location,
            'dhtt_google_map' => $this->google_map,
            'dhtt_organizer_url' => $this->organizer_url,
            'dhtt_organizer_image' => $this->organizer_image,
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.settings');
    }
}
