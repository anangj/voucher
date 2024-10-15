<?php

namespace App\Http\Controllers;

use App\Models\VoucherHistory;
use App\Http\Requests\StoreVoucherHistoryRequest;
use App\Http\Requests\UpdateVoucherHistoryRequest;

class VoucherHistoryController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVoucherHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVoucherHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoucherHistory  $voucherHistory
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherHistory $voucherHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherHistory  $voucherHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherHistory $voucherHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVoucherHistoryRequest  $request
     * @param  \App\Models\VoucherHistory  $voucherHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVoucherHistoryRequest $request, VoucherHistory $voucherHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherHistory  $voucherHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoucherHistory $voucherHistory)
    {
        //
    }
}
