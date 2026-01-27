<div>
    <input type="text" wire:model.live.debounce.250ms="nameSearch" placeholder="Enter an email...">

    <select wire:model.live="roleFilter">
        @can('access-admin')
            <option value="">All Roles</option>
            <option value="Lecturer">Lecturer</option>
        @endcan
        <option value="Teaching Assistant">Teaching Assistant</option>
        <option value="Student">Student</option>
    </select>

    <table>
        @foreach ($users as $user)
            <tr wire:key="user-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if (in_array($user->id, $enroledUsers))
                        <button wire:click="unenrol({{ $user->id }})">
                            Unenrol
                        </button>
                    @else
                        <button wire:click="enrol({{ $user->id }})">
                            Enrol
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
