<?php

namespace App\Http\Controllers;

use App\Models\VenueItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class VenueController extends Controller
{
    public function index()
    {
        $venueItems = collect();

        if (Schema::hasTable('venue_items')) {
            $venueItems = VenueItem::query()
                ->active()
                ->ordered()
                ->get();
        }

        $groupedVenue = $venueItems
            ->groupBy('section')
            ->map(fn (Collection $items) => $items->values());

        return view('venue', [
            'groupedVenue' => $groupedVenue,
            'venueItems' => $venueItems,
        ]);
    }
}
