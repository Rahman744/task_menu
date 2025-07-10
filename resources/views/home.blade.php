<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu</title>
    <link rel="stylesheet" href="{{ asset ('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset ('bootstrap/icons/bootstrap-icons.min.css') }}">
    <style>
        .task-link:hover {
            background-color: #e2e3e5;
        }
    </style>
</head>

<body>
    <div class="container-xl py-5">
        <div class="row">

            <div class="col-3 bg-light p-3 rounded-4">
                <h4 class="pb-2 fw-bold d-flex justify-content-between">Menu<i class="bi bi-list"></i></h4>
                <input class="form-control bg-light" type="text" placeholder="Search" aria-label="Search">
                <div>
                    <div class="fw-bolder pt-4 small text-muted">TASKS</div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-chevron-double-right mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-list-ul mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-calendar-week-fill mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi bi-sticky-fill mx-2"></i>Calendar</a></div>
                </div>
                <hr>
                <div>
                    <div class="fw-bolder small text-muted">LISTS</div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-danger mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary pe-2"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-info mx-2"></i>Personal</a><i class="bi bi-6-square text-secondary pe-2"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-warning mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary pe-2"></i></div>
                    <a href="#" style="text-decoration: none" class="text-muted ps-2"><span class="h4">+</span> Add New List</a>
                </div>
                <hr>
                <div class="pb-5 mb-5">
                    <div class="fw-bolder small text-muted mb-2">TAGS</div>
                    <div class="d-flex gap-2">
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">Tag 1</a></div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">Tag 2</a></div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">+ Add Tag</a></div>
                    </div>
                </div>
                <div class="task-link py-2 rounded-3 h6"><a href="#" style="text-decoration: none; font-size: 17px" class="text-secondary"><i class="bi bi-sliders2 mx-2"></i>Settings</a></div>
                <div class="task-link py-2 rounded-3 h6"><a href="#" style="text-decoration: none; font-size: 17px" class="text-secondary"><i class="bi bi-box-arrow-right mx-2"></i>Sign out</a></div>
            </div>

            <div class="col-6 ps-4 pe-4">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="me-5 fw-bold">Today</h1>
                    <span class="h3 border rounded px-2 py-1 fw-semibold">{{ count($tasks) }}</span>
                </div>

                <a href="#" style="text-decoration: none" class="text-secondary border rounded-2 form-control ps-3 mb-3">
                    <span class="h4 mx-2">+</span> Add New Task
                </a>

                @foreach($tasks as $task)
                <a href="{{ route('tasks.edit', $task->id) }}" style="text-decoration: none">
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between align-items-center mt-2 bg-light px-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                        </div>
                        <div class="flex-grow-1 ps-2 text-dark">{{ $task->title }}</div>
                        <i class="bi bi-chevron-right text-dark"></i>
                    </div>
                </a>
                @endforeach

            </div>

            <div class="col-3 bg-light p-3 rounded-4">
                <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                    @csrf
                    @if(isset($task))
                    @method('PUT')
                    @endif

                    <h4 class="pb-2 fw-bold">Task:</h4>

                    <input type="text" name="title" class="form-control bg-light" placeholder="Title" value="{{ $task->title ?? '' }}" required>
                    <br>

                    <textarea name="description" cols="10" rows="4" class="form-control bg-light" placeholder="Description">{{ $task->description ?? '' }}</textarea>

                    <div class="d-flex align-items-between gap-5 pt-4">
                        <div class="h6 pt-2 fw-semibold">List</div>
                        <div class="small ps-4 ms-3">
                            <select name="list" class="py-1 px-2 bg-light border rounded">
                                <option value="Personal" {{ (isset($task) && $task->list == 'Personal') ? 'selected' : '' }}>Personal</option>
                                <option value="Developer" {{ (isset($task) && $task->list == 'Developer') ? 'selected' : '' }}>Developer</option>
                                <option value="Business" {{ (isset($task) && $task->list == 'Business') ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-between gap-5 py-3">
                        <div class="h6 pt-2 fw-semibold">Due date</div>
                        <input type="date" name="due_date" class="bg-light border rounded small ps-2" value="{{ $task->due_date ?? '' }}">
                    </div>

                    <div class="d-flex align-items-between pt-1 gap-5">
                        <div class="h6 pt-1 fw-semibold">Tags</div>
                        <div class="ps-4 ms-2">
                            <input type="text" name="tags" class="form-control bg-light small" placeholder="Tag1, Tag2" value="{{ $task->tags ?? '' }}">
                        </div>
                    </div>

                    <hr>

                    <div class="pt-4 d-flex gap-3">
                        @if(isset($task))
                        <button type="submit" class="btn btn-warning border fw-semibold px-3">Update Task</button>
                        @else
                        <button type="submit" class="btn btn-warning border fw-semibold px-3">Save Task</button>
                        @endif

                        @if(isset($task))
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light border fw-semibold px-4">Delete Task</button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>

</html>