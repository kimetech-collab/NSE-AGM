<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class FaqItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['category' => 'Registration', 'question' => 'Do I need to be an NSE member to attend?', 'answer' => 'No. The conference is open to members and non-members.', 'sort_order' => 10, 'is_active' => true],
            ['category' => 'Registration', 'question' => 'Can students attend?', 'answer' => 'Yes. Student participation is welcome and discounted where applicable.', 'sort_order' => 20, 'is_active' => true],
            ['category' => 'Payment', 'question' => 'Can I register now and pay later?', 'answer' => 'Yes. You can register first and complete payment before registration closes.', 'sort_order' => 10, 'is_active' => true],
            ['category' => 'Payment', 'question' => 'What payment methods are supported?', 'answer' => 'Card, transfer, USSD, and other methods supported by Paystack.', 'sort_order' => 20, 'is_active' => true],
            ['category' => 'Attendance', 'question' => 'Is virtual attendance available?', 'answer' => 'Yes. Key sessions can be attended virtually.', 'sort_order' => 10, 'is_active' => true],
            ['category' => 'Support', 'question' => 'Who do I contact for support?', 'answer' => 'Contact conference@nse.org.ng for conference support.', 'sort_order' => 10, 'is_active' => true],
        ];

        foreach ($items as $item) {
            FaqItem::query()->updateOrCreate(
                ['category' => $item['category'], 'question' => $item['question']],
                $item
            );
        }
    }
}
