<?php

namespace App\Extensions\Base;

/**
 * Base interface for all extensions
 * 
 * This interface defines the common contract that all extensions must implement,
 * regardless of their type (payment, shipping, etc.)
 */
interface ExtensionInterface
{
    /**
     * Get the unique extension code/identifier
     * 
     * @return string
     */
    public function getCode(): string;

    /**
     * Get the display name of the extension
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Get the extension description
     * 
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get the extension version
     * 
     * @return string
     */
    public function getVersion(): string;

    /**
     * Get the extension type (payment, shipping, etc.)
     * 
     * @return string
     */
    public function getType(): string;

    /**
     * Check if the extension is active/enabled
     * 
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Get all extension settings
     * 
     * @return array
     */
    public function getSettings(): array;

    /**
     * Set extension settings
     * 
     * @param array $settings
     * @return void
     */
    public function setSettings(array $settings): void;

    /**
     * Get a specific setting value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting(string $key, $default = null);

    /**
     * Set a specific setting value
     * 
     * @param string $key
     * @param mixed $value
     * @param bool $encrypt Whether to encrypt the value
     * @return void
     */
    public function setSetting(string $key, $value, bool $encrypt = false): void;

    /**
     * Validate extension settings
     * Returns array of validation errors (empty array if valid)
     * 
     * @param array $settings
     * @return array Array of error messages
     */
    public function validateSettings(array $settings): array;

    /**
     * Get the settings schema for admin configuration form
     * Returns array of field definitions
     * 
     * @return array
     */
    public function getSettingsSchema(): array;
}
