<p>Hello {{ $registration->name }},</p>
<p>Your payment has been confirmed.</p>
<p><strong>Reference:</strong> {{ $transaction->provider_reference }}</p>
<p><strong>Amount:</strong> NGN {{ number_format($transaction->amount_cents / 100, 2) }}</p>
<p>You can access your ticket from the portal dashboard.</p>
