<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JsonException;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test
     */
    public function a_user_can_view_their_tasks(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get(route('tasks.index'))
            ->assertSee($task->name);
    }

    /** @test
     * @throws JsonException
     */
    public function a_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->raw(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->post(route('tasks.store'), $task);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test
     * @throws JsonException
     */
    public function a_user_can_update_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->put(route('tasks.update', $task->id), [
                'name' => $updatedName = fake()->sentence,
                'priority' => $updatedPriority = Task::PRIORITIES[array_rand(Task::PRIORITIES)],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => $updatedName,
            'priority' => $updatedPriority
        ]);
    }

    /** @test
     * @throws JsonException
     */
    public function a_user_can_delete_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->delete(route('tasks.destroy', $task->id));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }

    /** @test */
    public function creating_a_task_requires_a_name(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->raw([
            'user_id' => $user->id,
            'name' => null
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('tasks.store'), $task);

        $response
            ->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function updating_a_task_requires_a_name(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('tasks.update', $task->id), [
                'name' => null,
                'priority' => $updatedPriority = Task::PRIORITIES[array_rand(Task::PRIORITIES)],
            ]);

        $response
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function creating_a_task_requires_a_priority(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->raw([
            'user_id' => $user->id,
            'priority' => null
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('tasks.store'), $task);

        $response
            ->assertSessionHasErrors('priority');

        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function updating_a_task_requires_a_priority(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('tasks.update', $task->id), [
                'name' => fake()->sentence,
                'priority' => null,
            ]);

        $response
            ->assertSessionHasErrors('priority');
    }

    /** @test */
    public function a_user_cannot_see_another_users_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1Task = Task::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this
            ->actingAs($user2)
            ->get(route('tasks.index'));

        $response
            ->assertDontSee($user1Task->name);
    }

    /** @test */
    public function a_user_cannot_update_another_users_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $user1Task = Task::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this
            ->actingAs($user2)
            ->put(route('tasks.update', $user1Task->id), [
                'name' => $updatedName = fake()->sentence,
                'priority' => $updatedPriority = Task::PRIORITIES[array_rand(Task::PRIORITIES)],
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'id' => $user1Task->id,
            'name' => $updatedName,
            'priority' => $updatedPriority
        ]);
    }

    /** @test */
    public function a_user_cannot_delete_another_users_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this
            ->actingAs($user2)
            ->get(route('tasks.index'));

        $response
            ->assertDontSee($task->name);
    }
}
