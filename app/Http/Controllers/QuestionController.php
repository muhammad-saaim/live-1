<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions.
     */
    public function index(Request $request)
    {
        $query = Question::query();

        // Search Filter
        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        // Paginate 20 questions per page
        $questions = $query->with(['survey', 'type'])->paginate(20);

        return view('admin.setting.question.index', compact('questions'));
    }


    /**
     * Show the form for creating a new question.
     */
    public function create(Request $request)
    {
        $types = Type::all();
        $survey_id = $request->input('survey_id');
        return view('admin.setting.question.create', compact('types','survey_id'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            //'type' => 'required|in:single_choice,multiple_choice,true_false,text',
            //'options' => 'nullable|json', // Ensure JSON format if provided
            //'correct_answer' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'survey_id' => 'required|integer|exists:surveys,id',
            'type_id' => 'required|integer|exists:types,id',
        ]);

        $question = Question::create($request->all());
        //create automatically 5 options for the question
        $question->options()->createMany([
            ['name' => '1','point' => '1'],
            ['name' => '2','point' => '2'],
            ['name' => '3','point' => '3'],
            ['name' => '4','point' => '4'],
            ['name' => '5','point' => '5'],
        ]);

        return redirect()->route('survey.show',$request->survey_id)->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        return view('admin.setting.question.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $types = Type::all();
        return view('admin.setting.question.edit', compact('question','types'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            //'type' => 'required|in:single_choice,multiple_choice,true_false,text',
            //'options' => 'nullable|json', // Ensure JSON format if provided
            //'correct_answer' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'survey_id' => 'required|integer|exists:surveys,id',
            'type_id' => 'required|integer|exists:types,id',
        ]);

        $question->update($request->all());

        return redirect()->route('question.index')->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('question.index')->with('success', 'Question deleted successfully.');
    }

    public function autoComplete()
    {
        $questions = Question::with('options')->get();
        foreach ($questions as $question) {
            if($question->options->isEmpty()) {
                $question->options()->createMany([
                    ['name' => '1','point' => '1'],
                    ['name' => '2','point' => '2'],
                    ['name' => '3','point' => '3'],
                    ['name' => '4','point' => '4'],
                    ['name' => '5','point' => '5'],
                ]);
            }
        }
        return redirect()->route('question.index')->with('success', 'Questions options created successfully.');
    }

}
