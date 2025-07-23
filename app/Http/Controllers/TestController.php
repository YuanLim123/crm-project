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
        $task = Client::first()->project->tasks->first();
        return $task->end_date;
    }

    public function addMediaToLibrary(Request $request)
    {
        User::factory()
            ->create()
            ->addMedia(storage_path('demo/facebook.jpg'))
            ->toMediaCollection();
    }

    public function testCollectionApis(Request $request)
    {
        $collection = collect([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $collapsed = $collection->collapse();

        return $collapsed->all();
    }
}
