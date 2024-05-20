<?php

declare(strict_types=1);

namespace Blackbird\DTOToolkit\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class ClassNotFound extends LocalizedException
{
    /**
     * @param Phrase|null $phrase
     * @param \Exception|null $cause
     * @param int $code
     */
    public function __construct(Phrase $phrase = null, \Exception $cause = null, int $code = 0)
    {
        if ($phrase === null) {
            $phrase = new Phrase("The class provided was not found.");
        }

        parent::__construct($phrase, $cause, $code);
    }
}

