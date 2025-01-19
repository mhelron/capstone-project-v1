<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationStatusUpdateMail;

class QuotationControllerAdmin extends Controller
{
    protected $database, $quotations;
    
    public function __construct(Database $database){
        $this->database = $database;
        $this->quotations = 'quotations';
    }

    public function index(){
        $quotations = $this->database->getReference('quotations')->getValue();
        $quotations = is_array($quotations) ? $quotations : [];
        $isExpanded = session()->get('sidebar_is_expanded', true);

        return view('admin.quotation.index', compact('quotations', 'isExpanded'));
    }

    public function updateStatus($id, Request $request)
{
    try {
        $status = $request->status;
        
        $quotationRef = $this->database->getReference($this->quotations)->getChild($id);
        $quotation = $quotationRef->getValue();

        if (!$quotation) {
            return redirect()->back()->with('error', 'Quotation request not found.');
        }

        $updates = [
            'status' => $status,
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];

        $quotationRef->update($updates);

        // Merge updates for email
        $quotation = array_merge($quotation, $updates);

        // Send status update email
        try {
            Mail::mailer('clients')
                ->to($quotation['email'])
                ->send(new QuotationStatusUpdateMail($quotation));

            Log::info('Quotation status update email sent successfully', [
                'email' => $quotation['email'],
                'status' => $status
            ]);
        } catch (\Exception $mailException) {
            Log::error('Failed to send quotation status update email:', [
                'error' => $mailException->getMessage(),
                'email' => $quotation['email']
            ]);
        }

        $statusMessage = ucfirst($status);
        return redirect()->back()->with('status', "Quotation request has been {$statusMessage}!");

    } catch (\Exception $e) {
        Log::error('Error updating quotation status:', [
            'error' => $e->getMessage(),
            'id' => $id
        ]);
        return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
    }
}

    public function setPricing(Request $request, $id)
    {
        try {
            $request->validate([
                'total_price' => 'required|numeric|min:0',
            ]);

            $this->database->getReference($this->quotations . '/' . $id)->update([
                'total_price' => $request->total_price,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('status', 'Pricing has been set successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set pricing: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:Paid,Not Paid,Partially Paid',
            ]);

            $this->database->getReference($this->quotations . '/' . $id)->update([
                'payment_status' => $request->payment_status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('status', 'Payment status has been updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }
}