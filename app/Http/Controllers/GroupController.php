<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupType;
use Illuminate\Http\Request;

class GroupController extends Controller
{


    /* index() - To display a list of resources.
    create() - To show a form for creating a new resource.
    store() - To save a new resource.
    show() - To display a single resource.
    edit() - To show a form for editing a resource.
    update() - To update a resource.
    destroy() - To delete a resource. */

    public function index(){
        $groups = auth()->user()->groups;
        return view('group.index',compact('groups'));
    }

    public function create()
    {
        $groupTypes = GroupType::whereNull('parent_id')->get();
        return view('group.create',compact('groupTypes'));
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'group_types' => 'array',
            'group_types.*' => 'exists:group_types,id',
            'subgroup_types' => 'array',
            'subgroup_types.*' => 'exists:group_types,id',
        ]);

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

        // Create the new group
        $group = Group::create([
            'name' => $validatedData['name'],
            'group_admin' => auth()->id(),
            'description' => $validatedData['description'],
            'color' => $color
        ]);
        $group->users()->attach(auth()->id());

        // Attach selected group types
        if (isset($validatedData['group_types'])) {
            $group->groupTypes()->attach($validatedData['group_types']);
        }

        // Attach selected subgroup types
        if (isset($validatedData['subgroup_types'])) {
            $group->groupTypes()->attach($validatedData['subgroup_types']);
        }

        return redirect()->route('group.show',$group)->with('success', 'Group created successfully.');
    }

    /**
     * Display the specified group.
     */
    public function show(Group $group)
    {
        return view('group.show', compact('group'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        $groupTypes = GroupType::whereNull('parent_id')->get();
        return view('group.edit', compact('group','groupTypes'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'group_types' => 'array',
            'group_types.*' => 'exists:group_types,id',
            'subgroup_types' => 'array',
            'subgroup_types.*' => 'exists:group_types,id',
        ]);

        if (empty($group->color)) {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); 
        } else {
            $color = $group->color; 
        }

        // Update the group's basic information
        $group->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'color' => $color
        ]);

        // Sync the selected group types and subgroups
        $selectedGroupTypes = $validatedData['group_types'] ?? [];
        $selectedSubgroupTypes = $validatedData['subgroup_types'] ?? [];

        // Combine group types and subgroups into a single array for syncing
        $allSelectedGroupTypes = array_merge($selectedGroupTypes, $selectedSubgroupTypes);

        // Sync the relationships in the pivot table
        $group->groupTypes()->sync($allSelectedGroupTypes);

        return redirect()->route('group.show', $group)->with('success', 'Group updated successfully.');
    }


    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('dashboard.index')->with('success', 'Group deleted successfully.');
    }



}
