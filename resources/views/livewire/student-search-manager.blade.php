<div>
    <input type="text" wire:model.live.debounce.250ms="nameSearch" placeholder="Enter an email...">

    <table>
        @foreach ($students as $student)
            <tr wire:key="user-{{ $student->id }}">
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>
                    <a href="{{ route('module.signoff', [$module, $student]) }}"> Select </a>
                </td>
            </tr>
        @endforeach
    </table>
</div>
