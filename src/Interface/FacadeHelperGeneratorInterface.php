<?php

namespace Flawlol\FacadeIdeHelper\Interface;

/**
 * Interface FacadeHelperGeneratorInterface
 *
 * This interface defines the contract for generating facade helper files.
 *
 * @author Flawlol - Norbert Kecső
 */
interface FacadeHelperGeneratorInterface
{
    /**
     * Generate the facade helper file.
     *
     * This method is responsible for creating a helper file that provides IDE autocompletion
     * for facade classes.
     */
    public function generate(): void;
}