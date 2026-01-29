<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Players') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="{{ route('players.create') }}" class="mb-4 inline-block px-4 py-2 bg-blue-500 text-white rounded">Add Player</a>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Team</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td>{{ $player->name }}</td>
                                    <td>{{ $player->team->name }}</td>
                                    <td>{{ $player->position }}</td>
                                    <td>
                                        <a href="{{ route('players.show', $player) }}">View</a>
                                        <a href="{{ route('players.edit', $player) }}">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
