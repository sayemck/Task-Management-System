@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="panel-title text-info">Task Management System</h2>
            </div>
        </div>
        <div class="col-lg-12 py-4">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('task.create') }}"> <i class="fa fa-plus"></i> Create New Task</a>
                <a class="btn btn-success" href="{{ route('project.index') }}"> <i class="fa fa-list"></i> Project List</a>
            </div>
            <form class="form-inline" role="form" id='task-view-from' onsubmit="return false;">
                {{ csrf_field() }}

                <div class="form-group form-group-sm">
                    <label for="year" class="col-sm-2 control-label">Project </label>
                    <div class="col-sm-4">
                        <select id="project_id" name="project_id" class="form-control">
                            <option value="0">--- Select Project ---</option>
                            @if (isset($projects))
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-sm">
                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-block" type="button" id="task-view-button"><i
                                class="fa fa-list"></i> View Project</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-12 py-4">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <table id="table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Project Name</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody id="tablecontents">
                    @foreach ($tasks as $task)
                        <tr class="row1" data-id="{{ $task->id }}">
                            <td class="pl-3">{{ $loop->index + 1 }} <i class="fa fa-arrows"></i></td>
                            <td>{{ isset($task->project_name->name) ? $task->project_name->name : '' }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{!! $task->status == 'active'
                                ? '<span class="text-success">Activated</span>'
                                : '<span class="text-danger">Inactivated</span>' !!}</td>
                            <td>
                                {{ $task->created_at->diffForHumans() }}
                                <br>
                                {{ date('d-M-Y', strtotime($task->created_at)) }}
                            </td>
                            <td>
                                <form action="{{ route('task.destroy', $task->id) }}" method="POST">
                                    <a class="btn btn-primary" href="{{ route('task.edit', $task->id) }}"><i
                                            class="fa fa-edit"></i>
                                        Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
                                        Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // task filtering when select project
            $(document).on('click', '#task-view-button', function() {
                $.ajax({
                    url: "{{ url('view') }}",
                    type: 'POST',
                    data: $('#task-view-from').serialize(),
                    success: function(response) {
                        console.log(response);
                        $('#tablecontents').html(response);
                    }
                });
            });

            // The order will change when files are dragged and reordered from the project list
            $("#table").DataTable();

            $("#tablecontents").sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });

            function sendOrderToServer() {
                var priority = [];
                var token = $('meta[name="csrf-token"]').attr('content');
                $('tr.row1').each(function(index, element) {
                    priority.push({
                        id: $(this).attr('data-id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('task-sortable') }}",
                    data: {
                        priority: priority,
                        _token: token
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

                        } else {
                            console.log(response);
                        }
                    }
                });
            }
        });
    </script>
@stop
@endsection
