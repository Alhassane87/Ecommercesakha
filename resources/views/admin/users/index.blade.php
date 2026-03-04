@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-users text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Gestion des Utilisateurs</h1>
            <p class="text-white/90">Ajoutez, modifiez ou supprimez des administrateurs et des clients</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 mb-6 border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input
                type="text"
                name="q"
                value="{{ $search ?? '' }}"
                placeholder="Rechercher nom ou email..."
                class="md:col-span-2 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">

            <select
                name="role"
                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les roles</option>
                <option value="admin" @selected(($role ?? '') === 'admin')>Administrateur</option>
                <option value="customer" @selected(($role ?? '') === 'customer')>Client</option>
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full md:w-auto px-4 py-2 rounded-xl text-white font-semibold" style="background: var(--action-info)">
                    <i class="fas fa-search mr-1"></i>Filtrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="text-sm text-gray-600 dark:text-gray-300">
            Total admins: <span class="font-semibold">{{ $adminsCount }}</span>
        </div>
        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-5 py-3 rounded-xl text-white font-semibold shadow-lg" style="background: var(--action-success)">
            <i class="fas fa-user-plus"></i>
            <span>Nouvel utilisateur</span>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/70">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Creation</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        @php
                            $isSelf = auth()->id() === $user->id;
                            $isLastAdmin = $user->role === 'admin' && $adminsCount <= 1;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                @if($isSelf)
                                    <div class="text-xs text-indigo-600 dark:text-indigo-300">Vous</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">Administrateur</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">Client</span>
                                @endif
                                @if($isLastAdmin)
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Dernier admin</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-2 text-sm rounded-lg bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 hover:bg-yellow-200 dark:hover:bg-yellow-900/60">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </a>
                                    <form
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        data-confirm="Supprimer cet utilisateur ?"
                                        data-confirm-title="Suppression utilisateur"
                                        data-confirm-ok="Supprimer"
                                        data-confirm-variant="danger">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            @disabled($isSelf || $isLastAdmin)
                                            class="px-3 py-2 text-sm rounded-lg bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/60 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-trash mr-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-300">
                                Aucun utilisateur trouve.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection
