<?php

namespace App\Http\Controllers;

use App\Models\PromoHistory;
use App\Http\Requests\StorePromoHistoryRequest;
use App\Http\Requests\UpdatePromoHistoryRequest;

class PromoHistoryController extends Controller
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
     * @param  \App\Http\Requests\StorePromoHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePromoHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromoHistory  $promoHistory
     * @return \Illuminate\Http\Response
     */
    public function show(PromoHistory $promoHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromoHistory  $promoHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoHistory $promoHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePromoHistoryRequest  $request
     * @param  \App\Models\PromoHistory  $promoHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePromoHistoryRequest $request, PromoHistory $promoHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromoHistory  $promoHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoHistory $promoHistory)
    {
        //
    }
}
