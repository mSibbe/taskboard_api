<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Task::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function userTasks(User $user){
        return response()->json(
            $user->tasks()->with('project')->get(),
            Response::HTTP_OK
        );
    }

    public function projectTasks(Project $project)
    {
        return response()->json(
            $project->tasks()->with('user')->get(),
            Response::HTTP_OK
        );
    }

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
