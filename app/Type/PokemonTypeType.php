<?php

namespace Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Evaneos\Archi\Domain\Model\VO\PokemonType;

class PokemonTypeType extends Type
{
    const NAME = 'pokemon-type';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array            $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform         The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param PokemonType      $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue((string) $value, $platform);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return PokemonType
     *
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new PokemonType(parent::convertToPHPValue($value, $platform));
    }


    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
