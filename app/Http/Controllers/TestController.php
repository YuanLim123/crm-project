<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Client::first()->project->tasks;
        return $tasks;
    }

    public function addMediaToLibrary(Request $request)
    {
        User::factory()
            ->create()
            ->addMedia(storage_path('demo/facebook.jpg'))
            ->toMediaCollection();

    }


}
