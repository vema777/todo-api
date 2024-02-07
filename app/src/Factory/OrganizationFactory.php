<?php

namespace App\Factory;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Organization>
 *
 * @method        Organization|Proxy                     create(array|callable $attributes = [])
 * @method static Organization|Proxy                     createOne(array $attributes = [])
 * @method static Organization|Proxy                     find(object|array|mixed $criteria)
 * @method static Organization|Proxy                     findOrCreate(array $attributes)
 * @method static Organization|Proxy                     first(string $sortedField = 'id')
 * @method static Organization|Proxy                     last(string $sortedField = 'id')
 * @method static Organization|Proxy                     random(array $attributes = [])
 * @method static Organization|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OrganizationRepository|RepositoryProxy repository()
 * @method static Organization[]|Proxy[]                 all()
 * @method static Organization[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Organization[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Organization[]|Proxy[]                 findBy(array $attributes)
 * @method static Organization[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Organization[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OrganizationFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Organization $organization): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Organization::class;
    }
}
