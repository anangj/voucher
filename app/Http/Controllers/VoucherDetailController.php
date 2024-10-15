<?php

namespace App\Http\Controllers;

use App\Models\VoucherDetail;
use Illuminate\Http\Request;

class VoucherDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('ss');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoucherDetail  $voucherDetail
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherDetail $voucherDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherDetail  $voucherDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherDetail $voucherDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoucherDetail  $voucherDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoucherDetail $voucherDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherDetail  $voucherDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoucherDetail $voucherDetail)
    {
        //
    }
}
