<div class="flex flex-col h-[80vh] border rounded-xl">
    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3">
        @foreach ($messages as $message)
            <div class="bg-gray-100 p-3 rounded-lg">
                <div class="text-sm font-semibold">
                    {{ $message->user->name ?? $message->user['name'] }}
                </div>
                <div>{{ $message->body }}</div>
                <div class="text-xs text-gray-500">
                    {{ $message->created_at }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input -->
    <div class="p-3 border-t flex gap-2">
        <input type="text" wire:model="body" wire:keydown.enter="sendMessage" class="flex-1 border rounded px-3 py-2"
            placeholder="Type a message...">

        <button wire:click="sendMessage" class="bg-blue-500 text-white px-4 py-2 rounded">
            Send
        </button>
    </div>
</div>
