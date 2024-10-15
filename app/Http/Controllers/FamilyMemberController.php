<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Http\Requests\StoreFamilyMemberRequest;
use App\Http\Requests\UpdateFamilyMemberRequest;

class FamilyMemberController extends Controller
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
     * @param  \App\Http\Requests\StoreFamilyMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFamilyMemberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyMember $familyMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyMember $familyMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFamilyMemberRequest  $request
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFamilyMemberRequest $request, FamilyMember $familyMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyMember $familyMember)
    {
        //
    }
}
