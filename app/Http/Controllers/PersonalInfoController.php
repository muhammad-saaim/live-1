<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\Auth;

class PersonalInfoController extends Controller
{
    public function index(){
        return view('profile.information');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'biography' => 'nullable|string',
            'full_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'birthDate' => 'nullable|date',
            'blood' => 'nullable|string|max:10',
            'gradDepartment' => 'nullable|string|max:255',
            'gradSchool' => 'nullable|string|max:255',
            'gradYear' => 'nullable|digits:4',
            'phone' => 'nullable|string|max:20',
            'profession' => 'nullable|string|max:255',
            'currJob' => 'nullable|string|max:255',
            'pastJob' => 'nullable|string|max:255',
            'hobies' => 'nullable|string|max:255',
            'pet' => 'nullable|string|max:255',
            'skill1' => 'nullable|string|max:255',
            'skill2' => 'nullable|string|max:255',
            'skill3' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image
        ]);

        PersonalInfo::create([
            'user_id' => Auth::id(),
            'biography' => $request->biography,
            'full_name' => $request->full_name,
            'city' => $request->city,
            'birthdate'=> $request->birthDate,
            'blood_type'=> $request->blood,
            'graduated_department'=> $request->gradDepartment,
            'graduated_school'=> $request->gradSchool,
            'graduated_year'=> $request->gradYear,
            'phone'=> $request->phone,
            'profession'=> $request->profession,
            'current_job'=> $request->currJob,
            'past_job'=>$request->pastJob,
            'hobies'=>$request->hobies,
            'pet_type'=>$request->pet,
            'skill_1'=>$request->skill1,
            'skill_2'=>$request->skill2,
            'skill_3'=>$request->skill3
        ]);

        return redirect()->route('profile.information')->with('success', 'Personal information created successfully.');
    }

}
