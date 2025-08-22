<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\User;
use App\Notifications\AdminNewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewUserSendAdminNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUserRegistered $event): void
    {
        $admins = User::role('admin')->get();

        Notification::send($admins, new AdminNewUserNotification($event->user));

    }
}
