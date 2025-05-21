<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Component
{
    use WithFileUploads;

    #[Reactive]
    public int|string|null $eventId = null;

    #[Locked]
    public ?Event $event = null;

    #[Locked]
    public ?MediaCollection $images;

    #[Locked]
    public array $order = [];

    public $newImages = [];

    private function getEventId()
    {
        return $this->event?->getKey() ?? null;
    }

    public function mount(): void
    {
        if (is_numeric($this->eventId) && ! $this->event) {
            $this->event = Event::find($this->eventId);
        }
        $this->loadImages();
    }

    public function loadImages(bool $refresh = false): void
    {
        if ($this->event) {
            if ($refresh) {
                $this->event->refresh();
            }

            $event = &$this->event;
            $mediaItems = $event->getMedia('gallery');
            $this->images = $mediaItems->values();
            $this->order = $this->images->pluck('id')->toArray();
        }
    }

    public function addImages(): void
    {
        if (is_null($this->event)) {
            return;
        }

        $this->validate([
            'newImages.*' => 'required|image|max:204800',
        ]);

        if (is_array($this->newImages) && count($this->newImages)) {
            $countUploaded = 0;
            foreach ($this->newImages as $index => &$image) {
                $this->event->addMedia($image->getRealPath())
                    ->usingFileName($image->getClientOriginalName())
                    ->toMediaCollection('gallery', 'dhtt_gallery');
                $image->delete();
                unset($this->newImages[$index]);
                $countUploaded++;
            }

            add_user_log([
                'title' => 'Galéria esemény #'.$this->getEventId(),
                'link' => route('admin.dhtt.index', ['tab' => 'gallery', 'event' => $this->getEventId()]),
                'reference_id' => auth()->id(),
                'section' => 'Galéria',
                'type' => 'Képek felöltése '.$countUploaded.' db',
            ]);
            flash('Sikeres feltöltés!')->success();
        } else {
            $this->addError('newImages.*', 'Nem töltöttél fel képet!');
        }

        $this->loadImages(true);
    }

    public function deleteImage($mediaId): void
    {
        if (! $this->event) {
            return;
        }

        $media = $this->event->getMedia('gallery')->firstWhere('id', $mediaId);
        if ($media) {
            $media->delete();

            add_user_log([
                'title' => 'Galéria esemény #'.$this->getEventId(),
                'link' => route('admin.dhtt.index', ['tab' => 'gallery', 'event' => $this->getEventId()]),
                'reference_id' => auth()->id(),
                'section' => 'Galéria',
                'type' => 'Kép törlés #'.$mediaId,
            ]);

            flash('A kép sikeresen törölve!')->success();
        }

        $this->loadImages(true);
    }

    private function updateOrder(array $order): void
    {
        Media::setNewOrder($order);
        $this->loadImages(true);
        flash('Sorrend sikeresen frissítve!')->success();
    }

    public function moveUp($mediaId): void
    {
        $order = &$this->order;
        $index = array_search($mediaId, $order);
        if ($index !== false && $index > 0) {
            $temp = $order[$index - 1];
            $order[$index - 1] = $order[$index];
            $order[$index] = $temp;
            $this->updateOrder($order);
        }
    }

    public function moveDown($mediaId): void
    {
        $order = &$this->order;
        $index = array_search($mediaId, $order);
        if ($index !== false && $index < count($order) - 1) {
            $temp = $order[$index + 1];
            $order[$index + 1] = $order[$index];
            $order[$index] = $temp;
            $this->updateOrder($order);
        }
    }

    public function removeImage($index): void
    {
        if (isset($this->newImages[$index])) {
            $this->newImages[$index]->delete();
            array_splice($this->newImages, $index, 1);
        }
    }

    public function updatingNewImages($files): void
    {
        array_walk($this->newImages, function ($item) {
            if ($item instanceof TemporaryUploadedFile) {
                $item->delete();
            }
        });
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.gallery');
    }
}
