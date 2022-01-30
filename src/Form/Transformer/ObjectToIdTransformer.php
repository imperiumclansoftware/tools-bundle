<?php

namespace ICS\ToolsBundle\Form\Transformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ObjectToIdTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $registry;

    /**
     * @var string
     */
    private $class;

    private $multiple=false;

    /**
     * @param EntityManagerInterface $registry
     * @param string          $class
     */
    public function __construct(ManagerRegistry $registry, $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    /**
     * Transforms an object (object) to a string (id).
     *
     * @param object|null $object
     *
     * @return string
     */
    public function transform($object)
    {
        if (null === $object) {
            return '';
        }

        return $object->getId();
    }

    /**
     * Transforms a string (id) to an object (object).
     *
     * @param string $id
     *
     * @throws TransformationFailedException if object (object) is not found
     *
     * @return object|null
     */
    public function reverseTransform($id)
    {
        if (empty($id)) {
            return;
        }
        $object = $this->registry->getManagerForClass($this->class)->getRepository($this->class)->find($id);
        if (null === $object) {
            throw new TransformationFailedException(sprintf('Object from class %s with id "%s" not found', $this->class, $id));
        }

        return $object;
    }
}
