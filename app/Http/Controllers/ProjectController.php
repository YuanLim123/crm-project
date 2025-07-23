<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;



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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $attributes = $request->validate([
            'title' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'endDate' => ['required', 'date'],
            'client' => ['required', 'exists:clients,id'],
            'assignedUser' => ['required', 'exists:users,id'],
            'tasks' => ['array'],
            'tasks.*.title' => ['required', 'string', 'max:128'],
            'tasks.*.description' => ['required', 'string', 'max:255'],
            'tasks.*.status' => ['required', 'in:pending,in_progress,completed'],
            'tasks.*.endDate' => ['required', 'date'],
            'tasks.*.assignedUser' => ['required', 'exists:users,id'],
        ]);

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
                    'user_id' => $task['assignedUser'],
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

        $selectedClient = $project->client;
        $selectedUser = $project->user;
        $selectedTasks = $project->tasks;

        return Inertia::render('Project/Edit', [
            'clients' => $clients,
            'users' => $users,
            'project' => $project,
            'selectedClient' => $selectedClient,
            'selectedUser' => $selectedUser,
            'selectedTasks' => $selectedTasks,
            'endDate' => date('Y-m-d', strtotime($project->end_date)),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        dd($request->all());
        
        $attributes = $request->validate([
            'title' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'endDate' => ['required', 'date'],
            'client' => ['required', 'exists:clients,id'],
            'assignedUser' => ['required', 'exists:users,id'],
            'tasks' => ['array'],
            'tasks.*.title' => ['required', 'string', 'max:128'],
            'tasks.*.description' => ['required', 'string', 'max:255'],
            'tasks.*.status' => ['required', 'in:pending,in_progress,completed'],
            'tasks.*.endDate' => ['required', 'date'],
            'tasks.*.assignedUser' => ['required', 'exists:users,id'],
        ]);

        $project->update([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'client_id' => $attributes['client'],
            'user_id' => $attributes['assignedUser'],
        ]);

        // Delete all old tasks
        $project->tasks()->delete();

        // Create new tasks
        foreach ($attributes['tasks'] as $task) {
            $project->tasks()->create([
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'end_date' => $task['endDate'],
                'user_id' => $task['assignedUser'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function updateRelations($oldData, $newData)
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
