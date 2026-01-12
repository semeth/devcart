<?php

namespace App\Extensions\Base;

use App\Models\ExtensionModel;
use App\Models\ExtensionSettingModel;

/**
 * Base class for all extensions
 * 
 * Provides common functionality for extension management
 */
abstract class BaseExtension implements ExtensionInterface
{
    protected ExtensionModel $extensionModel;
    protected ExtensionSettingModel $settingModel;
    protected ?array $extensionData = null;
    protected array $settings = [];

    public function __construct()
    {
        $this->extensionModel = new ExtensionModel();
        $this->settingModel = new ExtensionSettingModel();
        $this->loadExtensionData();
        $this->loadSettings();
    }

    /**
     * Load extension data from database
     */
    protected function loadExtensionData(): void
    {
        $this->extensionData = $this->extensionModel->findByTypeAndCode(
            $this->getType(),
            $this->getCode()
        );
    }

    /**
     * Load settings from database
     */
    protected function loadSettings(): void
    {
        if (!$this->extensionData) {
            return;
        }

        $this->settings = $this->settingModel->getByExtensionId($this->extensionData['id']);
    }

    /**
     * Get extension type (must be implemented by child classes)
     */
    abstract public function getType(): string;

    /**
     * Get extension code (must be implemented by child classes)
     */
    abstract public function getCode(): string;

    /**
     * Get extension name (must be implemented by child classes)
     */
    abstract public function getName(): string;

    /**
     * Get extension description (must be implemented by child classes)
     */
    abstract public function getDescription(): string;

    /**
     * Get extension version (must be implemented by child classes)
     */
    abstract public function getVersion(): string;

    /**
     * Check if extension is active
     */
    public function isActive(): bool
    {
        return $this->extensionData && $this->extensionData['is_active'] == 1;
    }

    /**
     * Get all settings
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Set settings
     */
    public function setSettings(array $settings): void
    {
        if (!$this->extensionData) {
            return;
        }

        $encryptedKeys = $this->getEncryptedSettingKeys();
        $this->settingModel->setSettings($this->extensionData['id'], $settings, $encryptedKeys);
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Get a specific setting
     */
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Set a specific setting
     */
    public function setSetting(string $key, $value, bool $encrypt = false): void
    {
        if (!$this->extensionData) {
            return;
        }

        $this->settingModel->setSetting($this->extensionData['id'], $key, $value, $encrypt);
        $this->settings[$key] = $value;
    }

    /**
     * Validate settings (default implementation - can be overridden)
     */
    public function validateSettings(array $settings): array
    {
        $errors = [];
        $schema = $this->getSettingsSchema();

        foreach ($schema as $field) {
            $key = $field['key'] ?? null;
            if (!$key) {
                continue;
            }

            $required = $field['required'] ?? false;
            $value = $settings[$key] ?? null;

            if ($required && empty($value)) {
                $errors[] = ($field['label'] ?? $key) . ' is required';
            }

            // Type validation
            if ($value !== null && isset($field['type'])) {
                switch ($field['type']) {
                    case 'number':
                    case 'decimal':
                        if (!is_numeric($value)) {
                            $errors[] = ($field['label'] ?? $key) . ' must be a number';
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = ($field['label'] ?? $key) . ' must be a valid email';
                        }
                        break;
                    case 'url':
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            $errors[] = ($field['label'] ?? $key) . ' must be a valid URL';
                        }
                        break;
                }
            }
        }

        return $errors;
    }

    /**
     * Get settings schema (must be implemented by child classes)
     */
    abstract public function getSettingsSchema(): array;

    /**
     * Get list of setting keys that should be encrypted
     * Override in child classes to specify which settings to encrypt
     * 
     * @return array
     */
    protected function getEncryptedSettingKeys(): array
    {
        return ['api_key', 'secret_key', 'password', 'private_key', 'webhook_secret'];
    }

    /**
     * Get extension data from database
     */
    protected function getExtensionData(): ?array
    {
        return $this->extensionData;
    }
}
