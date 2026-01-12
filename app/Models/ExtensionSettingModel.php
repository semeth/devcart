<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtensionSettingModel extends Model
{
    protected $table            = 'extension_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'extension_id',
        'setting_key',
        'setting_value',
        'is_encrypted',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'extension_id'  => 'required|integer',
        'setting_key'   => 'required|max_length[255]',
        'setting_value' => 'permit_empty',
        'is_encrypted'  => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get all settings for an extension
     */
    public function getByExtensionId(int $extensionId): array
    {
        $settings = $this->where('extension_id', $extensionId)->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $value = $setting['setting_value'];
            
            // Decrypt if encrypted
            if ($setting['is_encrypted'] && $value) {
                try {
                    $encrypter = \Config\Services::encrypter();
                    $value = $encrypter->decrypt($value);
                } catch (\Exception $e) {
                    // If decryption fails, return empty string
                    $value = '';
                }
            }
            
            $result[$setting['setting_key']] = $value;
        }
        
        return $result;
    }

    /**
     * Get a single setting value
     */
    public function getSetting(int $extensionId, string $key, $default = null)
    {
        $setting = $this->where('extension_id', $extensionId)
                       ->where('setting_key', $key)
                       ->first();
        
        if (!$setting) {
            return $default;
        }
        
        $value = $setting['setting_value'];
        
        // Decrypt if encrypted
        if ($setting['is_encrypted'] && $value) {
            try {
                $encrypter = \Config\Services::encrypter();
                $value = $encrypter->decrypt($value);
            } catch (\Exception $e) {
                return $default;
            }
        }
        
        return $value ?? $default;
    }

    /**
     * Set a setting value
     */
    public function setSetting(int $extensionId, string $key, $value, bool $encrypt = false)
    {
        // Encrypt if needed
        if ($encrypt && $value) {
            try {
                $encrypter = \Config\Services::encrypter();
                $value = $encrypter->encrypt($value);
            } catch (\Exception $e) {
                return false;
            }
        }
        
        // Check if setting exists
        $existing = $this->where('extension_id', $extensionId)
                        ->where('setting_key', $key)
                        ->first();
        
        if ($existing) {
            // Update existing
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'is_encrypted' => $encrypt ? 1 : 0,
            ]);
        } else {
            // Insert new
            return $this->insert([
                'extension_id'  => $extensionId,
                'setting_key'   => $key,
                'setting_value' => $value,
                'is_encrypted'  => $encrypt ? 1 : 0,
            ]);
        }
    }

    /**
     * Set multiple settings at once
     */
    public function setSettings(int $extensionId, array $settings, array $encryptedKeys = [])
    {
        foreach ($settings as $key => $value) {
            $encrypt = in_array($key, $encryptedKeys);
            $this->setSetting($extensionId, $key, $value, $encrypt);
        }
        
        return true;
    }

    /**
     * Delete all settings for an extension
     */
    public function deleteByExtensionId(int $extensionId)
    {
        return $this->where('extension_id', $extensionId)->delete();
    }
}
