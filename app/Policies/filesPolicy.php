<?php

namespace App\Policies;

use App\Models\User;
use App\Models\files;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class filesPolicy
{
    public function view(User $user,files $file)
    {
        Log::info('User : '.$user->id.'Requesting Authorization for Viewing File '.$file->id);
        return $user->id === $file->user_id;
    }

    public function delete(User $user,files $file)
    {
        Log::info('User : '.$user->id.'Requesting Authorization for deleting File '.$file->id);
        return $user->id === $file->user_id;
    }

}
