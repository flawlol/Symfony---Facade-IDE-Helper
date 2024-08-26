<?php

namespace Flawlol\FacadeIdeHelper\Service;

use Flawlol\FacadeIdeHelper\Interface\FacadeHelperGeneratorInterface;
use Flawlol\FacadeIdeHelper\Interface\ProcessInterface;

/**
 * Class ProcessCommand
 *
 * This class implements the ProcessInterface and is responsible for invoking the facade helper generation process.
 *
 * @author Flawlol - Norbert Kecső
 */
final class ProcessCommand implements ProcessInterface
{
    /**
     * ProcessCommand constructor.
     *
     * @param FacadeHelperGeneratorInterface $facadeHelperGenerator The facade helper generator interface.
     */
    public function __construct(private FacadeHelperGeneratorInterface $facadeHelperGenerator)
    {

    }

    /**
     * Invoke the process.
     *
     * This method calls the generate method on the facade helper generator to create the facade helper file.
     */
    public function __invoke(): void
    {
        $this->facadeHelperGenerator->generate();
    }
}