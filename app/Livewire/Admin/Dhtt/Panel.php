<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;

class Panel extends Component
{
    #[Url(as: 'tab')]
    public string $activeTab = 'events';

    #[Locked]
    public array $tabs = [
        'events' => [
            'component' => 'admin.dhtt.events.events-list',
            'title' => 'Események',
            'icon' => 'calendar',
        ],
        'hikes' => [
            'component' => 'admin.dhtt.hikes.hikes-list',
            'title' => 'Túrák',
            'icon' => 'hiking',
        ],
        'participants' => [
            'component' => 'admin.dhtt.participants.participants-list',
            'title' => 'Jelentkezők',
            'icon' => 'users',
        ],
        'gallery' => [
            'component' => 'admin.dhtt.gallery',
            'title' => 'Galéria',
            'icon' => 'images',
        ],
        'statistics' => [
            'component' => 'admin.dhtt.statistics',
            'title' => 'Statisztika',
            'icon' => 'chart-line',
        ],
        'supporters' => [
            'component' => 'admin.dhtt.supporters',
            'title' => 'Támogatók',
            'icon' => 'handshake',
        ],
        'templates' => [
            'component' => 'admin.dhtt.templates',
            'title' => 'Sablonok',
            'icon' => 'file-code',
        ],
        'settings' => [
            'component' => 'admin.dhtt.settings',
            'title' => 'Beállítások',
            'icon' => 'tools',
        ],
    ];

    #[Url(as: 'event')]
    #[Session(key: 'selectedEvent')]
    public string|int|null $eventId = '';

    public function mount(): void
    {
        $this->setTab($this->activeTab);

        if (! is_numeric($this->eventId) && $this->eventId !== 'all') {
            $event = Event::where('active', 1)->first()
                ?? Event::where('active', 0)->orderByDesc('id')->first();
        } else {
            $event = Event::find($this->eventId);
        }

        $this->eventId = $event->id ?? null;
    }

    public function updatedEventId(mixed $value): void
    {
        if ($value != 'all' && ! is_numeric($value)) {
            $this->reset('eventId');
        }

        if (! $this->eventId) {
            session()->forget('selectedEvent');
        }
    }

    public function updatedActiveTab(string $tab): void
    {
        $this->setTab($tab);
    }

    public function setTab(string $tab): void
    {
        if (array_key_exists($tab, $this->tabs)) {
            $this->activeTab = $tab;
        } else {
            $this->reset('activeTab');
        }
        /* wire:navigate
        $parameters = ['tab' => $tab];
        if ($this->eventId) {
            $parameters['eventId'] = $this->eventId;
        }
        $this->redirectRoute('admin.dhtt.dhtt.index', $parameters, navigate: true);
        */
    }

    private function getActiveTab(): array
    {
        $names = array_keys($this->tabs);
        $activeTab = $this->activeTab;
        if (! in_array($activeTab, $names)) {
            $activeTab = $names[0];
        }

        return $this->tabs[$activeTab] ?? [];
    }

    public function render(): View
    {
        $tab = $this->getActiveTab();
        $title = $tab['title'];
        $tabComponent = $tab['component'];

        return view('livewire.admin.dhtt.panel', ['tabComponent' => $tabComponent])->title($title);
    }
}
