<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CustomField.
 *
 * @ORM\Table(name="custom_field")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomFieldRepository")
 */
class CustomField
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=255)
     */
    private $fieldName;

    /**
     * @var string
     *
     * @ORM\Column(name="field_type", type="string", length=255, nullable=true)
     */
    private $fieldType;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255)
     */
    public $class;

    /**
     * @var ArrayCollection|CustomFieldValue[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CustomFieldValue", mappedBy="customField")
     */
    private $customFieldValues;

    public function __construct()
    {
        $this->customFieldValues = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fieldName.
     *
     * @param string $fieldName
     *
     * @return CustomField
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName.
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set fieldType.
     *
     * @param string $fieldType
     *
     * @return CustomField
     */
    public function setFieldType($fieldType = null)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType.
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set class.
     *
     * @param string $class
     *
     * @return CustomField
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add customFieldValue.
     *
     * @param CustomFieldValue $customFieldValue
     *
     * @return CustomField
     */
    public function addCustomFieldValue(CustomFieldValue $customFieldValue)
    {
        $this->customFieldValues[] = $customFieldValue;

        return $this;
    }

    /**
     * Remove customFieldValue.
     *
     * @param CustomFieldValue $customFieldValue
     */
    public function removeCustomFieldValue(CustomFieldValue $customFieldValue)
    {
        $this->customFieldValues->removeElement($customFieldValue);
    }

    /**
     * Get customFieldValues.
     *
     * @return ArrayCollection|CustomFieldValue[]
     */
    public function getCustomFieldValues()
    {
        return $this->customFieldValues;
    }
}
