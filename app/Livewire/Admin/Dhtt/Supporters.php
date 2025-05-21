<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Supporter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Supporters extends Component
{
    use WithFileUploads, WithPagination;

    #[Locked]
    public ?int $supporterId = null;

    #[Locked]
    public bool $modalOpen = false;

    public string $name = '';

    public string $image_url = '';

    public string $link = '';

    public bool $is_local = false;

    public ?TemporaryUploadedFile $newImage = null;

    protected array $rules = [
        'supporterId' => 'numeric|nullable',
        'name' => 'required|string|max:255',
        'image_url' => 'nullable|string|max:255',
        'link' => 'nullable|url',
        'newImage' => 'nullable|image|max:10485760',
    ];

    public function create(): void
    {
        $this->cancel();
        $this->modalOpen = true;
    }

    public function cancel(): void
    {
        $this->resetFields();
        $this->resetValidation();
        $this->modalOpen = false;
    }

    public function edit($id): void
    {
        $this->resetFields();
        $supporter = Supporter::find($id);

        if (! $supporter) {
            $this->alertJs('Támogató nem létezik!');

            return;
        }

        $this->supporterId = $supporter->id;
        $this->name = $supporter->name;
        $this->image_url = $supporter->image_url;
        $this->link = $supporter->link;
        $this->is_local = (bool) $supporter->is_local;
        $this->modalOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        $is_local = false;
        if ($this->newImage instanceof TemporaryUploadedFile && $this->newImage->exists()) {
            $imagePath = $this->newImage->store('supporters', 'public');
            $this->newImage->delete();
            $is_local = true;
        } else {
            $imagePath = $this->image_url;
        }

        $data = [
            'name' => $this->name,
            'image_url' => $imagePath,
            'is_local' => $is_local,
            'link' => $this->link,
        ];

        if (! is_null($this->supporterId)) {
            $supporter = Supporter::find($this->supporterId);

            if (! $supporter) {
                $this->addError('name', 'Támogató nem létezik');

                return;
            }

            if ($imagePath == $supporter->image_url) {
                $data['is_local'] = $supporter->is_local;
            }

            if ($supporter->is_local && storage_exists($supporter->image_url)) {
                Storage::disk('public')->delete($supporter->image_url);
            }
            $supporter->update($data);
        } else {
            Supporter::create($data);
        }

        flash('Támogató sikeresen '.($this->supporterId ? 'szerkesztve' : 'létrehozva'))->success();
        $this->modalOpen = false;
        $this->resetFields();
    }

    public function delete(string|int $id): void
    {
        $supporter = Supporter::find($id);

        if (! $supporter) {
            $this->alertJs('Támogató nem létezik!');

            return;
        }

        if ($supporter->is_local) {
            Storage::disk('public')->delete($supporter->image_url);
        }
        $supporter->delete();
        flash('Támogató sikeresen törölve!')->success();
    }

    private function resetFields(): void
    {
        $this->cleanupOldUploads();
        $this->supporterId = null;
        $this->name = '';
        $this->image_url = '';
        $this->link = '';
        $this->is_local = false;
        $this->newImage = null;
    }

    private function alertJs(string $message): void
    {
        $this->js('alert(\''.e($message).'\');');
    }

    public function render(): View
    {
        $supporters = Supporter::orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.dhtt.supporters', compact('supporters'));
    }
}
