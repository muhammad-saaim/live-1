<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    public function invite(User $inviter, string $email, Group $group, ?int $relationId = null): Invitation
    {
        // Generate an invitation token
        $token = Str::random(32);
        $user = User::where('email', $email)->first();

        // Check if an invitation already exists for this email and group
        $invitation = Invitation::where('email', $email)
            ->where('group_id', $group->id)
            ->first();

        if ($invitation) {
            // Update the inviter and relation_id, and keep the same token
            $invitation->invited_by = $inviter->id;
            $invitation->relation_id = $relationId;
            $invitation->touch(); 
            $invitation->created_at = now();
            $invitation->save();
        } else {
            // Create the invitation record in the database
            $invitation = Invitation::create([
                'email' => $email,
                'group_id' => $group->id,
                'invited_by' => $inviter->id,
                'token' => $token,
                'relation_id' => $relationId,
            ]);
        }

        $link = $user
            ? url("/groups/accept-invite?token={$invitation->token}")
            : url("/register?token={$invitation->token}");
        $messageText = $user
            ? "$inviter->name invited you to Join  $group->name: $link"
            : "$inviter->name invited you to register: $link";
        Mail::raw($messageText, function ($message) use ($email) {
            $message->to($email)
                ->subject("Group Invitation");
        });

        return $invitation;
    }
}
