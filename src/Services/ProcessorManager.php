<?php

namespace Eboseogbidi\Smartpaymentrouter\Services;

use Illuminate\Support\Facades\File;

class ProcessorManager
{
    protected $configPath;
    protected $processors;

    public function __construct()
    {
        // $this->configPath = config_path('smartpaymentrouter');
        $this->processors =\config('smartpaymentrouter.processors');
    }

    /**
     * Add a new payment processor
     *
     * @param string $name
     * @param array $details
     */
    public function addProcessor(string $name, array $details)
    {
        $this->processors[$name] = $details;
        // $this->saveProcessors();
    }

    /**
     * Update an existing payment processor
     *
     * @param string $name
     * @param array $details
     */
    public function updateProcessor(string $name, array $details)
    {
        if (isset($this->processors[$name])) {
            $this->processors[$name] = $details;
            // $this->saveProcessors();
        }
    }

    /**
     * Remove a payment processor
     *
     * @param string $name
     */
    public function removeProcessor(string $name)
    {
        if (isset($this->processors[$name])) {
            unset($this->processors[$name]);
            $this->saveProcessors();
        }
    }

    /**
     * Get all processors
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Save processors back to the configuration file
     */
    protected function saveProcessors()
    {
        $configContent = "<?php\n\nreturn [\n    'processors' => " . var_export($this->processors, true) . ",\n];\n";

        File::put($this->configPath, $configContent);
    }
}
