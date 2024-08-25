<?php

namespace Flawlol\FacadeIdeHelper\Interface;

/**
 * Interface ProcessInterface
 *
 * This interface defines the contract for processing facade helper generation.
 *
 * @author Flawlol - Norbert Kecső
 */
interface ProcessInterface
{
    /**
     * Invoke the process.
     *
     * This method is responsible for executing the process that generates facade helper files.
     */
    public function __invoke(): void;
}