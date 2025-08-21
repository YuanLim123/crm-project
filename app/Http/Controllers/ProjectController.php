<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Enums\ProjectStatus;
use App\Http\Requests\ProjectPostRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('client')
            ->paginate(10)
            ->through(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'client' => $project->client,
                    'end_date' => $project->end_date,
                ];
            });

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
    public function store(ProjectPostRequest $request, ProjectService $projectService)
    {
        $projectService->store($request->validated());

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['client', 'user', 'tasks', 'tasks.user'])->findOrFail($id);

        $files = $project
            ->getMedia('attachments')
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->file_name,
                ];
            });

        return Inertia::render('Project/Show', [
            'project' => $project,
            'files' => $files,
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

        // retrive media files
        $attachments = $project->getMedia('attachments')
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->file_name,
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
            'attachments' => $attachments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectPostRequest $request, Project $project, ProjectService $projectService)
    {
        $project = $projectService->update($request->validated(), $project);

        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        
        return redirect()->route('projects.index');
    }

    public function downloadFile(string $id)
    {
        $media = Media::findOrFail($id);

        return response()->download($media->getPath(), $media->file_name);
    }

}
