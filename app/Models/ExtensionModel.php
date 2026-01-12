<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtensionModel extends Model
{
    protected $table            = 'extensions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type',
        'code',
        'name',
        'description',
        'version',
        'is_active',
        'is_default',
        'sort_order',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'type'        => 'required|max_length[50]',
        'code'        => 'required|max_length[50]',
        'name'        => 'required|max_length[255]',
        'description' => 'permit_empty',
        'version'     => 'permit_empty|max_length[20]',
        'is_active'   => 'permit_empty|in_list[0,1]',
        'is_default'  => 'permit_empty|in_list[0,1]',
        'sort_order'  => 'permit_empty|integer',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get extensions by type
     */
    public function getByType(string $type)
    {
        return $this->where('type', $type)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get active extensions by type
     */
    public function getActiveByType(string $type)
    {
        return $this->where('type', $type)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Find extension by type and code
     */
    public function findByTypeAndCode(string $type, string $code)
    {
        return $this->where('type', $type)
                    ->where('code', $code)
                    ->first();
    }

    /**
     * Get default extension for type
     */
    public function getDefaultByType(string $type)
    {
        return $this->where('type', $type)
                    ->where('is_active', 1)
                    ->where('is_default', 1)
                    ->first();
    }

    /**
     * Set extension as default (and unset others)
     */
    public function setAsDefault(int $extensionId)
    {
        $extension = $this->find($extensionId);
        if (!$extension) {
            return false;
        }

        // Unset other defaults of the same type
        $this->where('type', $extension['type'])
             ->where('id !=', $extensionId)
             ->set('is_default', 0)
             ->update();

        // Set this one as default
        return $this->update($extensionId, ['is_default' => 1]);
    }
}
