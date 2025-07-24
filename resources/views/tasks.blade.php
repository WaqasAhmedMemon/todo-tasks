<!DOCTYPE html>
<html>
<head>
    <title>Task Manager Web App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
</head>
<body class="p-5">
<div class="container">
    <h2>Task Manager Web App</h2>
    <form id="taskForm">
        <input type="text" name="title" class="form-control" placeholder="Task Title" required>
        <textarea name="description" class="form-control mt-2" placeholder="Description"></textarea>
        <button type="submit" class="btn btn-primary mt-2">Add Task</button>
    </form>

    <hr>
    <div id="taskList">
        @foreach ($tasks as $task)
            <div class="card mt-2 task-item" data-id="{{ $task->id }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $task->title }} 
                        <span class="badge bg-{{ $task->status === 'Done' ? 'success' : ($task->status === 'In Progress' ? 'warning' : 'secondary') }}">
                            {{ $task->status }}
                        </span>
                    </h5>
                    <p class="card-text">{{ $task->description }}</p>
                    <select class="form-select status-select">
                        <option {{ $task->status === 'To Do' ? 'selected' : '' }}>To Do</option>
                        <option {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option {{ $task->status === 'Done' ? 'selected' : '' }}>Done</option>
                    </select>
                    <button class="btn btn-danger mt-2 delete-btn">Delete</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const csrf = $('meta[name="csrf-token"]').attr('content');

    $('#taskForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/tasks',
            method: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function () { location.reload(); }
        });
    });

    $('.status-select').on('change', function () {
        const card = $(this).closest('.card');
        const id = card.data('id');
        const status = $(this).val();
        $.ajax({
            url: `/tasks/${id}`,
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrf },
            data: { status },
            success: function () { location.reload(); }
        });
    });

    $('.delete-btn').on('click', function () {
        const card = $(this).closest('.card');
        const id = card.data('id');
        $.ajax({
            url: `/tasks/${id}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf },
            success: function () { location.reload(); }
        });
    });

    function sortTasks() {
        const order = Array.from(document.querySelectorAll('.task-item')).map(el => el.dataset.id);
        fetch('/tasks/sort', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order })
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        new Sortable(document.getElementById('taskList'), {
            animation: 150,
            onEnd: sortTasks
        });
    });
</script>
</body>
</html>
