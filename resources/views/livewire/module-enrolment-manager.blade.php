<div>
    <input type="text" wire:model.live.debounce.250ms="nameSearch" placeholder="Enter an email...">

    <select wire:model.live="roleFilter">
        <option value="">All Roles</option>
        <option value="Student">Student</option>
        <option value="Teaching Assistant">Teaching Assistant</option>
        @can('access-admin')
            <option value="Lecturer">Lecturer</option>
        @endcan
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
