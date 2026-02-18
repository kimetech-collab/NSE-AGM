<p>Hello {{ $registration->name }},</p>
<p>Your refund request has been initiated.</p>
<p><strong>Reference:</strong> {{ $transaction->provider_reference }}</p>
<p><strong>Amount:</strong> NGN {{ number_format($transaction->amount_cents / 100, 2) }}</p>
@if (!empty($refundData['data']['reference']))
    <p><strong>Refund Reference:</strong> {{ $refundData['data']['reference'] }}</p>
@endif
