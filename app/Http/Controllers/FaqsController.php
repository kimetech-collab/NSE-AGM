<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class FaqsController extends Controller
{
    public function index()
    {
        $faqItems = collect();

        if (Schema::hasTable('faq_items')) {
            $faqItems = FaqItem::query()
                ->active()
                ->ordered()
                ->get();
        }

        $groupedFaqs = $faqItems
            ->groupBy('category')
            ->map(fn (Collection $items) => $items->values())
            ->sortKeys();

        return view('faqs', [
            'groupedFaqs' => $groupedFaqs,
            'faqItems' => $faqItems,
        ]);
    }
}
