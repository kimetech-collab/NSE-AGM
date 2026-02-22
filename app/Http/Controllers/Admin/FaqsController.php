<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqItem;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FaqsController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('faq_items')) {
            return view('admin.faqs.index', ['faqItems' => collect()])
                ->with('error', 'FAQs table not found. Run migrations.');
        }

        return view('admin.faqs.index', [
            'faqItems' => FaqItem::query()->ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:120',
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $faq = FaqItem::create([
            'category' => $data['category'],
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('faq.created', 'FaqItem', $faq->id, $faq->toArray());
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(FaqItem $faqItem)
    {
        return view('admin.faqs.edit', compact('faqItem'));
    }

    public function update(Request $request, FaqItem $faqItem)
    {
        $data = $request->validate([
            'category' => 'required|string|max:120',
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $before = $faqItem->toArray();

        $faqItem->update([
            'category' => $data['category'],
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('faq.updated', 'FaqItem', $faqItem->id, ['question' => $faqItem->question], $before, $faqItem->fresh()->toArray());
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(FaqItem $faqItem)
    {
        $before = $faqItem->toArray();
        $id = $faqItem->id;
        $faqItem->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('faq.deleted', 'FaqItem', $id, $before);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
