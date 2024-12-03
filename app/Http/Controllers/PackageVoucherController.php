<?php

namespace App\Http\Controllers;

use App\Models\PackageVoucher;
use App\Http\Requests\StorePackageVoucherRequest;
use App\Http\Requests\UpdatePackageVoucherRequest;

class PackageVoucherController extends Controller
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
                'name' => 'Package',
                'url' => route('packages.index'),
                'active' => false
            ],
            [
                'name' => 'List',
                'url' => '#',
                'active' => true
            ],
        ];

        $data = PackageVoucher::all();

        return view('package-vouchers.index',[
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Package'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbItems = [
            [
                'name' => 'Create Package',
                'url' => route('packages.index'),
                'active' => false
            ],
            [
                'name' => 'Create',
                'url' => '#',
                'active' => true
            ],
        ];

        return view('package-vouchers.create', [
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Create Package'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePackageVoucherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageVoucherRequest $request)
    {
        // dd($request);
        // dd($request->file('image'));
        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the image in the 'public/voucher_images' directory
            $imagePath = $request->file('image')->store('voucher_images', 'public');
        }

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo_unit')) {
            $logoPath = $request->file('logo_unit')->store('logo_unit', 'public');
        }

        // Merge the image path and TnC into the validated request data
        $validatedData = array_merge($request->validated(), [
            'image' => $imagePath,  // Store the image path
            'tnc' => $request->input('tnc'),  // Get TnC input from the request
            'logo_unit' => $logoPath,
        ]);

        $packageVoucher = PackageVoucher::create($validatedData);

        return redirect()->route('packages.index')->with('message', 'Paket Voucher created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackageVoucher  $packageVoucher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbItems = [
            [
                'name' => 'Package Voucher',
                'url' => route('packages.index'),
                'active' => false
            ],
            [
                'name' => 'View',
                'url' => '#',
                'active' => true
            ],
        ];

        $data = PackageVoucher::findOrFail($id);
        return view('package-vouchers.show',[
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'View Package Voucher'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PackageVoucher  $packageVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $breadcrumbItems = [
            [
                'name' => 'Package Voucher',
                'url' => route('packages.index'),
                'active' => false
            ],
            [
                'name' => 'Edit',
                'url' => '#',
                'active' => true
            ],
        ];

        $data = PackageVoucher::findOrFail($id);
        // Format amount in Rupiah
        $data->formatted_amount = number_format($data->amount, 0, ',', '.');

        return view('package-vouchers.edit',[
            'data' => $data,
            'breadcrumbItems' => $breadcrumbItems,
            'pageTitle' => 'Edit Package Voucher'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageVoucherRequest  $request
     * @param  \App\Models\PackageVoucher  $packageVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageVoucherRequest $request, $id)
    {
        // Find the package voucher by ID
        $packageVoucher = PackageVoucher::findOrFail($id);

        // Get the validated data from the request
        $validatedData = $request->validated();

        // Convert the formatted amount to numeric
        if (isset($validatedData['amount'])) {
            $validatedData['amount'] = (int) str_replace(['.', 'Rp', ' '], '', $validatedData['amount']);
        }

        // Update the package voucher with the processed data
        $packageVoucher->update($validatedData);

        // Redirect back with success message
        return redirect()->route('packages.index')->with('message', 'Paket Voucher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackageVoucher  $packageVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PackageVoucher::destroy($id);

        return redirect()->route('packages.index')->with('message', 'Paket Voucher deleted successfully.');
    }
}
