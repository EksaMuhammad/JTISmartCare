<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosisRule;

class KnowledgeController extends Controller
{
    public function index()
    {
        $rules = DiagnosisRule::orderBy('rule_code')->get();
        return view('admin.knowledge', compact('rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rule_code' => 'required|string|unique:diagnosis_rules,rule_code',
            'kondisi' => 'required|string',
            'kondisi_json' => 'required|json',
            'hasil_risiko' => 'required|string',
            'certainty_factor' => 'required|numeric',
            'rekomendasi' => 'required|string',
        ]);

        $data = $request->all();
        $data['kondisi_json'] = json_decode($request->kondisi_json, true);

        DiagnosisRule::create($data);

        return redirect()->route('admin.knowledge.index')->with('success', 'Rule berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $rule = DiagnosisRule::findOrFail($id);

        $request->validate([
            'rule_code' => 'required|string|unique:diagnosis_rules,rule_code,' . $rule->id,
            'kondisi' => 'required|string',
            'kondisi_json' => 'required|json',
            'hasil_risiko' => 'required|string',
            'certainty_factor' => 'required|numeric',
            'rekomendasi' => 'required|string',
        ]);

        $data = $request->all();
        $data['kondisi_json'] = json_decode($request->kondisi_json, true);

        $rule->update($data);

        return redirect()->route('admin.knowledge.index')->with('success', 'Rule berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rule = DiagnosisRule::findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.knowledge.index')->with('success', 'Rule berhasil dihapus.');
    }
}
