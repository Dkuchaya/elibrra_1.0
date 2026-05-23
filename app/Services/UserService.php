<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(
        protected UserRepository $users
    ) {}

    public function paginate(string $search = '', int $perPage = 10, ?User $authUser = null)
{
    return $this->users->paginate($search, $perPage, $authUser);
}

    public function find(int $id): User
    {
        return $this->users->find($id);
    }

    public function create(array $data): User
    {
        $role = $data['role'];

        unset($data['role']);

        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['password'] = Hash::make('password123');
        $data['must_change_password'] = true;

        if (auth()->user()->hasRole('School Admin')) {
            $data['school_id'] = auth()->user()->school_id;
        }

        $user = $this->users->create($data);

        $user->assignRole($role);

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $role = $data['role'];

        unset($data['role']);

        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);

        if (auth()->user()->hasRole('School Admin')) {
            $data['school_id'] = auth()->user()->school_id;
        }

        $user = $this->users->update($id, $data);

        $user->syncRoles([$role]);

        return $user;
    }

    public function toggleStatus(int $id): User
    {
        $user = $this->users->find($id);

        return $this->users->update($id, [
            'is_active' => ! $user->is_active,
        ]);
    }

    public function delete(int $id): void
    {
        $this->users->delete($id);
    }

    public function importCsv($file, User $authUser): array
{
    $created = 0;
    $skipped = 0;

    $path = $file->getRealPath();

    $rows = array_map('str_getcsv', file($path));

    if (count($rows) < 2) {
        return [
            'created' => 0,
            'skipped' => 0,
        ];
    }

    $headers = array_map('strtolower', array_map('trim', $rows[0]));

    unset($rows[0]);

    foreach ($rows as $row) {
        $data = array_combine($headers, $row);

        if (! $data || empty($data['email'])) {
            $skipped++;
            continue;
        }

        if (User::where('email', $data['email'])->exists()) {
            $skipped++;
            continue;
        }

        $schoolId = $authUser->hasRole('Super Admin')
            ? ($data['school_id'] ?? null)
            : $authUser->school_id;

        $role = $authUser->hasRole('Super Admin')
            ? ($data['role'] ?? 'Reader')
            : 'Reader';

        $user = User::create([
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'gender' => $data['gender'] ?? null,
            'city' => $data['city'] ?? null,
            'school_id' => $schoolId,
            'password' => Hash::make($data['password'] ?? 'password123'),
            'is_active' => true,
        ]);

        $user->assignRole($role);

        $created++;
    }

    return [
        'created' => $created,
        'skipped' => $skipped,
    ];
}
}