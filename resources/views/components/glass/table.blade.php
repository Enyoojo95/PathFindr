{{--
    <x-glass.table>
        <x-slot:head>
            <th>Name</th>
            <th>Status</th>
        </x-slot:head>
        <x-slot:body>
            <tr>
                <td>John</td>
                <td>Active</td>
            </tr>
        </x-slot:body>

        {{-- Optional footer (pagination etc.) --}}
        <x-slot:foot>
            {{ $users->links() }}
        </x-slot:foot>
    </x-glass.table>
--}}
@props(['minWidth' => '600px'])

<div class="glass-table-wrap">
    <div style="overflow-x:auto;">
        <table class="glass-table" style="min-width:{{ $minWidth }};">
            @if(isset($head))
            <thead>
                <tr>{{ $head }}</tr>
            </thead>
            @endif
            <tbody>
                {{ $body ?? $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($foot))
    <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,0.05);">
        {{ $foot }}
    </div>
    @endif
</div>
