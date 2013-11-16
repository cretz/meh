<?php
namespace Meh\Test\Phpt;

class PhptExpectation
{
    /** @var string */
    public $contents;

    /** @var bool */
    public $formatted;

    public function __construct($contents, $formatted)
    {
        $this->contents = $contents;
        $this->formatted = $formatted;
    }
}