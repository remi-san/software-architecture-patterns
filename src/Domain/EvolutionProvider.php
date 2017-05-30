<?php

namespace Evaneos\Archi\Domain;

interface EvolutionProvider
{
    public function evolutionFor(Pokemon $pokemon);
}
