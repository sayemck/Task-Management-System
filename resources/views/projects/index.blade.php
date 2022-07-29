@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Task Management System</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('project.create') }}"> <i class="fa fa-plus"></i> Create New
                    Project</a>
                <a class="btn btn-success" href="{{ route('task.index') }}"> <i class="fa fa-list"></i> Task List</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table id="table" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>status</th>
                <th>Date</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{!! $project->status == 'active'
                        ? '<span class="text-success">Activated</span>'
                        : '<span class="text-danger">Inactivated</span>' !!}</td>
                    <td>
                        {{ $project->created_at->diffForHumans() }}
                        <br>
                        {{ date('d-M-Y', strtotime($project->created_at)) }}
                    </td>
                    <td>
                        <form action="{{ route('project.destroy', $project->id) }}" method="POST">
                            <a class="btn btn-primary" href="{{ route('project.edit', $project->id) }}"><i
                                    class="fa fa-edit"></i>
                                Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
