<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index()
    {
        $reportServices = Service::active()->byCategory('report')->get();
        $mentoringServices = Service::active()->byCategory('mentoring')->get();

        $children = Auth::user()->relatives()
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('users_surveys')
                      ->whereColumn('users_surveys.user_id', 'users.id')
                      ->where('users_surveys.is_completed', true);
            })
            ->get();

        return view('billing.index', compact('reportServices', 'mentoringServices', 'children'));
    }

    public function createInvoice(Request $request)
    {
        $request->validate([
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.item_details' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $invoice = new Invoice();
            $invoice->user_id = Auth::id();
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->issued_at = now();
            $invoice->billing_details = [
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'billing_address' => $request->billing_address ?? null
            ];

            $totalAmount = 0;
            $invoiceItems = [];

            foreach ($request->services as $serviceData) {
                if (!isset($serviceData['service_id'])) continue;

                $service = Service::findOrFail($serviceData['service_id']);
                $quantity = $serviceData['quantity'] ?? 1;
                $itemTotal = $service->price * $quantity;
                $totalAmount += $itemTotal;

                $invoiceItems[] = [
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'unit_price' => $service->price,
                    'quantity' => $quantity,
                    'total_price' => $itemTotal,
                    'item_details' => $serviceData['item_details'] ?? null
                ];
            }

            $invoice->total_amount = $totalAmount;
            $invoice->save();

            foreach ($invoiceItems as $itemData) {
                $invoice->items()->create($itemData);
            }

            DB::commit();

            return redirect()->route('billing.invoice', $invoice->id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    public function showInvoice(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) abort(403);

        $invoice->load('items.service', 'user');
        return view('billing.invoice', compact('invoice'));
    }

    public function checkout(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) abort(403);

        if ($invoice->status !== 'pending') {
            if ($invoice->status === 'paid') {
                session()->forget('error');
                return redirect()->route('billing.invoice', $invoice->id)
                    ->with('success', 'Invoice paid successfully.');
            }

            return redirect()->route('billing.invoice', $invoice->id)
                ->with('error', 'This invoice has already been processed.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $invoice->total_amount * 100,
                'currency' => 'usd',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                ],
            ]);

            return view('billing.checkout', compact('invoice', 'paymentIntent'));
        } catch (\Exception $e) {
            return redirect()->route('billing.invoice', $invoice->id)
                ->with('error', 'Unable to initialize payment. Please try again.');
        }
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        $request->validate(['payment_intent_id' => 'required|string']);

        if ($invoice->user_id !== Auth::id()) abort(403);

        if ($invoice->status !== 'pending') {
            return redirect()->route('billing.invoice', $invoice->id)
                ->with('error', 'This invoice has already been processed.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $invoice->markAsPaid();
                $invoice->paid_at = Carbon::createFromTimestamp($paymentIntent->created);

                $invoice->payment_details = [
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount_paid' => $paymentIntent->amount / 100,
                    'currency' => $paymentIntent->currency,
                    'payment_method' => $paymentIntent->payment_method,
                ];

                $invoice->save();

                return redirect()->route('billing.invoice', $invoice->id)
                    ->with('success', 'Invoice paid successfully.');
            }

            return back()->with('error', 'Payment was not successful. Please try again.');

        } catch (\Exception $e) {
            \Log::error('Stripe payment error: ' . $e->getMessage());
            return back()->with('error', 'Payment processing error. Please try again.');
        }
    }

    public function invoiceHistory()
    {
        $invoices = Auth::user()->invoices()->with('items')->orderBy('created_at', 'desc')->get();
        return view('billing.history', compact('invoices'));
    }



    public function edit(Invoice $invoice)
    {
        $services = Service::all();
        $children = $invoice->user->relatives ?? [];
        $invoice->load('items');
        return view('billing.edit', compact('invoice', 'services', 'children'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice->items()->delete();

            $totalAmount = 0;
            foreach ($request->services as $serviceData) {
                $quantity = $serviceData['quantity'] ?? 1;
                $unitPrice = $serviceData['unit_price'];
                $itemTotal = $unitPrice * $quantity;
                $totalAmount += $itemTotal;

                $invoice->items()->create([
                    'service_id' => $serviceData['service_id'],
                    'service_name' => $serviceData['service_name'],
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $itemTotal,
                    'item_details' => $serviceData['item_details'] ?? null,
                ]);
            }

            $invoice->total_amount = $totalAmount;
            $invoice->save();

            DB::commit();
            return redirect()->route('billing.history')->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update invoice.');
        }
    }

    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice->items()->delete();
            $invoice->delete();
            DB::commit();

            return redirect()->route('billing.history')->with('success', 'Invoice deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete invoice.');
        }
    }
}
