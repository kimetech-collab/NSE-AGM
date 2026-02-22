<?php

namespace App\Http\Controllers;

use App\Models\ProgrammeItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmeItems = collect();

        if (Schema::hasTable('programme_items')) {
            $programmeItems = ProgrammeItem::query()
                ->active()
                ->ordered()
                ->get();
        }

        $groupedSchedule = $programmeItems
            ->groupBy('programme_date')
            ->map(function (Collection $items, string $date) {
                return [
                    'date' => $date,
                    'items' => $items->values(),
                ];
            })
            ->values();

        return view('programme', [
            'groupedSchedule' => $groupedSchedule,
            'programmeItems' => $programmeItems,
        ]);
    }
}
