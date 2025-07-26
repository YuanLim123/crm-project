<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProjectStatus;
use App\Http\Requests\ProjectPostRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('client')->get();

        return Inertia::render('Project/Index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $clients = Client::doesntHave('project')->get();
        $users = User::all();

        return Inertia::render('Project/Create', [
            'clients' => $clients,
            'users' => $users,
            'status' => ProjectStatus::toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectPostRequest $request)
    {
        $attributes = $request->validated();

        $project = Project::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'client_id' => $attributes['client'],
            'user_id' => $attributes['assignedUser'],
        ]);

        if (!empty($attributes['tasks'])) {
            foreach ($attributes['tasks'] as $task) {
                $project->tasks()->create([
                    'title' => $task['title'],
                    'description' => $task['description'],
                    'status' => $task['status'],
                    'end_date' => $task['endDate'],
                    'user_id' => $task['user'],
                ]);
            }
        }

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['client', 'user', 'tasks', 'tasks.user'])->findOrFail($id);
        return Inertia::render('Project/Show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::doesntHave('project')->get();
        $users = User::all();

        $project->load(['client', 'user', 'tasks.user']);

        $project->tasks->transform(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                // since we change the accessor for end_date to d/m/Y, we need to format it back to Y-m-d
                // to show it in the form
                'endDate' => Carbon::createFromFormat('d/m/Y', $task->end_date)->format('Y-m-d'),
                'user' => $task->user,
            ];
        });

        return Inertia::render('Project/Edit', [
            'clients' => $clients,
            'users' => $users,
            'project' => $project,
            // since we change the accessor for end_date to d/m/Y, we need to format it back to Y-m-d
            // to show it in the form
            'endDate' => Carbon::createFromFormat('d/m/Y', $project->end_date)->format('Y-m-d'),
            'status' => ProjectStatus::toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectPostRequest $request, Project $project)
    {
        
        $attributes = $request->validated();

        $project->update([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'client_id' => $attributes['client'],
            'user_id' => $attributes['assignedUser'],
        ]);

        // initial approach, Delete all old tasks then Create new tasks
        // $project->tasks()->delete();
        // foreach ($attributes['tasks'] as $task) {
        //     $project->tasks()->create([
        //         'title' => $task['title'],
        //         'description' => $task['description'],
        //         'status' => $task['status'],
        //         'end_date' => $task['endDate'],
        //         'user_id' => $task['user'],
        //     ]);
        // }

        // Sync tasks
        $oldData = $project->tasks;
        $newData = $attributes['tasks'] ?? [];

        $partition = $this->updateRelations($oldData, $newData);
        // Delete removed tasks
        foreach ($partition['delete'] as $task) {
            $project->tasks()->where('id', $task['id'])->delete();
        }

        // Update existing tasks
        foreach ($partition['update'] as $task) {
            $project->tasks()->where('id', $task['id'])->update([
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'end_date' => $task['endDate'],
                'user_id' => $task['user'],
            ]);
        }
        // Create new tasks
        foreach ($partition['create'] as $task) {
            $project->tasks()->create([
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'end_date' => $task['endDate'],
                'user_id' => $task['user'],
            ]);
        }

        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // https://laracasts.com/discuss/channels/eloquent/update-create-and-delete-hasmany-relations-in-one-go
    private function updateRelations($oldData, $newData)
    {
        $oldIds = Arr::pluck($oldData, 'id');
        $newIds = Arr::pluck($newData, 'id');

        // groups
        $delete = collect($oldData)
            ->filter(function ($model) use ($newIds) {
                return !in_array($model["id"], $newIds);
            });

        $update = collect($newData)
            ->filter(function ($model) use ($oldIds) {
                return isset($model["id"]) && in_array($model["id"], $oldIds);
            });

        $create = collect($newData)
            ->filter(function ($model) {
                return !isset($model["id"]);
            });
        // return
        return compact('delete', 'update', 'create');
    }
}
