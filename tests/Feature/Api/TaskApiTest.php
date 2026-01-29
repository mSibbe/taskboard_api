<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskOverdueNotification;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;



    public function test_unauthenticated_user_cannot_access_api_endpoints()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'todo'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Task']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id
        ]);
    }

    public function test_user_can_get_all_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Task::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Task']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Updated Task'
        ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }

    public function test_user_sees_only_own_tasks()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Task::factory()->create(['user_id' => $user->id]);
        Task::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($user);
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_user_cannot_access_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($user);
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_project_has_many_tasks()
    {
        Sanctum::actingAs(User::factory()->create());

        $project = Project::factory()->create();
        Task::factory()->count(3)->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_overdue_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Task::factory()->create([
            'user_id' => $user->id,
            'deadline' => now()->subDay()
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'deadline' => now()->addDay()
        ]);

        $response = $this->getJson('/api/overdue');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_task_deadline_must_be_in_future()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/tasks', [
            'title' => 'Invalid Deadline',
            'description' => 'Test',
            'status' => 'todo',
            'deadline' => now()->subDay()->toDateTimeString()
        ]);

        $response->assertStatus(422);
    }

    public function test_overdue_task_triggers_notification()
    {
        Notification::fake();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'deadline' => now()->subDay()
        ]);

        $task->update(['title' => 'Trigger Event']);

        Notification::assertSentTo(
            [$user],
            TaskOverdueNotification::class
        );
    }
}
