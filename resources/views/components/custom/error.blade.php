@if ($errors->any())
    <div>
        <ul class="text-red-500 font-bold">
            @foreach ($errors->all() as $error)
                <li>ERROR! {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
