<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoalRequest;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        // $soals = \App\Models\Soal::paginate(10);
        $soals = DB::table('soals')
            ->when($request->input('pertanyaan'), function ($query, $pertanyaan) {
                return $query->where('pertanyaan', 'like', '%' . $pertanyaan . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.soals.index', compact('soals'), ['type_menu' => 'soals']);
    }

    public function create()
    {
        return view('pages.soals.create', ['type_menu' => 'soals']);
    }

    public function store(StoreSoalRequest $request)
    {
        $data = $request->validated();
        $data['password']=Hash::make($request->password);
        \App\Models\Soal::create($data);
        return redirect()->route('soal.index')->with('success','Soal successfully created');
    }

    public function edit(Soal $soal){
        return view('pages.soals.edit',compact('soal'), ['type_menu' => 'soals']);
    }

    public function update(StoreSoalRequest $request,Soal $soal)
    {
        $data = $request->validated();
        $soal->update($data);
        return redirect()->route('soal.index')->with('success','Soal successfully updated');
    }

    public function destroy(Soal $soal){
        $soal->delete();
        return redirect()->route('soal.index')->with('success','Soal successfully deleted');
    }
}
