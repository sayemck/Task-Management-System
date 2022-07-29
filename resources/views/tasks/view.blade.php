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
                <a class="btn btn-primary" href="{{ route('task.edit', $task->id) }}"><i class="fa fa-edit"></i>
                    Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
            </form>
        </td>
    </tr>
@endforeach
