<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use App\Enums\ProjectStatus;


uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = createUser();
});

test('project page is displayed ', function () {

    $this->actingAs($this->user);

    $response = $this->get(route('projects.index'));

    $response->assertStatus(200);
});

test('project page contains created project data', function () {

    $this->actingAs($this->user);
    $project = createProject();

    $response = $this->get(route('projects.index'));

    $response->assertInertia(
        fn(Assert $page) =>
        $page->component('Project/Index')
            ->has('projects.data', 1)
            ->has(
                'projects.data.0',
                fn(Assert $page) => $page
                    ->where('id', $project->id)
                    ->where('title', $project->title)
                    ->etc()
            )
    );
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => $project->title,
    ]);
});

test('project edit contains correct values', function () {
    $this->actingAs($this->user);
    $project = createProject();

    $response = $this->get(route('projects.edit', $project));

    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Project/Edit')
            ->has(
                'project',
                fn(Assert $page) => $page
                    ->where('id', $project->id)
                    ->where('title', $project->title)
                    ->etc()
            )
    );
});

test('project can be updated', function () {
    $this->actingAs($this->user);
    $project = createProject();

    $response = $this->post(route('projects.update', $project), [
        'title' => 'Updated Project Title',
        'description' => 'Updated description',
        'status' => ProjectStatus::IN_PROGRESS->value,
        'endDate' => now()->addDays(10)->format('Y-m-d'),
        'client' => $project->client_id,
        'assignedUser' => $this->user->id,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('projects.show', $project));

    $this->assertDatabaseHas('projects', [
        'title' => 'Updated Project Title',
        'description' => 'Updated description',
    ]);
});

test('project update validation error redirects back', function () {
    $this->actingAs($this->user);
    $project = createProject();

    $response = $this->post(route('projects.update', $project), [
        'title' => '',
        'description' => '',
        'status' => '',
        'endDate' => '',
        'client' => '',
        'assignedUser' => '',
    ]);

    $response->assertStatus(302);
    $response->assertInvalid(['title', 'description', 'status', 'endDate', 'client', 'assignedUser']);
});


test('project can be deleted', function () {
    $this->actingAs($this->user);
    $project = createProject();

    $response = $this->delete(route('projects.destroy', $project));

    $response->assertStatus(302);
    $this->assertSoftDeleted('projects', [
        'id' => $project->id,
        'title' => $project->title,
    ]);
});
