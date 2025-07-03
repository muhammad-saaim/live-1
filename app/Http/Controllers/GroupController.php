<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupType;
use App\Models\Invitation;
use App\Models\InvitedMember;
use Illuminate\Http\Request;
use App\Models\Relation;
use App\Models\UserRelative;

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
        // Check if the current user is a member of the group
        if (!auth()->user()->groups->contains($group->id)) {
            abort(404);
        }
        // dd($group);
        $relations = Relation::all(); // Fetch all relations
        
        // Get all invitations for this group
        $invitations = Invitation::where('group_id', $group->id)->get();
        
        // Get all users from the group
        $groupUsers = $group->users;
        
        // Create a collection to store all users (both group members and invited users)
        $allUsers = collect();
        
        // Add existing group users
        foreach ($groupUsers as $user) {
            $allUsers->push([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => 'member',
                'relation' => $user->id === auth()->id() 
                    ? 'Me'
                    : ($group->groupTypes->first()?->name == 'Family' 
                        ? UserRelative::where('user_id', auth()->id())
                            ->where('relative_id', $user->id)
                            ->with('relation')
                            ->first()?->relation?->name ?? 'N/A'
                        : 'Member')
            ]);
        }
        
        // Add invited users
        foreach ($invitations as $invitation) {
            // Check if user exists with this email
            $existingUser = User::where('email', $invitation->email)->first();
            
            if ($existingUser) {
                // If user exists, use their actual data
                $allUsers->push([
                    'id' => $existingUser->id,
                    'name' => $existingUser->name,
                    'email' => $existingUser->email,
                    'status' => 'invited',
                    'relation' => $group->groupTypes->first()?->name == 'Family'
                        ? Relation::find($invitation->relation_id)?->name ?? 'N/A'
                        : 'Member'
                ]);
            } else {
                // If user doesn't exist, use placeholder data
                $allUsers->push([
                    'id' => null,
                    'name' => 'Not Registered',
                    'email' => $invitation->email,
                    'status' => 'invited',
                    'relation' => $group->groupTypes->first()?->name == 'Family'
                        ? Relation::find($invitation->relation_id)?->name ?? 'N/A'
                        : 'Member'
                ]);
            }
        }
        
        return view('group.show', compact('group', 'relations', 'allUsers'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        // Check if the current user is the group admin
        if (auth()->id() !== $group->group_admin) {
            return redirect()->back()->with('error', 'Only the group admin can edit the group.');
        }

        $groupTypes = GroupType::whereNull('parent_id')->get();
        return view('group.edit', compact('group','groupTypes'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        // Check if the current user is the group admin
        if (auth()->id() !== $group->group_admin) {
            return redirect()->back()->with('error', 'Only the group admin can update the group.');
        }

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

    public function removeMember(Request $request, Group $group)
    {
        // Handle multiple user removal (admin only)
        if ($request->has('user_ids')) {
            if (auth()->id() !== $group->group_admin) {
                return redirect()->back()->with('error', 'Only the group admin can remove members.');
            }

            $userIds = $request->input('user_ids');
            // Don't allow removing the group admin
            $userIds = array_filter($userIds, function($userId) use ($group) {
                return $userId != $group->group_admin;
            });
            
            if (!empty($userIds)) {
                $group->users()->detach($userIds);
                return redirect()->back()->with('success', 'Selected members removed successfully.');
            }
        }
        // Handle single user removal
        else if ($request->has('user') || $request->user_id) {                
            // If user is trying to remove themselves
            if (auth()->id() == $request->user_id) {
                // Don't allow group admin to leave
                if ($request->user_id === $group->group_admin) {
                    return redirect()->back()->with('error', 'Group admin cannot leave the group. Please transfer admin rights first.');
                }
                
                $group->users()->detach($request->user_id);
                return redirect()->route('dashboard.index')->with('success', 'You have left the group successfully.');
            }
            // If admin is removing another user
            else if (auth()->id() === $group->group_admin) {
                $user = User::findOrFail($request->user);

                // Don't allow removing the group admin
                if ($user->id === $group->group_admin) {
                    return redirect()->back()->with('error', 'Cannot remove the group admin.');
                }
                $group->users()->detach($user->id);
                return redirect()->back()->with('success', 'Member removed successfully.');
            }
            else {
                return redirect()->back()->with('error', 'You are not authorized to remove members.');
            }
        }

        return redirect()->back()->with('error', 'No valid members selected for removal.');
    }

    /**
     * Cancel an invitation for a group.
     */
    public function cancelInvitation(Group $group, $email)
    {
        // Check if the current user is the group admin
        if (auth()->id() !== $group->group_admin) {
            return redirect()->back()->with('error', 'Only the group admin can cancel invitations.');
        }

        // Find and delete the invitation
        $invitation = Invitation::where('group_id', $group->id)
            ->where('email', $email)
            ->first();

        if ($invitation) {
            $invitation->delete();
            return redirect()->back()->with('success', 'Invitation cancelled successfully.');
        }

        return redirect()->back()->with('error', 'Invitation not found.');
    }

}
