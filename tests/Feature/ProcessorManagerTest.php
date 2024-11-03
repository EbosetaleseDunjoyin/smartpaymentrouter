<?php

use Eboseogbidi\Smartpaymentrouter\Tests\TestCase;
use Eboseogbidi\Smartpaymentrouter\Services\ProcessorManager;
use Eboseogbidi\Smartpaymentrouter\Contracts\PaymentProcessorInterface;

class ProcessorManagerTest extends TestCase
{
    private ProcessorManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new ProcessorManager();
    }

    /** @test */
    public function it_can_add_new_processor()
    {
        $processorConfig = [
            'name' => 'Test Processor',
            'class' => 'YourNamespace\Processors\TestProcessor',
            'transaction_cost' => 1.5,
            'reliability' => 99,
            'currencies' => ['USD', 'EUR']
        ];

        $this->manager->addProcessor('test_processor', $processorConfig);
        $config = $this->manager->getProcessors();

        $this->assertArrayHasKey('test_processor', $config['processors']);
        $this->assertEquals($processorConfig, $config['processors']['test_processor']);
    }

    /** @test */
    public function it_can_edit_processor()
    {
        $processorConfig = [
            'transaction_cost' => 1.9,
            'reliability' => 999,
            'currencies' => ['USD', 'EUR', 'NGN']
        ];


        $this->manager->updateProcessor('test_processor', $processorConfig);
        $config = $this->manager->getProcessors();

        $this->assertArrayHasKey('test_processor', $config['processors']);
        $this->assertEquals($processorConfig, $config['processors']['test_processor']);
    }

    /** @test */
    public function it_can_delete_processor()
    {
    
        $this->manager->removeProcessor('test_processor');
        $config = $this->manager->getProcessors();

        $this->assertNull($config['processors']['test_processor']);
    }

   
}