<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CostRepository")
 * @ORM\Table(name="cost")
 */
class Cost
{
    const TYPE_INTERNAL = 0; // resource
    const TYPE_EXTERNAL = 1; // cost

    const EXPENSE_TYPE_CAPEX = 0;
    const EXPENSE_TYPE_OPEX = 1;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="costs")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     * @Assert\NotBlank(message="not_blank.project")
     */
    private $project;

    /**
     * @var WorkPackage
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage", inversedBy="costs")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="work_package_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $workPackage;

    /**
     * @var resource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resource", inversedBy="costs")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $resource;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=128, nullable=true)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(name="type", type="integer", options={"default"=0})
     * @Assert\NotBlank(message="not_blank.type")
     */
    private $type = self::TYPE_INTERNAL;

    /**
     * @var int|null
     * @ORM\Column(name="expense_type", type="integer", nullable=true)
     */
    private $expenseType;

    /**
     * @var float
     * @ORM\Column(name="rate", type="decimal", precision=9, scale=2)
     * @Assert\NotBlank(message="not_blank.rate")
     */
    private $rate;

    /**
     * @var float
     * @ORM\Column(name="quantity", type="decimal", precision=9, scale=2)
     * @Assert\NotBlank(message="not_blank.quantity")
     */
    private $quantity;

    /**
     * @var Unit|null
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unit", cascade={"persist"})
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="unit_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $unit;

    /**
     * @var string
     * @ORM\Column(name="duration", type="string", length=64, nullable=true)
     */
    private $duration;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->expenseType = self::EXPENSE_TYPE_OPEX;
    }

    public function __toString()
    {
        return (string) $this->name;
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
     * Set name.
     *
     * @param string $name
     *
     * @return Cost
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Cost
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int|null $expenseType
     *
     * @return Cost
     */
    public function setExpenseType($expenseType)
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExpenseType()
    {
        return $this->expenseType;
    }

    /**
     * Set rate.
     *
     * @param string $rate
     *
     * @return Cost
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set quantity.
     *
     * @param string $quantity
     *
     * @return Cost
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unit.
     *
     * @param Unit $unit
     *
     * @return Cost
     */
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return Unit|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set duration.
     *
     * @param string $duration
     *
     * @return Cost
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration.
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Cost
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Cost
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Cost
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set workPackage.
     *
     * @param \AppBundle\Entity\WorkPackage $workPackage
     *
     * @return Cost
     */
    public function setWorkPackage(\AppBundle\Entity\WorkPackage $workPackage = null)
    {
        $this->workPackage = $workPackage;

        return $this;
    }

    /**
     * Get workPackage.
     *
     * @return \AppBundle\Entity\WorkPackage
     */
    public function getWorkPackage()
    {
        return $this->workPackage;
    }

    /**
     * Set resource.
     *
     * @param \AppBundle\Entity\Resource $resource
     *
     * @return Cost
     */
    public function setResource(\AppBundle\Entity\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource.
     *
     * @return \AppBundle\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @Serializer\SerializedName("project")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getProjectId()
    {
        return $this->getProject() ? $this->getProject()->getId() : null;
    }

    /**
     * @Serializer\SerializedName("projectName")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getProjectName()
    {
        return $this->getProject() ? $this->getProject()->getName() : null;
    }

    /**
     * @Serializer\SerializedName("resource")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getResourceId()
    {
        return $this->getResource() ? $this->getResource()->getId() : null;
    }

    /**
     * @Serializer\SerializedName("resourceName")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getResourceName()
    {
        return $this->getResource() ? $this->getResource()->getName() : null;
    }

    /**
     * @Serializer\SerializedName("workPackage")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getWorkPackageId()
    {
        return $this->getWorkPackage() ? $this->getWorkPackage()->getId() : null;
    }

    /**
     * @Serializer\SerializedName("workPackageName")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getWorkPackageName()
    {
        return $this->getWorkPackage() ? $this->getWorkPackage()->getName() : null;
    }

    /**
     * @Serializer\SerializedName("actualValue")
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getActualValue()
    {
        $durationFactor = intval($this->duration) > 0
            ? intval($this->duration)
            : 1
        ;

        return $this->getQuantity() * $this->getRate() * $durationFactor;
    }

    /**
     * Validation for the actual value,the sum of all values of
     * the children cannot excede the actual value of the parent.
     *
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function actualValueValidator(ExecutionContextInterface $context)
    {
        $workPackage = $this->getWorkPackage();
        if (!$workPackage) {
            return;
        }

        $parent = $workPackage->getParent();
        if (!$parent) {
            return;
        }

        $siblingsActualCost = $parent->getChildrenTotalCosts();
        if (!$this->getId()) {
            $siblingsActualCost += $this->getActualValue();
        }

        if ($siblingsActualCost > $parent->getTotalCosts()) {
            $context
                ->buildViolation('invalid.cost.value_exceeds_parent_costs', [
                    '%cost_actual_value%' => $this->getActualValue(),
                ])
                ->atPath('rate')
                ->addViolation()
            ;
        }
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("opex")
     *
     * @return bool
     */
    public function isOPEX(): bool
    {
        return self::EXPENSE_TYPE_OPEX === $this->getExpenseType();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("capex")
     *
     * @return bool
     */
    public function isCAPEX(): bool
    {
        return self::EXPENSE_TYPE_CAPEX === $this->getExpenseType();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("external")
     *
     * @return bool
     */
    public function isExternal(): bool
    {
        return self::TYPE_EXTERNAL === $this->getType();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("internal")
     *
     * @return bool
     */
    public function isInternal(): bool
    {
        return self::TYPE_INTERNAL === $this->getType();
    }
}
