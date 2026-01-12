<?php

namespace App\Services;

use App\Models\ExtensionModel;
use App\Extensions\Base\ExtensionInterface;
use App\Extensions\Base\PaymentExtensionInterface;
use App\Extensions\Base\ShippingExtensionInterface;

/**
 * Extension Manager Service
 * 
 * Manages loading, caching, and accessing extensions
 */
class ExtensionManager
{
    protected ExtensionModel $extensionModel;
    protected array $extensions = [];
    protected array $extensionInstances = [];

    public function __construct()
    {
        $this->extensionModel = new ExtensionModel();
    }

    /**
     * Get all extensions of a specific type
     * 
     * @param string $type Extension type (payment, shipping, etc.)
     * @param bool $activeOnly Only return active extensions
     * @return array Array of extension data
     */
    public function getExtensions(string $type, bool $activeOnly = true): array
    {
        $cacheKey = $type . '_' . ($activeOnly ? 'active' : 'all');
        
        if (!isset($this->extensions[$cacheKey])) {
            if ($activeOnly) {
                $this->extensions[$cacheKey] = $this->extensionModel->getActiveByType($type);
            } else {
                $this->extensions[$cacheKey] = $this->extensionModel->getByType($type);
            }
        }
        
        return $this->extensions[$cacheKey];
    }

    /**
     * Get extension instance by type and code
     * 
     * @param string $type Extension type
     * @param string $code Extension code
     * @return ExtensionInterface|null
     */
    public function getExtension(string $type, string $code): ?ExtensionInterface
    {
        $cacheKey = $type . '_' . $code;
        
        if (isset($this->extensionInstances[$cacheKey])) {
            return $this->extensionInstances[$cacheKey];
        }

        $extension = $this->extensionModel->findByTypeAndCode($type, $code);
        
        if (!$extension) {
            return null;
        }

        $instance = $this->loadExtensionInstance($type, $code);
        
        if ($instance) {
            $this->extensionInstances[$cacheKey] = $instance;
        }
        
        return $instance;
    }

    /**
     * Get default extension for a type
     * 
     * @param string $type Extension type
     * @return ExtensionInterface|null
     */
    public function getDefaultExtension(string $type): ?ExtensionInterface
    {
        $extension = $this->extensionModel->getDefaultByType($type);
        
        if (!$extension) {
            return null;
        }

        return $this->getExtension($type, $extension['code']);
    }

    /**
     * Get all payment extensions
     * 
     * @param bool $activeOnly
     * @return array Array of PaymentExtensionInterface instances
     */
    public function getPaymentExtensions(bool $activeOnly = true): array
    {
        $extensions = $this->getExtensions('payment', $activeOnly);
        $instances = [];

        foreach ($extensions as $extension) {
            $instance = $this->getExtension('payment', $extension['code']);
            if ($instance instanceof PaymentExtensionInterface) {
                $instances[] = $instance;
            }
        }

        return $instances;
    }

    /**
     * Get all shipping extensions
     * 
     * @param bool $activeOnly
     * @return array Array of ShippingExtensionInterface instances
     */
    public function getShippingExtensions(bool $activeOnly = true): array
    {
        $extensions = $this->getExtensions('shipping', $activeOnly);
        $instances = [];

        foreach ($extensions as $extension) {
            $instance = $this->getExtension('shipping', $extension['code']);
            if ($instance instanceof ShippingExtensionInterface) {
                $instances[] = $instance;
            }
        }

        return $instances;
    }

    /**
     * Get payment extension by code
     * 
     * @param string $code
     * @return PaymentExtensionInterface|null
     */
    public function getPaymentExtension(string $code): ?PaymentExtensionInterface
    {
        $extension = $this->getExtension('payment', $code);
        return $extension instanceof PaymentExtensionInterface ? $extension : null;
    }

    /**
     * Get shipping extension by code
     * 
     * @param string $code
     * @return ShippingExtensionInterface|null
     */
    public function getShippingExtension(string $code): ?ShippingExtensionInterface
    {
        $extension = $this->getExtension('shipping', $code);
        return $extension instanceof ShippingExtensionInterface ? $extension : null;
    }

    /**
     * Load extension instance by type and code
     * 
     * @param string $type
     * @param string $code
     * @return ExtensionInterface|null
     */
    protected function loadExtensionInstance(string $type, string $code): ?ExtensionInterface
    {
        $className = $this->getExtensionClassName($type, $code);
        
        if (!class_exists($className)) {
            return null;
        }

        try {
            $instance = new $className();
            
            if ($instance instanceof ExtensionInterface) {
                return $instance;
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to load extension: ' . $className . ' - ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get extension class name from type and code
     * 
     * @param string $type
     * @param string $code
     * @return string
     */
    protected function getExtensionClassName(string $type, string $code): string
    {
        // Convert code to class name (e.g., 'cash_on_delivery' -> 'CashOnDelivery')
        $className = str_replace('_', '', ucwords($code, '_'));
        
        // Build namespace
        $namespace = 'App\\Extensions\\' . ucfirst($type) . '\\' . $className;
        
        return $namespace;
    }

    /**
     * Clear extension cache
     * 
     * @param string|null $type Clear cache for specific type, or all if null
     */
    public function clearCache(?string $type = null): void
    {
        if ($type) {
            // Clear cache for specific type
            foreach (array_keys($this->extensions) as $key) {
                if (strpos($key, $type . '_') === 0) {
                    unset($this->extensions[$key]);
                }
            }
            
            foreach (array_keys($this->extensionInstances) as $key) {
                if (strpos($key, $type . '_') === 0) {
                    unset($this->extensionInstances[$key]);
                }
            }
        } else {
            // Clear all cache
            $this->extensions = [];
            $this->extensionInstances = [];
        }
    }

    /**
     * Check if extension exists
     * 
     * @param string $type
     * @param string $code
     * @return bool
     */
    public function extensionExists(string $type, string $code): bool
    {
        $extension = $this->extensionModel->findByTypeAndCode($type, $code);
        return $extension !== null;
    }
}
