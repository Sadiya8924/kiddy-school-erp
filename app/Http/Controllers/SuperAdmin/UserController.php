<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Yayasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request, ?string $token = null)
    {
        $currentUser = Auth::user();
        $active_school_id = session('active_school_id');

        $rawYayasanParam = $token ?: $request->get('y') ?: $request->get('yayasan_id');
        $active_yayasan_id = null;

        if ($rawYayasanParam) {
            $decryptedCode = Yayasan::decryptToken($rawYayasanParam);
            $targetCodeOrId = $decryptedCode ?: $rawYayasanParam;

            $foundY = Yayasan::where('id', $targetCodeOrId)
                ->orWhere('kode_yayasan', $targetCodeOrId)
                ->orWhere('kode_yayasan', strtolower($targetCodeOrId))
                ->first();

            if ($foundY) {
                $active_yayasan_id = $foundY->id;
                session(['active_yayasan_id' => $foundY->id]);
            }
        }

        if (!$active_yayasan_id) {
            $active_yayasan_id = $currentUser->getUserYayasanId() ?: session('active_yayasan_id');
        }

        // Jika Owner/Superadmin dan belum memilih yayasan, tampilkan Card per-Yayasan
        $showYayasanCards = ($currentUser->isOwner() || $currentUser->isSuperAdmin()) && !$active_yayasan_id && !$request->has('show_users') && !$rawYayasanParam;

        $yayasanCards = [];
        if ($showYayasanCards) {
            $yayasans = Yayasan::with('schools')->get();

            foreach ($yayasans as $yayasan) {
                $schoolIds = $yayasan->schools->pluck('id');

                $getUsersQuery = function($role = null) use ($yayasan, $schoolIds) {
                    return User::where(function($q) use ($yayasan, $schoolIds) {
                        $q->where('yayasan_id', $yayasan->id);
                        if ($schoolIds->isNotEmpty()) {
                            $q->orWhereIn('school_id', $schoolIds);
                        }
                    })
                    ->when($role, function($q) use ($role) {
                        return $q->where('role', $role);
                    });
                };

                $yayasan->total_users       = $getUsersQuery()->count();
                $yayasan->count_super_admin = $getUsersQuery('super_admin')->count();
                $yayasan->count_admin       = $getUsersQuery('admin')->count();
                $yayasan->count_kepsek      = $getUsersQuery('kepsek')->count();
                $yayasan->count_guru        = $getUsersQuery('guru')->count();
                $yayasan->count_ortu        = $getUsersQuery('wali_murid')->count();

                $yayasanCards[] = $yayasan;
            }
        }

        // Ambil data user untuk mode tabel
        $users = User::with(['school', 'yayasan'])
            ->when(!$currentUser->isOwner(), function($query) {
                return $query->whereNot('role', 'owner');
            })
            ->when($active_yayasan_id, function($query) use ($active_yayasan_id) {
                // Ambil TK-TK di bawah yayasan ini
                $schoolIds = School::where('yayasan_id', $active_yayasan_id)->pluck('id');
                return $query->where(function($q) use ($active_yayasan_id, $schoolIds) {
                    $q->where('yayasan_id', $active_yayasan_id)
                      ->orWhereIn('school_id', $schoolIds);
                });
            })
            ->when($active_school_id, function($query) use ($active_school_id) {
                return $query->where('school_id', $active_school_id);
            })
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->latest()
            ->paginate(15);

        $selectedYayasan = $active_yayasan_id ? Yayasan::find($active_yayasan_id) : null;

        return view('superadmin.users.index', compact('users', 'showYayasanCards', 'yayasanCards', 'selectedYayasan'));
    }

    public function create()
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        $schools = School::where('status', 'aktif')
            ->when(!$user->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('yayasan_id', $active_yayasan_id);
            })
            ->get();

        $yayasans = Yayasan::where('status', 'aktif')
            ->when(!$user->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('id', $active_yayasan_id);
            })
            ->get();

        return view('superadmin.users.create', compact('schools', 'yayasans'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $active_yayasan_id = $user->getUserYayasanId() ?: session('active_yayasan_id');

        $request->validate([
            'school_id'  => 'nullable|exists:schools,id',
            'yayasan_id' => 'nullable|exists:yayasans,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:owner,super_admin,admin,guru,wali_murid,kepsek',
        ]);

        $schoolId = $request->school_id;
        $yayasanId = $request->yayasan_id;

        if (!$user->isOwner() && $active_yayasan_id) {
            $yayasanId = $active_yayasan_id;
        } elseif ($schoolId && !$yayasanId) {
            $school = School::find($schoolId);
            if ($school) $yayasanId = $school->yayasan_id;
        }

        User::create([
            'school_id'  => $schoolId,
            'yayasan_id' => $yayasanId,
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $currentUser = Auth::user();
        $active_yayasan_id = $currentUser->getUserYayasanId() ?: session('active_yayasan_id');

        $schools = School::where('status', 'aktif')
            ->when(!$currentUser->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('yayasan_id', $active_yayasan_id);
            })
            ->get();

        $yayasans = Yayasan::where('status', 'aktif')
            ->when(!$currentUser->isOwner() && $active_yayasan_id, function($q) use ($active_yayasan_id) {
                return $q->where('id', $active_yayasan_id);
            })
            ->get();

        return view('superadmin.users.edit', compact('user', 'schools', 'yayasans'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $active_yayasan_id = $currentUser->getUserYayasanId() ?: session('active_yayasan_id');

        $request->validate([
            'school_id'  => 'nullable|exists:schools,id',
            'yayasan_id' => 'nullable|exists:yayasans,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:owner,super_admin,admin,guru,wali_murid,kepsek',
            'password'   => 'nullable|min:6',
        ]);

        $data = $request->except('password');

        if (!$currentUser->isOwner() && $active_yayasan_id) {
            $data['yayasan_id'] = $active_yayasan_id;
        } elseif ($request->school_id && !$request->yayasan_id) {
            $school = School::find($request->school_id);
            if ($school) $data['yayasan_id'] = $school->yayasan_id;
        }

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