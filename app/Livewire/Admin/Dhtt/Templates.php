<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Template;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Templates extends Component
{
    public string $title = '';

    public bool $isActive = true;

    public string $type = '';

    public string $content = '';

    #[Locked]
    public ?int $editing = null;

    #[Locked]
    public bool $formOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isActive' => 'required|boolean',
            'type' => 'required|in:'.implode(',', array_keys($this->templateTypes())),
            'content' => 'required|string',
        ];
    }

    private function templateTypes(): array
    {
        return [
            'description' => 'Leírás',
            'rules' => 'Szabályzat',
            'invitation' => 'Meghívó',
            'terms' => 'Részvételi feltételek',
        ];
    }

    #[Computed]
    public function templates(): Collection
    {
        return Template::orderBy('title')->get();
    }

    public function edit($id): void
    {
        $this->cancel();
        $template = Template::findOrFail($id);
        $this->title = $template->title;
        $this->type = $template->type;
        $this->editing = $template->id;
        $this->content = $template->content;
        $this->isActive = (bool) $template->is_active;
        $this->formOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = $this->only('content', 'title', 'type');
        $data['is_active'] = $this->isActive;

        if ($this->editing) {
            $template = Template::findOrFail($this->editing);
            $template->update($data);
            flash('Sablon frissítve!')->success();
        } else {
            $template = new Template;
            $template->fill($data);
            $template->save();
            $this->cancel();
            flash('Sablon sikeresen létrehozva!')->success();
        }
    }

    public function create(): void
    {
        $this->cancel();
        $this->formOpen = true;
    }

    public function cancel(): void
    {
        $this->resetValidation();
        $this->reset(['editing', 'formOpen', 'content', 'isActive', 'title', 'type']);
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.templates');
    }
}
