<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Ambil ID sekolah dari session switcher jika sedang aktif
        $active_school_id = session('active_school_id');

        $users = User::with('school')
            ->whereNot('role', 'super_admin')
            // Logika filter: Jika switcher aktif, hanya tampilkan user dari sekolah tersebut
            ->when($active_school_id, function($query) use ($active_school_id) {
                return $query->where('school_id', $active_school_id);
            })
            ->when(request('role'), fn($q) => $q->where('role', request('role')))
            ->latest()
            ->paginate(15);

        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $schools = School::where('status', 'aktif')->get();
        return view('superadmin.users.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,guru,wali_murid,kepsek', // kepsek ditambahkan
        ]);

        User::create([
            'school_id' => $request->school_id,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $schools = School::where('status', 'aktif')->get();
        return view('superadmin.users.edit', compact('user', 'schools'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,guru,wali_murid,kepsek', // kepsek ditambahkan
            'password'  => 'nullable|min:6',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil dihapus!');
    }

    public function show(User $user)
    {
        return view('superadmin.users.show', compact('user'));
    }
}