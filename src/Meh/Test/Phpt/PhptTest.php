<?php
namespace Meh\Test\Phpt;

class PhptTest
{
    /** @var string */
    public $name;

    /** @var PhptFile */
    public $file;

    /** @var PhptExpectation */
    public $expectation;

    /**
     * @param string $name
     * @param PhptFile $file
     * @param PhptExpectation $expectation
     */
    public function __construct($name, PhptFile $file, PhptExpectation $expectation)
    {
        $this->name = $name;
        $this->file = $file;
        $this->expectation = $expectation;
    }
}
