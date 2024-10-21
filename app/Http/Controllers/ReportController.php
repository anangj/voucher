<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Payment;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbItems = [
            [
                'name' => 'Reports',
                'url' => route('reports.index'),
                'active' => false,
            ],
            [
                'name' => 'List',
                'url' => '#',
                'active' => true,
            ],
        ];

        $voucherSales = Payment::with('patient', 'voucherHeader.paketVoucher')->get();
        // dd($voucherSales);

        return view('reports.index', [
            'voucherSales' => $voucherSales,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Reports',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReportRequest  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    public function generateVoucherSalesReport(Request $request)
    {
        // Retrieve date range from request for filtering
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        // Query the payment table along with patient and voucher details
        $salesReport = Payment::with(['patient', 'voucher.packageVoucher'])
            ->whereBetween('created_at', [$startDate, $endDate]) // Filter by date range
            ->get();

        // Calculate total amount sold
        $totalAmountSold = $salesReport->sum('amount'); // Assuming 'amount' is the column for the total payment made

        return view('reports.voucher_sales_report', [
            'salesReport' => $salesReport,
            'totalAmountSold' => $totalAmountSold,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    // public function downloadVoucherSalesReport(Request $request)
    // {
    //     $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    //     $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

    //     $salesReport = Payment::with(['patient', 'voucher.packageVoucher'])
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->get();

    //     // $csv = Writer::createFromString('');
    //     $csv = Writer::createFromString('')
    //     $csv->insertOne(['Patient Name', 'Voucher Name', 'Purchase Date', 'Amount', 'Payment Method']);

    //     foreach ($salesReport as $payment) {
    //         $csv->insertOne([
    //             $payment->patient->name,
    //             $payment->voucher->packageVoucher->name,
    //             $payment->created_at->format('Y-m-d'),
    //             number_format($payment->amount, 2),
    //             ucfirst($payment->payment_method),
    //         ]);
    //     }

    //     return response((string) $csv)
    //         ->header('Content-Type', 'text/csv')
    //         ->header('Content-Disposition', 'attachment; filename="voucher_sales_report.csv"');
    // }
}
