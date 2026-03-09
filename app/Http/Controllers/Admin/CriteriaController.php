<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCriteriaRequest;
use App\Models\Criteria;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::orderBy('weight_order')->get();
        return view('admin.criterias.index', compact('criterias'));
    }

    public function create()
    {
        return view('admin.criterias.create');
    }

    public function store(StoreCriteriaRequest $request)
    {
        Criteria::create($request->validated());
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $criteria)
    {
        return view('admin.criterias.edit', compact('criteria'));
    }

    public function update(StoreCriteriaRequest $request, Criteria $criteria)
    {
        $criteria->update($request->validated());
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil diubah.');
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
