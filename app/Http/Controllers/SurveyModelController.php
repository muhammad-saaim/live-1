<?php

namespace App\Http\Controllers;
use App\Models\SurveyModel;
use Illuminate\Http\Request;

class SurveyModelController
{
    public function index()
    {
        $surveyModels = SurveyModel::all();
        return view('admin.setting.model.index', compact('surveyModels'));
    }

    public function create()
    {
        return view('admin.setting.model.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SurveyModel::create($request->all());

        return redirect()->route('surveyModel.index')->with('success', 'Survey model created successfully.');
    }

    public function show(SurveyModel $surveyModel)
    {
        return view('admin.setting.model.show', compact('surveyModel'));
    }

    public function edit(SurveyModel $surveyModel)
    {
        return view('admin.setting.model.edit', compact('surveyModel'));
    }

    public function update(Request $request, SurveyModel $surveyModel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $surveyModel->update($request->all());

        return redirect()->route('surveyModel.index')->with('success', 'Survey updated successfully.');
    }

    public function destroy(SurveyModel $surveyModel)
    {
        $surveyModel->delete();

        return redirect()->route('surveyModel.index')->with('success', 'Survey deleted successfully.');
    }
}
