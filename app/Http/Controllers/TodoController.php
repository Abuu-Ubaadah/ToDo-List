<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        return response()->json(Todo::where('user_id', Auth::id())->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'deadline' => 'required|date',
        ]);

        $todo = Todo::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
        ]);

        return response()->json($todo, 201);
    }

    public function show($id)
    {
        return response()->json(Todo::where('id', $id)->where('user_id', Auth::id())->firstOrFail());
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $todo->update($request->all());

        return response()->json($todo);
    }

    public function destroy($id)
    {
        Todo::where('id', $id)->where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Todo dihapus']);
    }


public function nearingDeadline()
{
    // Ambil tanggal sekarang
    $today = Carbon::now();

    // Ambil semua todo yang deadlinenya kurang dari 1 hari lagi
    $todos = Todo::where('deadline', '>=', $today)
                 ->where('deadline', '<=', $today->copy()->addDay())
                 ->orderBy('deadline', 'asc')
                 ->get();

    return response()->json($todos);
}

public function searchAndSortTodos(Request $request)
{
    $query = Todo::query();

    // 🔍 Filter Pencarian berdasarkan Judul Todo
    if ($request->has('search')) {
        $query->where('title', 'LIKE', '%' . $request->search . '%');
    }

    // 🔻 Sorting berdasarkan Prioritas (rendah, sedang, tinggi)
    if ($request->has('sort_priority')) {
        $query->orderBy('priority', $request->sort_priority == 'desc' ? 'desc' : 'asc');
    }

    // 🔻 Sorting berdasarkan Deadline
    if ($request->has('sort_deadline')) {
        $query->orderBy('deadline', $request->sort_deadline == 'desc' ? 'desc' : 'asc');
    }

    return response()->json($query->get());
}

}