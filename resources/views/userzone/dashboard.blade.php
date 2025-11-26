<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 overflow-hidden shadow-lg rounded-xl p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-primary-100">Manage your teams and track performance</p>
                    </div>
                    <button onclick="window.location='{{ route('teams.create') }}'" class="bg-white hover:bg-gray-50 text-primary-600 font-semibold py-3 px-6 rounded-lg transition shadow-md">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Team
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <button onclick="window.location='{{ route('teams.index') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-primary-200 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">Team Hub</h3>
                    <p class="text-gray-600">View and manage all your teams</p>
                </button>

                <button onclick="window.location='{{ route('teams.create') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Create Team</h3>
                    <p class="text-gray-600">Start a new team</p>
                </button>

                <button onclick="window.location='{{ route('profile.edit') }}'" class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-purple-200 transition-all duration-300 text-left group">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Profile</h3>
                    <p class="text-gray-600">Manage your account</p>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
