<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .task.todo { background: #f8d7da; }
        .task.in_progress { background: #fff3cd; }
        .task.done { background: #d4edda; }
        .task { padding: 10px; margin-bottom: 10px; border-radius: 4px; cursor: move; }
    </style>
</head>
<body class="container py-4">
    <h2>Task Manager</h2>

    <form action="/tasks" method="POST" class="mb-4">
        @csrf
        <input type="text" name="title" placeholder="Task title" class="form-control mb-2" required>
        <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
        <select name="status" class="form-control mb-2">
            <option value="todo">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <button class="btn btn-primary">Add Task</button>
    </form>

    <div id="task-list">
        @foreach($tasks as $task)
        <div class="task {{ $task->status }}" data-id="{{ $task->id }}">
            <strong>{{ $task->title }}</strong>
            <div>{{ $task->description }}</div>
            <form method="POST" action="/tasks/{{ $task->id }}" class="d-inline">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger btn-sm mt-2">Delete</button>
            </form>
        </div>
        @endforeach
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#task-list').sortable({
            update: function () {
                let order = [];
                $('.task').each(function () {
                    order.push($(this).data('id'));
                });
                $.post('/tasks/sort', {
                    order: order,
                    _token: '{{ csrf_token() }}'
                });
            }
        });
    </script>
</body>
</html>
