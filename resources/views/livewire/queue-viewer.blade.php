<div class="flex flex-col items-center">

    <p>{{ $entries->count() }} {{ Str::plural('student', $entries) }} currently in the queue.</p>
    @forelse ($entries as $entry)
        <p>{{ $entry->student->name }}</p>
    @empty
        <p>The queue is currently empty...</p>
    @endforelse
</div>
