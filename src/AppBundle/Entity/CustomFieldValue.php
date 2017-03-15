<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CustomFieldValue.
 *
 * @ORM\Table(name="custom_field_value")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomFieldValueRepository")
 */
class CustomFieldValue
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
     * @var object
     */
    private $obj;

    /**
     * @var int
     *
     * @ORM\Column(name="obj_id", type="integer")
     */
    private $objId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var CustomField
     *
     * @ORM\ManyToOne(targetEntity="CustomField", inversedBy="customFieldValues")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $customField;

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
     * Set obj.
     *
     * @param $obj
     *
     * @return $this
     */
    public function setObj($obj)
    {
        $this->obj = $obj;

        return $this;
    }

    /**
     * Get obj.
     *
     * @return object
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * Set objId.
     *
     * @param int $objId
     *
     * @return CustomFieldValue
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;

        return $this;
    }

    /**
     * Get objId.
     *
     * @return int
     */
    public function getObjId()
    {
        return $this->objId;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return CustomFieldValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set customField.
     *
     * @param CustomField $customField
     *
     * @return CustomFieldValue
     */
    public function setCustomField(CustomField $customField = null)
    {
        $this->customField = $customField;

        return $this;
    }

    /**
     * Get customField.
     *
     * @return CustomField
     */
    public function getCustomField()
    {
        return $this->customField;
    }
}
