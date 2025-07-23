<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Arr;

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

    public function updateRelations(Request $request)
    {
        $oldData = json_decode('[{"id":1,"name":"one"},{"id":2,"name":"two"}]');
        $newData = json_decode('[{"id":2,"name":"TWO"},{"name":"three"}]');

        // results
        $results = $this->crudPartition($oldData, $newData);
        dd($results);
    }

    private function crudPartition($oldData, $newData)
    {
        // ids
        $oldIds = Arr::pluck($oldData, 'id');
        $newIds = array_filter(Arr::pluck($newData, 'id'), 'is_numeric');

        // groups
        $delete = collect($oldData)
            ->filter(function ($model) use ($newIds) {
                return !in_array($model->id, $newIds);
            });

        $update = collect($newData)
            ->filter(function ($model) use ($oldIds) {
                return property_exists($model, 'id') && in_array($model->id, $oldIds);
            });

        $create = collect($newData)
            ->filter(function ($model) {
                return !property_exists($model, 'id');
            });

        // return
        return compact('delete', 'update', 'create');
    }
}
