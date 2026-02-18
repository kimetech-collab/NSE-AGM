<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingItem;
use App\Models\PricingVersion;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $versions = PricingVersion::with('items')->orderBy('created_at', 'desc')->get();
        return view('admin.pricing.index', ['versions' => $versions]);
    }

    public function storeVersion(Request $request)
    {
        $data = $request->validate([
            'version_name' => 'required|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        PricingVersion::create($data);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing version created.');
    }

    public function updateVersion(Request $request, PricingVersion $version)
    {
        $data = $request->validate([
            'version_name' => 'required|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $version->update($data);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing version updated.');
    }

    public function deleteVersion(PricingVersion $version)
    {
        $version->items()->delete();
        $version->delete();

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing version deleted.');
    }

    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'pricing_version_id' => 'required|exists:pricing_versions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price_cents' => 'required|integer|min:0',
            'currency' => 'required|string|max:8',
        ]);

        PricingItem::create($data);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing item created.');
    }

    public function updateItem(Request $request, PricingItem $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price_cents' => 'required|integer|min:0',
            'currency' => 'required|string|max:8',
        ]);

        $item->update($data);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing item updated.');
    }

    public function deleteItem(PricingItem $item)
    {
        $item->delete();

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing item deleted.');
    }
}
