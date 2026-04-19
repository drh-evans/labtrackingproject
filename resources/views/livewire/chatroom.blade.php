<div class="flex flex-col h-[80vh] border rounded-xl bg-gray-50">
    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3">
        @foreach ($messages as $message)
            @php
                $isMe = $message->user_id === auth()->id();
            @endphp

            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                <!-- check if you are the user or if its another user -->
                <div class="text-xs font-semibold mb-1 px-1">
                    {{ $message->getDisplayName(auth()->user()) }}
                </div>

                <!-- display message content in a bubble like whatsapp or imessage -->
                <div
                    class="
        p-3 rounded-xl
        {{ $isMe ? 'bg-blue-500 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none' }}
    ">
                    <div>{{ $message->body }}</div>

                    <!-- Timestamp -->
                    <div class="text-xs mt-1 opacity-70 {{ $isMe ? 'text-right' : '' }}">
                        {{ $message->created_at->format('d/m/Y @ H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input -->
    <div class="flex flex-col mb-4">
        <div class="p-3 flex gap-2">
            <input type="text" wire:model="body" wire:keydown.enter="sendMessage"
                class="flex-1 border-gray-200 rounded-lg rounded-bl-none shadow-sm px-3 py-2"
                placeholder="What do you have in mind...">

            <button wire:click="sendMessage" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm">
                Send
            </button>
        </div>
    </div>
</div>
