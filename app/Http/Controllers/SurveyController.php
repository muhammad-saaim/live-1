<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Survey;
use App\Models\SurveyModel;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::all();
        return view('admin.setting.survey.index', compact('surveys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $surveyModels = SurveyModel::all();
        return view('admin.setting.survey.create', compact('surveyModels',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'model_id' => 'required|exists:survey_models,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'applies_to' => 'nullable|array',
            'targets' => 'nullable|array',
        ]);

        Survey::create([
            'model_id' => $request->model_id,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
            'is_default' => $request->is_default ?? false,
            'applies_to' => $request->applies_to,
            'targets' => $request->targets,
        ]);
        return redirect(route('survey.index'))->with('success', 'Survey successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey)
    {
        return view('admin.setting.survey.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey)
    {
        $surveyModels = SurveyModel::all();
        return view('admin.setting.survey.edit', compact('survey','surveyModels'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'model_id' => 'required|exists:survey_models,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'applies_to' => 'nullable|array',
            'targets' => 'nullable|array',
        ]);

        $survey->update([
            'model_id' => $request->model_id,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active,
            'is_default' => $request->is_default,
            'applies_to' => $request->applies_to,
            'targets' => $request->targets,
        ]);

        return redirect()->route('survey.index')->with('success', 'Survey updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('survey.index')->with('success', 'Survey deleted successfully.');
    }
}
