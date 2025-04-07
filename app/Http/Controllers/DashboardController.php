<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUserId = auth()->user()->id;

        // Retrieve all individual surveys (applies_to containing 'Individual')
        $individualSurveys = Survey::whereJsonContains('applies_to', 'Individual')->get();

        foreach ($individualSurveys as $survey) {
            // Check if the survey is already assigned to the authenticated user
            $isAssigned = DB::table('users_surveys')
                ->where('user_id', $authUserId)
                ->where('survey_id', $survey->id)
                ->exists();

            // If not assigned, assign it to the user
            if (!$isAssigned) {
                DB::table('users_surveys')->insert([
                    'user_id' => $authUserId,
                    'survey_id' => $survey->id,
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return view('dashboard');
    }


}
