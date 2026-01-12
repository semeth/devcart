<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table            = 'addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'is_default',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id'       => 'required|integer',
        'type'          => 'required|in_list[billing,shipping,both]',
        'first_name'    => 'required|min_length[2]|max_length[100]',
        'last_name'     => 'required|min_length[2]|max_length[100]',
        'company'       => 'permit_empty|max_length[255]',
        'address_line_1' => 'required|max_length[255]',
        'address_line_2' => 'permit_empty|max_length[255]',
        'city'          => 'required|max_length[100]',
        'state'         => 'required|max_length[100]',
        'postal_code'   => 'required|max_length[20]',
        'country'       => 'required|max_length[100]',
        'phone'         => 'permit_empty|max_length[20]',
        'is_default'    => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultAddress'];
    protected $beforeUpdate   = ['setDefaultAddress'];

    /**
     * Set default address (unset others if this is set as default)
     */
    protected function setDefaultAddress(array $data)
    {
        if (isset($data['data']['is_default']) && $data['data']['is_default'] == 1) {
            $userId = $data['data']['user_id'] ?? null;
            if ($userId) {
                // Unset other default addresses for this user
                $this->where('user_id', $userId)
                     ->where('is_default', 1)
                     ->set('is_default', 0)
                     ->update();
            }
        }
        return $data;
    }

    /**
     * Get addresses for a user
     */
    public function getByUserId(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('is_default', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get addresses by type
     */
    public function getByType(int $userId, string $type)
    {
        return $this->where('user_id', $userId)
                    ->groupStart()
                        ->where('type', $type)
                        ->orWhere('type', 'both')
                    ->groupEnd()
                    ->orderBy('is_default', 'DESC')
                    ->findAll();
    }

    /**
     * Get default address
     */
    public function getDefaultAddress(int $userId, string $type = null)
    {
        $builder = $this->where('user_id', $userId)
                        ->where('is_default', 1);
        
        if ($type) {
            $builder->groupStart()
                    ->where('type', $type)
                    ->orWhere('type', 'both')
                    ->groupEnd();
        }
        
        return $builder->first();
    }

    /**
     * Get billing address
     */
    public function getBillingAddress(int $userId)
    {
        return $this->getDefaultAddress($userId, 'billing');
    }

    /**
     * Get shipping address
     */
    public function getShippingAddress(int $userId)
    {
        return $this->getDefaultAddress($userId, 'shipping');
    }

    /**
     * Format full address as string
     */
    public function formatAddress(array $address): string
    {
        $parts = [];
        $parts[] = $address['address_line_1'];
        if (!empty($address['address_line_2'])) {
            $parts[] = $address['address_line_2'];
        }
        $parts[] = $address['city'] . ', ' . $address['state'] . ' ' . $address['postal_code'];
        $parts[] = $address['country'];
        
        return implode("\n", $parts);
    }
}
