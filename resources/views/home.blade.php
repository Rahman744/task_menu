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
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small tag-item"
                            data-id="{{ $tag->id }}">
                            <a href="{{ route('home', ['tag' => $tag->title]) }}" class="text-dark text-decoration-none">
                                {{ $tag->title }}
                            </a>
                        </div>
                        @endforeach

                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small" id="add-tag-wrapper">
                            <a href="#" class="text-dark text-decoration-none" id="add-tag-btn">+ Add Tag</a>
                        </div>
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
                                $tagsArray = $task->tags_array ?? [];
                                $tagsCount = count($tagsArray);
                                @endphp

                                <span class="me-3">
                                    <span class="badge bg-light text-dark border rounded-2">{{ $tagsCount }}</span>
                                    {{ $tagsCount === 1 ? 'Tag' : 'Tags' }}
                                    @if($tagsCount > 0)
                                    <div class="small text-muted mt-1">
                                        {{ implode(', ', $tagsArray) }}
                                    </div>
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

                    <div id="subtasks-container">
                        <div class="input-group mb-1 tag-input-group">
                            <span class="input-group-text">Tag 1</span>
                            <input type="text" name="tags[]" class="form-control" placeholder="Tag name">
                            <button type="button" class="btn btn-outline-danger" onclick="removeTagInput(this)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addTagInput()">+ Add tag</button>


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
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // --- Add Tag (левое меню): отправляем POST /tags, сервер создаст Tag N
            const addBtn = document.getElementById('add-tag-btn');
            if (addBtn) {
                addBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const res = await fetch('{{ route("tags.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({})
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data.success) {
                        const wrapper = document.getElementById('add-tag-wrapper');
                        const div = document.createElement('div');
                        div.className = 'px-3 py-1 bg-body-secondary rounded-3 fw-semibold small tag-item';
                        div.dataset.id = data.tag.id;
                        const a = document.createElement('a');
                        a.href = '?tag=' + encodeURIComponent(data.tag.title);
                        a.className = 'text-dark text-decoration-none';
                        a.textContent = data.tag.title;
                        div.appendChild(a);
                        wrapper.before(div);
                    }
                });
            }

            // --- Delete tag by right-click (context menu) on left tags-list
            const tagsList = document.getElementById('tags-list');
            if (tagsList) {
                tagsList.addEventListener('contextmenu', async function(e) {
                    const el = e.target.closest('.tag-item');
                    if (!el) return;
                    e.preventDefault();
                    if (!confirm('Удалить тег?')) return;
                    const id = el.dataset.id;
                    const res = await fetch('/tags/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (data.success) el.remove();
                });
            }

            // --- Навешиваем клик на .task-item через делегирование или прямой навес
            document.querySelectorAll('.task-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('input[type="checkbox"]')) return; // если чекбокс — не открываем
                    const id = this.getAttribute('data-task-id') || this.dataset.taskId || this.getAttribute('data-id');
                    if (id && typeof loadTaskDetails === 'function') {
                        loadTaskDetails(id);
                    }
                });
            });

        });

        // --- Правая панель: добавление/удаление input'ов тегов + переиндексация "Tag N"
        function addTagInput(value = '') {
            const container = document.getElementById('subtasks-container');
            const count = container.querySelectorAll('.tag-input-group').length + 1;
            const div = document.createElement('div');
            div.className = 'input-group mb-1 tag-input-group';
            div.innerHTML = `
        <span class="input-group-text">Tag ${count}</span>
        <input type="text" name="tags[]" class="form-control" placeholder="Tag name" value="${value ? escapeHtml(value) : ''}">
        <button type="button" class="btn btn-outline-danger" onclick="removeTagInput(this)">✕</button>
    `;
            container.appendChild(div);
        }

        function removeTagInput(button) {
            const group = button.closest('.tag-input-group');
            if (!group) return;
            group.remove();
            // переиндексация
            const labels = document.querySelectorAll('#subtasks-container .tag-input-group .input-group-text');
            labels.forEach((el, idx) => el.textContent = 'Tag ' + (idx + 1));
        }

        function escapeHtml(text) {
            return String(text).replace(/[&<>"'\/]/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;'
                })[s];
            });
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Навешиваем обработчик на каждый .task-item
            document.querySelectorAll('.task-item').forEach(function(item) {
                item.addEventListener('click', function(e) {
                    // Если клик по чекбоксу — пропускаем
                    if (e.target.closest('input[type="checkbox"]')) return;

                    const id = this.dataset.taskId; // теперь читаем data-task-id
                    if (id && typeof loadTaskDetails === 'function') {
                        loadTaskDetails(id);
                    }
                });
            });
        });
    </script>


    <script>
        // экранирование для value полей
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return String(text)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');
        }

        // добавляет input для тега в правой панели
        function addTagInput(value = '') {
            const container = document.getElementById('subtasks-container');
            const count = container.querySelectorAll('.tag-input-group').length + 1;

            const div = document.createElement('div');
            div.className = 'input-group mb-1 tag-input-group';

            div.innerHTML = `
            <span class="input-group-text">Tag ${count}</span>
            <input type="text" name="tags[]" class="form-control" placeholder="Tag name" value="${escapeHtml(value)}">
            <button type="button" class="btn btn-outline-danger" onclick="removeTagInput(this)">✕</button>
        `;

            container.appendChild(div);
        }

        function removeTagInput(button) {
            const group = button.closest('.tag-input-group');
            if (!group) return;
            group.remove();
            // переиндексация номера Tag N
            const labels = document.querySelectorAll('#subtasks-container .tag-input-group .input-group-text');
            labels.forEach((el, idx) => el.textContent = 'Tag ' + (idx + 1));
        }

        function loadTaskDetails(taskId) {
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    const form = document.querySelector('#task-form');

                    document.querySelector('input[name="title"]').value = task.title ?? '';
                    document.querySelector('textarea[name="description"]').value = task.description ?? '';
                    document.querySelector('select[name="list"]').value = task.list ?? '';

                    const dueDateInput = document.querySelector('input[name="due_date"]');
                    if (dueDateInput) dueDateInput.value = task.due_date ?? '';

                    // заполняем правую панель тегами (используем tags_array)
                    const container = document.getElementById('subtasks-container');
                    container.innerHTML = '';
                    const tags = task.tags_array ?? [];
                    if (Array.isArray(tags) && tags.length) {
                        tags.forEach(t => addTagInput(t));
                    } else {
                        addTagInput(''); // пустой инпут если нет тегов
                    }

                    // подготовка формы для PUT
                    form.action = `/tasks/${task.id}`;
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    const saveButton = document.getElementById('save-button');
                    saveButton.innerText = 'Save changes';

                    const deleteForm = document.querySelector('#delete-form');
                    deleteForm.action = `/tasks/${task.id}`;
                    deleteForm.style.display = 'block';
                })
                .catch(error => {
                    console.error('Ошибка при загрузке задачи:', error);
                });
        }
    </script>



    <script>
        (function() {
            // --- утилиты ---
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

            function updateTagLabels() {
                const labels = document.querySelectorAll('#subtasks-container .tag-input-group .input-group-text');
                labels.forEach((el, idx) => el.textContent = 'Tag ' + (idx + 1));
            }

            // --- add/remove tag inputs (right panel) ---
            window.addTagInput = function(value = '') {
                const container = document.getElementById('subtasks-container');
                if (!container) return;
                const count = container.querySelectorAll('.tag-input-group').length + 1;
                const div = document.createElement('div');
                div.className = 'input-group mb-1 tag-input-group';
                div.innerHTML = `
      <span class="input-group-text">Tag ${count}</span>
      <input type="text" class="form-control tag-input" placeholder="Tag name" value="${escapeHtml(value)}">
      <button type="button" class="btn btn-outline-danger" aria-label="Remove tag">✕</button>
    `;
                container.appendChild(div);

                // назначаем обработчик удаления для этой кнопки
                div.querySelector('button').addEventListener('click', function() {
                    div.remove();
                    updateTagLabels();
                });
            };

            window.removeTagInput = function(btn) { // совместимость, если где-то используется
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
                        // если сервер вернул строку "t1, t2"
                        const arr = task.tags.split(',').map(s => s.trim()).filter(Boolean);
                        if (arr.length) arr.forEach(v => addTagInput(v));
                        else addTagInput();
                    } else if (task.subtasks && Array.isArray(task.subtasks) && task.subtasks.length) {
                        // совместимость со старым полем subtasks
                        task.subtasks.forEach(st => addTagInput(st.title || st));
                    } else {
                        addTagInput();
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

            // --- сериализация тегов перед отправкой формы (в единое поле "tags") ---
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form || form.id !== 'task-form') return;
                // берем все текстовые tag-input
                const inputs = Array.from(form.querySelectorAll('#subtasks-container .tag-input'));
                const values = inputs.map(i => i.value.trim()).filter(Boolean);
                // создаём/обновляем скрытое поле name="tags" (comma separated)
                let hidden = form.querySelector('input[name="tags"]');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'tags';
                    form.appendChild(hidden);
                }
                hidden.value = values.join(',');
                // форма отправится дальше нативно
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

            // --- события DOMContentLoaded: один блок (всё навешиваем здесь) ---
            document.addEventListener('DOMContentLoaded', function() {
                // 1) Add Tag (left panel) — один слушатель
                const addTagBtn = document.getElementById('add-tag-btn');
                if (addTagBtn) {
                    addTagBtn.addEventListener('click', async function(ev) {
                        ev.preventDefault();
                        if (addTagBtn.dataset.loading) return; // защита от двойных кликов
                        addTagBtn.dataset.loading = '1';
                        try {
                            const res = await fetch('{{ route("tags.store") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': CSRF
                                },
                                body: JSON.stringify({})
                            });
                            const data = await res.json();
                            if (data && data.success && data.tag) {
                                createTagElementInLeftPanel(data.tag);
                            } else {
                                console.warn('Unexpected response for tag creation', data);
                            }
                        } catch (err) {
                            console.error('Error creating tag', err);
                        } finally {
                            delete addTagBtn.dataset.loading;
                        }
                    });
                }

                // 2) Delete Tag (right-click) — делегируем на контейнер tags-list
                const tagsList = document.getElementById('tags-list');
                if (tagsList) {
                    tagsList.addEventListener('contextmenu', async function(e) {
                        const el = e.target.closest('.tag-item');
                        if (!el) return;
                        e.preventDefault();
                        if (!confirm('Удалить тег?')) return;
                        const id = el.dataset.id;
                        if (!id) return;
                        try {
                            const res = await fetch(`/tags/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': CSRF
                                }
                            });
                            const data = await res.json();
                            if (data && data.success) el.remove();
                        } catch (err) {
                            console.error('Error deleting tag', err);
                        }
                    });
                }

                // 3) Click on task (delegation) — один обработчик для документа
                document.addEventListener('click', function(e) {
                    // если кликнули по чекбоксу — игнорируем
                    if (e.target.closest('input[type="checkbox"]')) return;

                    const taskItem = e.target.closest('.task-item');
                    if (!taskItem) return;

                    const taskId = taskItem.dataset.taskId || taskItem.dataset.id;
                    if (!taskId) return;

                    // вызываем загрузку деталей (функция определена выше)
                    if (typeof window.loadTaskDetails === 'function') {
                        window.loadTaskDetails(taskId);
                    } else {
                        console.warn('loadTaskDetails not defined');
                    }
                });
            });

        })();
    </script>




</body>

</html>