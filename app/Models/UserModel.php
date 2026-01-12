<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'role',
        'email_verified',
        'email_verified_at',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name'  => 'required|min_length[2]|max_length[100]',
        'email'      => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
        'password'   => 'required|min_length[8]',
        'phone'      => 'permit_empty|max_length[20]',
        'role'       => 'permit_empty|in_list[customer,admin]',
        'status'     => 'permit_empty|in_list[active,inactive,suspended]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.',
        ],
    ];

    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Verify user password
     */
    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Get active users only
     */
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get users by role
     */
    public function getByRole(string $role)
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Mark email as verified
     */
    public function markEmailVerified(int $userId)
    {
        return $this->update($userId, [
            'email_verified' => 1,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update user status
     */
    public function updateStatus(int $userId, string $status)
    {
        return $this->update($userId, ['status' => $status]);
    }
}
