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
                    <div class="fw-bold h6 text-muted small">LISTS</div>
                    <ul class="list-unstyled" id="listsContainer">
                        @foreach($lists as $list)
                        <div class="d-flex justify-content-between align-items-center px-2 py-1 rounded-3 mb-1 position-relative list-item"
                            data-id="{{ $list->id }}"
                            oncontextmenu="showDeleteButton(event, {{ $list->id }})"
                            {{ request('list') === $list->name ? 'style=background-color:#f8f9fa; border:1px solid #dee2e6;' : '' }}>

                            <a href="{{ route('home', ['list' => $list->name]) }}"
                                class="text-decoration-none d-flex align-items-center text-dark flex-grow-1">
                                <div class="me-2" style="width: 14px; height: 14px; background-color: {{ $list->color }}; border-radius: 4px;"></div>
                                {{ $list->name }}
                            </a>

                            <span class="badge bg-light text-dark fw-semibold">{{ $list->tasks_count }}</span>

                            <!-- Delete button (скрытая по умолчанию) -->
                            <form method="POST" action="{{ route('lists.destroy', $list->id) }}"
                                class="delete-form position-absolute end-0 top-0 me-2 mt-1" style="display: none;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-close" aria-label="Delete"></button>
                            </form>
                        </div>
                        @endforeach
                    </ul>




                    <!-- + Add New List (показывает форму) -->
                    <button class="btn btn-sm text-primary p-0 mb-3" id="showListForm">+ Add New List</button>

                    <!-- Скрытая форма добавления списка -->
                    <form action="{{ route('lists.store') }}" method="POST" id="listForm" class="d-none">
                        @csrf
                        <input type="text" name="name" placeholder="List name" class="form-control form-control-sm mb-2">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </form>

                    <script>
                        document.getElementById('showListForm').addEventListener('click', function() {
                            document.getElementById('listForm').classList.remove('d-none');
                            this.classList.add('d-none');
                        });
                    </script>

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
                <h2 class="h4 mb-4">
                    {{ $selectedList ?? 'Today' }}
                    <span class="badge bg-secondary">{{ $tasks->count() }}</span>
                </h2>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-3 text-start">
                    + Add new list
                </a>

                @foreach($tasks as $task)
                <div class="task-item d-flex justify-content-between align-items-start py-3 border-bottom"
                    data-id="{{ $task->id }}"
                    onclick="loadTaskDetails({{ $task->id }})">


                    {{-- Левая часть: чекбокс и заголовок --}}
                    <div class="d-flex align-items-start">
                        <input
                            type="checkbox"
                            class="form-check-input mt-1 me-3"
                            {{ $task->is_done ? 'checked' : '' }}
                            onchange="toggleTaskStatus({{ $task->id }})">

                        <div>
                            <div class="fw-semibold">{{ $task->title }}</div>

                            <div class="d-flex align-items-center mt-1 text-muted small">

                                @if($task->due_date)
                                <span class="me-3 d-flex align-items-center">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}
                                </span>
                                @endif

                                <span class="me-3">
                                    <span class="badge bg-light text-dark border rounded-2">
                                        {{ $task->subtasks_count ?? 0 }}
                                    </span> Subtasks
                                </span>

                                @php
                                $list = \App\Models\TaskList::where('name', $task->list)->first();
                                @endphp

                                @if($list)
                                <span class="d-flex align-items-center">
                                    <div class="me-1" style="width: 12px; height: 12px; background-color: {{ $list->color }}; border-radius: 4px;"></div>
                                    {{ $list->name }}
                                </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- Стрелка справа --}}
                    <div>
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>
                @endforeach

            </div>


            <!-- Right: Task Form -->
            <div class="col-3 bg-light p-3 rounded-4">
                <h4 class="pb-2 fw-bold">Task:</h4>

                @php
                $editing = isset($task) && request()->has('task');
                @endphp

                <form id="task-form" action="{{ $editing ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                    @csrf
                    @if($editing)
                    @method('PUT')
                    @endif

                    <input type="text" name="title" placeholder="Title" class="form-control bg-light mb-2"
                        value="{{ $editing ? $task->title : '' }}" required>

                    <textarea name="description" cols="10" rows="4" class="form-control bg-light mb-2"
                        placeholder="Description">{{ $editing ? $task->description : '' }}</textarea>

                    <select name="list" class="form-select bg-light mb-2">
                        <option value="">-- Select --</option>
                        @foreach ($lists as $list)
                        <option value="{{ $list->name }}" {{ (isset($task) && $task->list === $list->name) ? 'selected' : '' }}>
                            {{ $list->name }}
                        </option>
                        @endforeach
                    </select>


                    <input type="date" name="due_date" class="form-control bg-light mb-2"
                        value="{{ $editing ? $task->due_date : '' }}">

                    <input type="text" name="tags" placeholder="Tags" class="form-control bg-light mb-3"
                        value="{{ $editing ? $task->tags : '' }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subtasks:</label>
                        <div id="subtasks-container">
                            @if($editing && $task->subtasks->count())
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
                            {{ $editing ? 'Update Task' : 'Save Task' }}
                        </button>
                    </div>
                </form>

                <form id="delete-form" method="POST" onsubmit="return confirm('Delete?');" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border fw-semibold" id="delete-button" style="display: none;">Delete</button>
                </form>
            </div>


        </div>
    </div>

    <script>
        function showDeleteButton(event, id) {
            event.preventDefault(); // отключить обычное меню
            document.querySelectorAll('.delete-form').forEach(f => f.style.display = 'none');
            const item = document.querySelector(`[data-id='${id}'] .delete-form`);
            if (item) item.style.display = 'block';
        }

        // Скрыть при клике вне
        document.addEventListener('click', function() {
            document.querySelectorAll('.delete-form').forEach(f => f.style.display = 'none');
        });
    </script>

    <script>
        function toggleTaskStatus(taskId) {
            fetch(`/tasks/${taskId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        alert("Ошибка при переключении статуса задачи");
                    }
                });
        }
    </script>

    <script>
        function loadTaskDetails(taskId) {
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    // Заполняем правую форму значениями
                    document.querySelector('input[name="title"]').value = task.title ?? '';
                    document.querySelector('textarea[name="description"]').value = task.description ?? '';
                    document.querySelector('input[name="due_date"]').value = task.due_date ?? '';
                    document.querySelector('select[name="list"]').value = task.list ?? '';

                    // Обновим форму: action и метод
                    const form = document.querySelector('#task-form');
                    form.action = `/tasks/${task.id}`;

                    // Добавляем метод PUT
                    let methodInput = document.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    // Показать кнопку удалить, если есть
                    const deleteBtn = document.querySelector('#delete-button');
                    if (deleteBtn) deleteBtn.style.display = 'inline-block';
                })
                .catch(error => {
                    console.error('Ошибка при загрузке задачи:', error);
                });
        }
    </script>

    <script>
        // Save (обновление)
        const form = document.querySelector('#task-form');
        form.action = `/tasks/${task.id}`;

        // Добавляем метод PUT (Laravel требует _method)
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        // Delete
        const deleteForm = document.querySelector('#delete-form');
        if (deleteForm) {
            deleteForm.action = `/tasks/${task.id}`;
            document.querySelector('#delete-button').style.display = 'inline-block';
        }   
    </script>

</body>

</html>