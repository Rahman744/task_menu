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
                    <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-chevron-double-right mx-2"></i>Upcoming</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-list-ul mx-2"></i>Today</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-calendar-week-fill mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-sticky-fill mx-2"></i>Sticki Wall</a></div>
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
                    <div class="d-flex flex-wrap gap-2" id="tags-list">
                        @foreach ($tags as $tag)
                        <div class="d-flex">
                            <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small tag-item">
                                <a href="{{ route('home', ['tag' => $tag->title]) }}" class="text-dark text-decoration-none">
                                    {{ $tag->title }}
                                </a>
                            </div>
                            <div>
                                <form action="{{ route('tags.destroy', $tag) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm bg-body-secondary text-danger">X</button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small" id="add-tag-wrapper">
                            <a href="#" class="text-dark text-decoration-none" id="add-tag-btn">+ Add Tag</a>
                        </div>
                        <form action="{{ route('tags.store') }}" method="post">
                            @csrf
                            <div class="d-flex align-items-center d-none" id="tag-input">
                                <div class="me-2">
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="task-link py-2 rounded-3 h6"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-sliders2 mx-2"></i>Settings</a></div>
                <div class="task-link py-2 rounded-3 h6"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-box-arrow-right mx-2"></i>Sign out</a></div>
            </div>

            <!-- Center: Task List -->
            <div class="col-6 ps-4 pe-4">
                <h2 class="h4 mb-4">
                    {{ $selectedTag ?? ($selectedList ?? 'Today') }}
                    <span class="badge bg-secondary">{{ $tasks->count() }}</span>
                </h2>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-3 text-start">
                    + Add New Task
                </a>

                @foreach($tasks as $task)
                <div class="task-item d-flex justify-content-between align-items-start py-3 border-bottom"
                    data-task-id="{{ $task->id }}">
                    {{-- Левая часть: чекбокс и заголовок --}}
                    <div class="d-flex align-items-start">
                        <input
                            type="checkbox"
                            class="form-check-input mt-1 me-3"
                            {{ $task->is_done ? 'checked' : '' }}
                            onclick="event.stopPropagation(); toggleTaskStatus({{ $task->id }}, this.checked)">

                        <div>
                            <div class="fw-semibold">{{ $task->title }}</div>

                            <div class="d-flex align-items-center mt-1 text-muted small">

                                @if($task->due_date)
                                <span class="me-3 d-flex align-items-center">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}
                                </span>
                                @endif

                                @php
                                $tagsArray = $task->tags ? json_decode($task->tags, true) : []; // Декодируем JSON
                                $tagsCount = count($tagsArray);
                                @endphp

                                <span class="me-3">
                                    <span class="badge bg-light text-dark border rounded-2">{{ $tagsCount }}</span>
                                    {{ $tagsCount === 1 ? 'Tag' : 'Tags' }}
                                    @if($tagsCount > 0)

                                    @endif
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

                    <input type="text" name="title" placeholder="Renew driver's license" class="form-control bg-light mb-2" required>

                    <textarea name="description" cols="10" rows="4" class="form-control bg-light mb-2" placeholder="Description"></textarea>

                    <label for="due_date" class="form-label fw-semibold">Lists:</label>
                    <select name="list" class="form-select bg-light mb-2">
                        <option value="">-- Select --</option>
                        @foreach ($lists as $list)
                        <option value="{{ $list->name }}">{{ $list->name }}</option>
                        @endforeach
                    </select>

                    <label for="due_date" class="form-label fw-semibold">Due date:</label>
                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        class="form-control bg-light mb-2"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                        required>

                    <div id="subtasks-container"></div>

                    <label for="due_date" class="form-label fw-semibold">Tags:</label>
                    <div id="tags-container" class="d-flex flex-wrap gap-2 mb-2">
                        @foreach ($tags as $tag)
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small tag-item">
                            <input type="checkbox" name="tags[]" value="{{ $tag->title }}" class="me-2">
                            <span>{{ $tag->title }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-4 d-flex gap-3">
                        <button type="submit" class="btn btn-warning fw-semibold" id="save-button">Save Task</button>
                    </div>
                </form>

                <!-- Delete form — initially hidden -->
                <form id="delete-form" method="POST" style="display: none;" onsubmit="return confirm('Delete?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border fw-semibold mt-3">Delete Task</button>
                </form>
            </div>

        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // --- Централизованный код (только один набор функций) ---
        (function() {
            // безопасное экранирование для value
            function escapeHtml(text) {
                return String(text || '').replace(/[&<>"'\/]/g, function(s) {
                    const map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;',
                        '/': '&#x2F;'
                    };
                    return map[s];
                });
            }

            // Удалили старую функцию добавления инпутов тегов,
            // чтобы старые Tag 1, Tag 2 больше не появлялись
            window.addTagInput = function() {
                // Пустая функция, чтобы старые вызовы addTagInput() не ломали код
            };


            // оставил helper для обратной совместимости (если где-то вызывают removeTagInput)
            window.removeTagInput = function(btn) {
                const group = btn.closest('.tag-input-group');
                if (!group) return;
                group.remove();
                updateTagLabels();
            };

            // --- CSRF ---
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const CSRF = csrfMeta ? csrfMeta.getAttribute('content') : '';

            // --- создание элемента тега в левой панели (DOM) ---
            function createTagElementInLeftPanel(tag) {
                const wrapper = document.getElementById('add-tag-wrapper');
                if (!wrapper) return;
                const div = document.createElement('div');
                div.className = 'px-3 py-1 bg-body-secondary rounded-3 fw-semibold small tag-item';
                div.dataset.id = tag.id;
                const a = document.createElement('a');
                a.href = '?tag=' + encodeURIComponent(tag.title);
                a.className = 'text-dark text-decoration-none';
                a.textContent = tag.title;
                div.appendChild(a);
                wrapper.parentNode.insertBefore(div, wrapper);
            }

            // --- загрузка деталей задачи и заполнение формы (используется при клике на задачу) ---
            window.loadTaskDetails = async function(taskId) {
                try {
                    const res = await fetch(`/tasks/${taskId}`);
                    if (!res.ok) throw new Error('Fetch error ' + res.status);
                    const task = await res.json();

                    const form = document.getElementById('task-form');
                    if (!form) return;

                    // поля
                    form.querySelector('input[name="title"]').value = task.title ?? '';
                    form.querySelector('textarea[name="description"]').value = task.description ?? '';
                    const selectList = form.querySelector('select[name="list"]');
                    if (selectList) selectList.value = task.list ?? '';
                    const dueDateInput = form.querySelector('input[name="due_date"]');
                    if (dueDateInput) dueDateInput.value = task.due_date ?? '';

                    // теги: поддерживаем разные форматы (array, строка csv, или старые subtasks)
                    const container = document.getElementById('subtasks-container');
                    container.innerHTML = '';

                    if (task.tags && Array.isArray(task.tags) && task.tags.length) {
                        task.tags.forEach(t => addTagInput(t.title || t));
                    } else if (task.tags && typeof task.tags === 'string' && task.tags.trim()) {
                        task.tags.split(',').map(s => s.trim()).filter(Boolean).forEach(v => addTagInput(v));
                    } else if (task.subtasks && Array.isArray(task.subtasks) && task.subtasks.length) {
                        task.subtasks.forEach(st => addTagInput(st.title || st));
                    } else {
                        // Удаляем это, чтобы не добавлять пустой тег автоматически
                        // addTagInput();
                    }

                    // обновляем action формы и method для update
                    form.action = `/tasks/${task.id}`;
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    // меняем текст кнопки
                    const saveButton = document.getElementById('save-button');
                    if (saveButton) saveButton.innerText = 'Save changes';

                    // показываем delete-form и настраиваем action (если есть)
                    const deleteForm = document.getElementById('delete-form');
                    if (deleteForm) {
                        deleteForm.style.display = 'block';
                        deleteForm.action = `/tasks/${task.id}`;
                    }
                } catch (err) {
                    console.error('Ошибка загрузки задачи:', err);
                }
            };

            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form || form.id !== 'task-form') return;
                // Берем все отмеченные чекбоксы из #tags-container
                const checkedTags = Array.from(form.querySelectorAll('#tags-container input[type="checkbox"]:checked'))
                    .map(input => input.value.trim())
                    .filter(Boolean);
                // Создаём/обновляем скрытое поле name="tags" (comma separated)
                let hidden = form.querySelector('input[name="tags"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'tags';
                    form.appendChild(hidden);
                }
                hidden.value = checkedTags.join(',');
                // Форма отправится дальше нативно
            });

            // --- toggle task done ---
            window.toggleTaskStatus = async function(taskId, isDone) {
                try {
                    await fetch(`/tasks/${taskId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            is_done: isDone ? 1 : 0
                        })
                    });
                } catch (err) {
                    console.error('toggle error', err);
                }
            };

            // DOM ready listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Click on task (delegation) — загрузка деталей
                document.addEventListener('click', function(e) {
                    if (e.target.closest('input[type="checkbox"]')) return;
                    const taskItem = e.target.closest('.task-item');
                    if (!taskItem) return;
                    const taskId = taskItem.dataset.taskId || taskItem.dataset.id;
                    if (!taskId) return;
                    if (typeof window.loadTaskDetails === 'function') {
                        window.loadTaskDetails(taskId);
                    } else {
                        console.warn('loadTaskDetails not defined');
                    }
                });
            });

        })();

        let addTagBtn = document.getElementById('add-tag-btn');
        let tagInput = document.getElementById('tag-input');
        addTagBtn.addEventListener('click', function() {
            tagInput.classList.remove('d-none');
        });

        document.querySelector('#tag-input form').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Добавляем тег в список слева
                        createTagElementInLeftPanel({
                            id: data.id,
                            title: data.title
                        });
                        // Добавляем тег в выпадающий список справа
                        let select = document.getElementById('tags-select');
                        let option = document.createElement('option');
                        option.value = data.title;
                        option.text = data.title;
                        select.appendChild(option);
                        // Скрываем форму после добавления
                        tagInput.classList.add('d-none');
                        this.querySelector('input[name="title"]').value = '';
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });
    </script>







</body>

</html>