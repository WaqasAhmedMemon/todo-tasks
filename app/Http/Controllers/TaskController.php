<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index() {
        $tasks = Task::orderBy('order')->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request) {
        Task::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id) {
        $task = Task::find($id);
        $task->update($request->only('status'));
        return response()->json(['success' => true]);
    }

    public function destroy($id) {
        Task::destroy($id);
        return response()->json(['success' => true]);
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Task::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}