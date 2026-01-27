<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'todo'
        ]);
        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Task']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task'
        ]);
    }

    public function test_user_can_get_all_tasks()
    {
        Sanctum::actingAs(User::factory()->create());

        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_update_task()
    {
        Sanctum::actingAs(User::factory()->create());

        $task = Task::factory()->create();

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
        Sanctum::actingAs(User::factory()->create());

        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
