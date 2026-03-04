<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->latest();

        $search = trim((string) $request->get('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $role = $request->get('role');
        if (in_array($role, ['admin', 'customer'], true)) {
            $query->where('role', $role);
        }

        $users = $query->paginate(20)->withQueryString();
        $adminsCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'adminsCount', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($data);

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur cree avec succes.');
    }

    public function edit(User $user)
    {
        $isLastAdmin = $user->role === 'admin' && User::where('role', 'admin')->count() <= 1;
        return view('admin.users.edit', compact('user', 'isLastAdmin'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $currentUser = Auth::user();
        $isSelf = $currentUser && $currentUser->id === $user->id;
        $adminsCount = User::where('role', 'admin')->count();
        $wasAdmin = $user->role === 'admin';
        $willBeAdmin = $data['role'] === 'admin';

        if ($wasAdmin && !$willBeAdmin && $adminsCount <= 1) {
            return back()->withInput()->with('error', 'Impossible de retirer le dernier administrateur.');
        }

        if ($isSelf && $wasAdmin && !$willBeAdmin) {
            return back()->withInput()->with('error', 'Vous ne pouvez pas retirer votre propre role administrateur.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur mis a jour avec succes.');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte ici.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur supprime avec succes.');
    }
}
