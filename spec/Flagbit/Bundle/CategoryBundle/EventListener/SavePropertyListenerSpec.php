<?php

declare(strict_types=1);

namespace spec\Flagbit\Bundle\CategoryBundle\EventListener;

use Akeneo\Pim\Enrichment\Component\Category\Model\CategoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use EmptyIterator;
use Flagbit\Bundle\CategoryBundle\Entity\CategoryProperty;
use Flagbit\Bundle\CategoryBundle\EventListener\SavePropertyListener;
use Flagbit\Bundle\CategoryBundle\Exception\ValidationFailed;
use Flagbit\Bundle\CategoryBundle\Repository\CategoryPropertyRepository;
use Flagbit\Bundle\CategoryBundle\Schema\SchemaValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

class SavePropertyListenerSpec extends ObjectBehavior
{
    public function let(
        ParameterBag $propertyValuesBag,
        CategoryPropertyRepository $repository,
        EntityManagerInterface $entityManager,
        SchemaValidator $validator
    ): void {
        $this->beConstructedWith($propertyValuesBag, $repository, $entityManager, $validator);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SavePropertyListener::class);
    }

    public function it_ignores_entities_that_are_not_categories(
        GenericEvent $event,
        EntityManagerInterface $entityManager
    ): void {
        $event->getSubject()->willReturn(new EmptyIterator());

        $entityManager->flush()->ShouldNotBeCalled();

        $this->onCategoryPostSave($event);
    }

    public function it_doesnt_have_properties_to_save(
        GenericEvent $event,
        CategoryInterface $category,
        ParameterBag $propertyValuesBag,
        EntityManagerInterface $entityManager,
        CategoryPropertyRepository $repository
    ): void {
        $event->getSubject()->willReturn($category);

        $repository->findOneBy(['category' => $category])->willReturn(null);

        $propertyValuesBag->all()->willReturn([]);

        $entityManager->flush()->ShouldNotBeCalled();

        $this->onCategoryPostSave($event);
    }

    public function it_creates_new_category_property_if_missing(
        GenericEvent $event,
        CategoryInterface $category,
        ParameterBag $propertyValuesBag,
        EntityManagerInterface $entityManager,
        CategoryPropertyRepository $repository,
        SchemaValidator $validator
    ): void {
        $event->getSubject()->willReturn($category);

        $repository->findOneBy(['category' => $category])->willReturn(null);

        $propertyValuesBag->all()->willReturn(['foo' => []]);
        $propertyValuesBag->keys()->willReturn(['foo']);
        $propertyValuesBag->remove('foo')->shouldBeCalledOnce();

        $validator->validate(['foo' => []])->willReturn([]);

        $propertiesAreSet = static function (CategoryProperty $categoryProperty): bool {
            return $categoryProperty->getProperties() === ['foo' => []];
        };

        $entityManager->persist(Argument::that($propertiesAreSet))->ShouldBeCalledTimes(1);
        $entityManager->flush()->ShouldBeCalledTimes(1);

        $this->onCategoryPostSave($event);
    }

    public function it_throws_exception_on_invalid_property_schema(
        GenericEvent $event,
        CategoryInterface $category,
        CategoryProperty $categoryProperty,
        ParameterBag $propertyValuesBag,
        EntityManagerInterface $entityManager,
        CategoryPropertyRepository $repository,
        SchemaValidator $validator
    ): void {
        $event->getSubject()->willReturn($category);

        $repository->findOneBy(['category' => $category])->willReturn(null);

        $propertyValuesBag->all()->willReturn(['foo' => []]);

        $validator->validate(['foo' => []])->willReturn(['error' => 'text']);

        $entityManager->flush()->ShouldNotBeCalled();

        $this->shouldThrow(ValidationFailed::class)->during('onCategoryPostSave', [$event]);
    }

    public function it_processes(
        GenericEvent $event,
        CategoryInterface $category,
        CategoryProperty $categoryProperty,
        ParameterBag $propertyValuesBag,
        EntityManagerInterface $entityManager,
        CategoryPropertyRepository $repository,
        SchemaValidator $validator
    ): void {
        $event->getSubject()->willReturn($category);

        $repository->findOneBy(['category' => $category])->willReturn($categoryProperty);

        $propertyValuesBag->all()->willReturn(['foo' => []]);

        $validator->validate(['foo' => []])->willReturn([]);

        $categoryProperty->setProperties(['foo' => []])->shouldBeCalled();
        $propertyValuesBag->keys()->willReturn(['foo']);
        $propertyValuesBag->remove('foo')->shouldBeCalledOnce();

        $entityManager->persist($categoryProperty)->ShouldBeCalledTimes(1);
        $entityManager->flush()->ShouldBeCalledTimes(1);

        $this->onCategoryPostSave($event);
    }
}
