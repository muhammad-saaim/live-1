<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.setting.user.index', compact('users'));
    }



    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $countries = Country::all();
        $roles = Role::all();
        return view('admin.setting.user.create',compact('countries','roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',              // Minimum length of 8 characters
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one digit
                'regex:/[@$!%*?&]/'   // At least one special character
            ],
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'status' => 'boolean',
            'country' => 'nullable|exists:countries,id', // Assumes country ID exists in the countries table
            'language' => 'nullable|string|max:10',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id', // Ensure each selected role exists in roles table
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status ?? true,
            'phone' => $request->phone,
            'country' => $request->country, // Store selected country
            'language' => $request->language ?? 'en',
        ]);

        // Assign roles to user if any roles are selected
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $country_id = $user->country ?? null;
        $country_name = $country_id ? Country::find($country_id)->name ?? 'N/A' : 'N/A';

        return view('admin.setting.user.show', compact('user', 'country_name', 'country_id'));
    }


    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $countries = Country::all();
        return view('admin.setting.user.edit', compact('user', 'countries','roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',              // Minimum length of 8 characters
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one digit
                'regex:/[@$!%*?&]/'   // At least one special character
            ],
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'status' => 'boolean',
            'country' => 'nullable|exists:countries,id',
            'language' => 'nullable|string|max:10',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id', // Each selected role must exist in roles table
        ]);

        // Update user data
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->status = $request->status ?? $user->status;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->language = $request->language;

        // Update password only if a new password is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the user
        $user->save();

        // Sync roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // Redirect back to the user list with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }


}
