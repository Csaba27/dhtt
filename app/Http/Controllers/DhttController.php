<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Event;
use App\Models\Supporter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DhttController extends Controller
{
    /**
     * Lekéri az aktuális vagy legutóbbi eseményt.
     */
    private function getEvent(): ?Event
    {
        return Event::where(['active' => 1, 'show' => 1])->first()
            ?? Event::where(['active' => 0, 'show' => 1])
                ->orderByDesc('id')
                ->first();
    }

    /**
     * A főoldal megjelenítése.
     */
    public function index(): View
    {
        $event = $this->getEvent();
        $hikes = $event ? $event->hikes()->orderBy('name')->get() : [];
        $supporters = Supporter::all();

        return view('dhtt.home', compact('hikes', 'event', 'supporters'));
    }

    /**
     * A regisztrációs oldal megjelenítése.
     */
    public function registration(): View
    {
        $event = $this->getEvent();
        $status = $event ? $event->status : 4;
        $associations = $event ? Association::all() : [];

        return view('dhtt.registration', compact('event', 'status', 'associations'));
    }

    /**
     * Az esemény oldal megjelenítése.
     */
    public function event(Request $request): View|RedirectResponse
    {
        $option = $request->get('option', 'detail');

        if (! in_array($option, ['detail', 'routes', 'results', 'gallery'])) {
            abort(404);
        }

        $event = $this->getEvent();

        if (! $event) {
            return redirect()->route('dhtt.archive');
        }

        return view('dhtt.event', compact('event', 'option'));
    }

    /**
     * Az archívum oldal megjelenítése.
     */
    public function archive(Request $request, ?Event $event = null): View
    {
        $option = $request->get('option', 'detail');

        if (! in_array($option, ['detail', 'routes', 'results', 'gallery'])) {
            abort(404);
        }

        $where = function (Builder $query) {
            $query->where('date', '<=', now()->format('Y-m-d'))
                ->where('active', 0)
                ->where('status', 4)
                ->where('show', 1);
        };

        $events = Event::where($where)->get();

        if ($events->isEmpty()) {
            abort(404, 'Nem találhatóak események az archívumba!');
        }

        if (! $event) {
            $event = $events->first();
        }

        return view('dhtt.event', compact('events', 'event', 'option'));
    }
}
