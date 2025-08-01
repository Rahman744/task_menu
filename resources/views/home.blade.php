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
                                    </span> Tasks
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

                <form id="task-form" action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <input type="text" name="title" placeholder="Title" class="form-control bg-light mb-2" required>

                    <textarea name="description" cols="10" rows="4" class="form-control bg-light mb-2" placeholder="Description"></textarea>

                    <select name="list" class="form-select bg-light mb-2">
                        <option value="">-- Select --</option>
                        @foreach ($lists as $list)
                        <option value="{{ $list->name }}">{{ $list->name }}</option>
                        @endforeach
                    </select>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags:</label>
                        <div id="subtasks-container">
                            <input type="text" name="subtasks[]" class="form-control mb-1" placeholder="Task">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addSubtask()">+ Add tasks</button>
                    </div>

                    <div class="pt-4 d-flex gap-3">
                        <button type="submit" class="btn btn-warning fw-semibold" id="save-button">Save Task</button>
                    </div>
                </form>

                <!-- Delete form — initially hidden -->
                <form id="delete-form" method="POST" style="display: none;" onsubmit="return confirm('Delete?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border fw-semibold mt-3">Delete</button>
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
        function loadTaskDetails(taskId) {
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    // Обновить поля формы
                    document.querySelector('input[name="title"]').value = task.title ?? '';
                    document.querySelector('textarea[name="description"]').value = task.description ?? '';
                    document.querySelector('select[name="list"]').value = task.list ?? '';

                    // Обновить subtasks
                    const subtasksContainer = document.getElementById('subtasks-container');
                    subtasksContainer.innerHTML = '';
                    if (task.subtasks && task.subtasks.length) {
                        task.subtasks.forEach(subtask => {
                            const input = document.createElement('input');
                            input.type = 'text';
                            input.name = 'subtasks[]';
                            input.value = subtask.title;
                            input.classList.add('form-control', 'mb-1');
                            subtasksContainer.appendChild(input);
                        });
                    } else {
                        subtasksContainer.innerHTML = '<input type="text" name="subtasks[]" class="form-control mb-1" placeholder="Task 1">';
                    }

                    // Обновить form action для PUT
                    const form = document.querySelector('#task-form');
                    form.action = `/tasks/${task.id}`;

                    // Добавить или обновить _method hidden input
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    // Изменить кнопку на "Update Task"
                    const saveButton = document.getElementById('save-button');
                    saveButton.innerText = 'Update Task';

                    // Показать форму удаления
                    const deleteForm = document.querySelector('#delete-form');
                    deleteForm.action = `/tasks/${task.id}`;
                    deleteForm.style.display = 'block';
                })
                .catch(error => {
                    console.error('Ошибка при загрузке задачи:', error);
                });
        }

        function addSubtask() {
            const container = document.getElementById('subtasks-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subtasks[]';
            input.placeholder = 'Task';
            input.classList.add('form-control', 'mb-1');
            container.appendChild(input);
        }
    </script>


</body>

</html>