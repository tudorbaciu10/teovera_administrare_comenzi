<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('store')->orderBy('rol')->orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $stores = Store::orderBy('denumire')->get();

        return view('users.create', compact('stores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol'      => ['required', 'in:admin,operator,vanzatoare'],
            'store_id' => ['nullable', 'exists:stores,id'],
            'lang'     => ['required', 'in:ro,ru'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email ?: null,
            'password' => Hash::make($request->password),
            'rol'      => $request->rol,
            'store_id' => $request->store_id ?: null,
            'lang'     => $request->lang,
        ]);

        return redirect()->route('users.index')->with('success', __('admin.salvat_succes'));
    }

    public function edit(User $user): View
    {
        $stores = Store::orderBy('denumire')->get();

        return view('users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['nullable', 'email', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'string', 'min:6'],
            'rol'      => ['required', 'in:admin,operator,vanzatoare'],
            'store_id' => ['nullable', 'exists:stores,id'],
            'lang'     => ['required', 'in:ro,ru'],
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email ?: null,
            'rol'      => $request->rol,
            'store_id' => $request->store_id ?: null,
            'lang'     => $request->lang,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', __('admin.salvat_succes'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nu poți șterge propriul cont.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', __('admin.sters_succes'));
    }
}
