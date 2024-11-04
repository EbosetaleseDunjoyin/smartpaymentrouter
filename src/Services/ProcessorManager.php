<?php

namespace Eboseogbidi\Smartpaymentrouter\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Exception;

class ProcessorManager
{
    protected $configPath;
    protected $processors = [];
    protected $config;

    /**
     * ProcessorManager constructor.
     * 
     * 
     */
    public function __construct()
    {
        $this->configPath = config_path('smartpaymentrouter.php');
        $this->processors = config('smartpaymentrouter.processors');
    }

    

    /**
     * Add a new payment processor.
     *
     * @param string $name
     * @param array $details
     * @return bool
     */
    public function addProcessor(string $name, array $details): bool
    {
        if ($this->processorExists($name)) {
            return false;
        }
        $this->processors[$name] = $details;
        $this->saveProcessors();

        return true;
    }

    /**
     * Update an existing payment processor.
     *
     * @param string $name
     * @param array $details
     * @return bool
     */
    public function updateProcessor(string $name, array $details): bool
    {
        if (!$this->processorExists($name)) {
            return false;
        }

        $this->processors[$name] = $details;
        $this->saveProcessors();

        return true;
    }

    /**
     * Remove a payment processor.
     *
     * @param string $name
     * @return bool
     */
    public function removeProcessor(string $name): bool
    {
        if (!$this->processorExists($name)) {
            return false;
        }

        unset($this->processors[$name]);
        $this->saveProcessors();

        return true;
    }

    /**
     * Get all processors.
     *
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * Check if a processor exists.
     *
     * @param string $name
     * @return bool
     */
    protected function processorExists(string $name): bool
    {
        return isset($this->processors[$name]);
    }

    /**
     * Save processors back to the configuration file.
     *
     * @return void
     * @throws Exception
     */
     protected function saveProcessors(): void
    {
        try {
            // Load the current configuration file content
            $configArray = include($this->configPath);
            
            if (is_array($configArray)) {
                $configArray['processors'] = $this->processors;
            } else {
                throw new Exception("Invalid configuration format");
            }

            $configContent = "<?php\n\nreturn " . var_export($configArray, true) . ";\n";

            // Save back the modified configuration
            File::put($this->configPath, $configContent);
        } catch (Exception $e) {
            throw new Exception("Unable to save the configuration file: " . $e->getMessage());
        }
    }
}
