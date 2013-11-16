<?php
namespace Meh\Test\Phpt;

class PhptFile
{
    /** @var string */
    public $contents;

    /** @param string $contents */
    public function __construct($contents)
    {
        $this->contents = $contents;
    }
}
