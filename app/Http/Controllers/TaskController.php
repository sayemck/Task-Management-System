<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('priority', 'ASC')->get();
        $projects = Project::where('status', 'active')->orderBy('id', 'ASC')->get();
        return view('tasks.index', compact('tasks', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', 'active')->orderBy('id', 'ASC')->get();
        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|numeric',
            'priority' => 'required|numeric',
        ]);

        $task = new Task();
        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->priority = $request->priority;
        $task->status = 'active';
        $task->created_at = date('Y-m-d H:i:s');

        if ($task->save()) {
            Alert::success('Success', 'Task created successfully');
            return redirect()->route('task.index');
        } else {
            Alert::error('Failed', 'failed');
            return redirect()->route('task.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        $projectId = $request->project_id ?? 0;

        $tasks = Task::select(
            'id',
            'project_id',
            'name',
            'priority',
            'status',
            'created_at',
        )
            ->when($projectId, function ($query, $projectId) {
                $query->where('tasks.project_id', $projectId);
            })
            ->get();
        return view('tasks.view', compact('tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        if (empty($task)) {
            abort(403, 'Not found');
        }
        $projects = Project::where('status', 'active')->orderBy('id', 'ASC')->get();
        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|numeric',
            'priority' => 'required|numeric',
            'status' => 'required',
        ]);

        if (empty($task)) {
            abort(403, 'Not found');
        }

        $task->name = $request->name;
        $task->project_id = $request->project_id;
        $task->priority = $request->priority;
        $task->status = $request->status;
        $task->updated_at = date('Y-m-d H:i:s');

        if ($task->save()) {
            Alert::success('Success', 'Task Update successfully');
            return redirect()->route('task.index');
        } else {
            Alert::error('Failed', 'failed');
            return redirect()->route('task.index');
        }
    }


    /**
     * sortable the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function sortable(Request $request)
    {
        foreach ($request->priority as $key => $order) {
            Task::find($order['id'])->update(['priority' => $order['position']]);
        }
        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        if ($task) {
            toast('Task deleted successfully', 'info');
            return redirect()->route('task.index');
        } else {
            Alert::error('Failed', 'failed');
            return redirect()->route('task.index');
        }
    }
}
