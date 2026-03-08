<div>
    @if ($entries->isNotEmpty())
        <table>
            <tr>
                <th>Name</th>
                <th>Assistance Type</th>
                <th>Task</th>
            </tr>

            @foreach ($entries as $entry)
                <tr>
                    <td>{{ $entry->student->name }}</td>
                    <td>{{ $entry->assistance_type }}</td>
                    <td>Task 1</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>The queue is currently empty...</p>
    @endif
</div>
