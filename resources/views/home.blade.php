<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/icons/bootstrap-icons.min.css') }}">
    <style>
        .task-link:hover {
            background-color: #e2e3e5;
        }
    </style>
</head>

<body>
<div class="container-xl py-5">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-3 bg-light p-3 rounded-4">
            <h4 class="pb-2 fw-bold d-flex justify-content-between">Menu<i class="bi bi-list"></i></h4>
            <input class="form-control bg-light" type="text" placeholder="Search" aria-label="Search">

            <div>
                <div class="fw-bolder pt-4 small text-muted">TASKS</div>
                <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-chevron-double-right mx-2"></i>Calendar</a></div>
                <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-list-ul mx-2"></i>Calendar</a></div>
                <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-calendar-week-fill mx-2"></i>Calendar</a></div>
                <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-sticky-fill mx-2"></i>Calendar</a></div>
            </div>

            <hr>

            <div>
                <div class="fw-bolder small text-muted">LISTS</div>
                <div class="task-link py-2 rounded-3 d-flex justify-content-between">
                    <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-square-fill text-danger mx-2"></i>Personal</a>
                    <i class="bi bi-3-square text-secondary pe-2"></i>
                </div>
                <div class="task-link py-2 rounded-3 d-flex justify-content-between">
                    <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-square-fill text-info mx-2"></i>Personal</a>
                    <i class="bi bi-6-square text-secondary pe-2"></i>
                </div>
                <div class="task-link py-2 rounded-3 d-flex justify-content-between">
                    <a href="#" class="text-secondary text-decoration-none"><i class="bi bi-square-fill text-warning mx-2"></i>Personal</a>
                    <i class="bi bi-3-square text-secondary pe-2"></i>
                </div>
                <a href="{{ route('home') }}" class="text-muted ps-2 text-decoration-none"><span class="h4">+</span> Add New List</a>
            </div>

            <hr>

            <div class="pb-5 mb-5">
                <div class="fw-bolder small text-muted mb-2">TAGS</div>
                <div class="d-flex gap-2">
                    <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" class="text-dark text-decoration-none">Tag 1</a></div>
                    <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" class="text-dark text-decoration-none">Tag 2</a></div>
                    <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" class="text-dark text-decoration-none">+ Add Tag</a></div>
                </div>
            </div>

            <div class="task-link py-2 rounded-3 h6"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-sliders2 mx-2"></i>Settings</a></div>
            <div class="task-link py-2 rounded-3 h6"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-box-arrow-right mx-2"></i>Sign out</a></div>
        </div>

        <!-- Center: Task List -->
        <div class="col-6 ps-4 pe-4">
            <div class="d-flex align-items-center mb-3">
                <h1 class="me-5 fw-bold">Today</h1>
                <span class="h3 border rounded px-2 py-1 fw-semibold">{{ $tasks->count() }}</span>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-3 text-start">
                <span class="h4">+</span> Add New List
            </a>

            @foreach ($tasks as $task)
                <a href="{{ route('home', ['task' => $task->id]) }}" class="text-decoration-none">
                    <div class="bg-light rounded px-3 py-2 mb-2 task-link">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="form-check-input me-2">
                                <span class="fw-semibold text-dark">{{ $task->title }}</span>
                            </div>
                            <i class="bi bi-chevron-right text-dark"></i>
                        </div>

                        <div class="d-flex gap-4 align-items-center mt-2 ps-4 text-muted small">
                            @if ($task->due_date)
                                <div><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('d-m-y') }}</div>
                            @endif

                            <div><i class="bi bi-list-task me-1"></i> {{ $task->subtasks->count() }} Subtasks</div>

                            @if ($task->list)
                                <div><i class="bi bi-square-fill me-1" style="color:#f66;"></i> {{ $task->list }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach



        </div>


        <!-- Right: Task Form -->
        <div class="col-3 bg-light p-3 rounded-4">
            <h4 class="pb-2 fw-bold">Task:</h4>

            <!-- Форма сохранения или обновления -->
            <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                @csrf
                @if(isset($task)) @method('PUT') @endif

                <!-- Название задачи -->
                <input type="text" name="title" placeholder="Title" class="form-control bg-light mb-2"
                       value="{{ old('title', $task->title ?? '') }}" required>

                <!-- Описание задачи -->
                <textarea name="description" cols="10" rows="4" class="form-control bg-light mb-2"
                          placeholder="Description">{{ old('description', $task->description ?? '') }}</textarea>

                <!-- Список -->
                <select name="list" class="form-select bg-light mb-2">
                    <option value="">-- Select --</option>
                    <option {{ (old('list', $task->list ?? '') == 'Personal') ? 'selected' : '' }}>Personal</option>
                    <option {{ (old('list', $task->list ?? '') == 'Developer') ? 'selected' : '' }}>Developer</option>
                    <option {{ (old('list', $task->list ?? '') == 'Buhgalter') ? 'selected' : '' }}>Buhgalter</option>
                </select>

                <!-- Дата -->
                <input type="date" name="due_date" class="form-control bg-light mb-2"
                       value="{{ old('due_date', $task->due_date ?? '') }}">

                <!-- Теги -->
                <input type="text" name="tags" placeholder="Tags" class="form-control bg-light mb-3"
                       value="{{ old('tags', $task->tags ?? '') }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Subtasks:</label>
                    <div id="subtasks-container">
                        @if(isset($task) && $task->subtasks)
                            @foreach ($task->subtasks as $subtask)
                                <input type="text" name="subtasks[]" class="form-control mb-1" value="{{ $subtask->title }}">
                            @endforeach
                        @else
                            <input type="text" name="subtasks[]" class="form-control mb-1" placeholder="Subtask 1">
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addSubtask()">+ Add Subtask</button>
                </div>

                <script>
                    function addSubtask() {
                        const container = document.getElementById('subtasks-container');
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'subtasks[]';
                        input.placeholder = 'Subtask';
                        input.classList.add('form-control', 'mb-1');
                        container.appendChild(input);
                    }
                </script>



                <div class="pt-4 d-flex gap-3">
                    <button type="submit" class="btn btn-warning fw-semibold">
                        {{ isset($task) ? 'Update Task' : 'Save Task' }}
                    </button>
                </div>
            </form>

            <!-- Форма удаления отдельно -->
            @if(isset($task))
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Delete?');" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border fw-semibold">Delete</button>
                </form>
            @endif
        </div>


    </div>
</div>
</body>

</html>
