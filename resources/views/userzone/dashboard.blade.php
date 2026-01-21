<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-slate-500">Manage your teams and track performance</p>
                    </div>
                    <button onclick="window.location='{{ route('teams.create') }}'" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Team
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <button onclick="window.location='{{ route('teams.index') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 hover:shadow-md hover:bg-slate-50 transition-all duration-200 text-left group">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-slate-200 transition-colors">
                        <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Team Hub</h3>
                    <p class="text-slate-500">View and manage all your teams</p>
                </button>

                <button onclick="window.location='{{ route('teams.create') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 hover:shadow-md hover:bg-slate-50 transition-all duration-200 text-left group">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Create Team</h3>
                    <p class="text-slate-500">Start a new team</p>
                </button>

                <button onclick="window.location='{{ route('profile.edit') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 hover:shadow-md hover:bg-slate-50 transition-all duration-200 text-left group">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Profile</h3>
                    <p class="text-slate-500">Manage your account</p>
                </button>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Player Search & List (Detailed Interactive Component) -->
                <div class="lg:col-span-2">
                    <livewire:player-search />
                </div>

                <!-- API Widget (Detailed API Component) -->
                <div class="lg:col-span-1">
                     <livewire:external-football-matches />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
