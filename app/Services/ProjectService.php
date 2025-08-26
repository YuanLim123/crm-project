<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Helpers\PartitionHelper;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Enums\ProjectStatus;

class ProjectService
{
    public function store(array $attributes)
    {
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

        if (!empty($attributes['attachments'])) {
            foreach ($attributes['attachments'] as $attachment) {
                $project->addMedia($attachment)->toMediaCollection('attachments');
            }
        }
    }

    public function prepareDataForEdit(Project $project)
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
                'endDate' => $this->formatEndDate($task->end_date),
                'user' => $task->user,
            ];
        });

        // retrive media files as id and name only
        $attachments = $project->getMedia('attachments')
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->file_name,
                ];
            });

        return [
            'clients' => $clients,
            'users' => $users,
            'project' => $project,
            // since we change the accessor for end_date to d/m/Y, we need to format it back to Y-m-d
            // to show it in the form
            'endDate' => $this->formatEndDate($project->end_date),
            'attachments' => $attachments,
            'status' => ProjectStatus::toArray(),
        ];

    }

    public function update(array $attributes, Project $project): Project
    {
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

        // approach from laracast
        // Sync tasks
        $oldData = $project->tasks;
        $newData = $attributes['tasks'] ?? [];

        $partition = PartitionHelper::partition($oldData, $newData);

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

        // Handle current file deletions
        if (!empty($attributes['fileToDelete'])) {
            foreach ($attributes['fileToDelete'] as $fileId) {
                $media = $project->getMedia('attachments')->where('id', $fileId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Handle new file uploads
        if (!empty($attributes['attachments'])) {
            foreach ($attributes['attachments'] as $attachment) {
                $project->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $project->fresh();
    }

    private function formatEndDate(string $endDate)
    {
        return Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
    }

}
