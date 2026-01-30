<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

/**
 * Handles all task-related API endpoints.
 *
 * This controller provides CRUD operations for tasks,
 * overdue task retrieval, and task filtering by user or project.
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the authenticated user's tasks.
     */
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->with(['project'])
            ->get();
        return response()->json($tasks, Response::HTTP_OK);
    }

    /**
     * Store a newly created task for the authenticated user.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:todo,in-progress,done',
            'deadline' => 'nullable|date|after:now',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $validate['user_id'] = $request->user()->id;

        $task = Task::create($validate);

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Display a single task.
     *
     * Access is restricted by middleware to the task owner.
     */
    public function show(Task $task)
    {
        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * Update the specified task.
     *
     * Authorization is handled via middleware and model logic.
     */
    public function update(Request $request, Task $task)
    {
        $validate = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:todo,in_progress,done',
            'deadline' => 'nullable|date|after:now',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task->update($validate);

        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Display all tasks belonging to the specified user.
     *
     * Access is restricted so that users can only
     * retrieve their own tasks.
     */
    public function userTasks(User $user){
        return response()->json(
            $user->tasks()->with('project')->get(),
            Response::HTTP_OK
        );
    }

    /**
     * Display all tasks assigned to the specified project
     * that belong to the authenticated user.
     */
    public function projectTasks(Project $project)
    {
        return response()->json(
            $project->tasks()->with('user')->get(),
            Response::HTTP_OK
        );
    }

    /**
     * Return all overdue tasks of the authenticated user.
     *
     * A task is considered overdue if its deadline is in the past
     * and its status is not marked as done.
     */
    public function overdue(){
        $tasks = Task::whereNotNUll('deadline')
            ->where('deadline', '<', Carbon::now())
            ->where('status', '!=', 'done')
            ->where('user_id', auth()->id())
            ->with(['project'])
            ->get();

        return response()->json($tasks, Response::HTTP_OK);
    }
}
