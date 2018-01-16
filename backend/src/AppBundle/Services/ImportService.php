<?php

namespace AppBundle\Services;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\Company;
use AppBundle\Entity\CustomField;
use AppBundle\Entity\CustomFieldValue;
use AppBundle\Entity\Timephase;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Project;
use AppBundle\Entity\Calendar;
use AppBundle\Entity\Day;
use AppBundle\Entity\WorkingTime;
use AppBundle\Utils\ImportConstants;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class ImportService
 * Imports projects based on xml file.
 */
class ImportService
{
    /** @var ObjectRepository */
    private $customFieldRepo;

    /**
     * ImportService constructor.
     *
     * @param EntityManager      $em
     * @param RecursiveValidator $validator
     */
    public function __construct(EntityManager $em, RecursiveValidator $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->customFieldRepo = $this->em->getRepository(CustomField::class);
    }

    /**
     * Check if given string has a date format.
     *
     * @param $string
     *
     * @return mixed
     */
    private function checkIsDate($string)
    {
        return preg_match('/(?<!\d)\d{4}-\d{2}-\d{2}(?!\d)/', $string);
    }

    /**
     * Import project based on xml content.
     *
     * @param Project $project
     * @param $content
     */
    public function importProjects(Project $project, $content)
    {
        $projectSaveQueue = new ArrayCollection();
        $xml = new \SimpleXMLElement($content);

        foreach ($xml->children() as $tag => $element) {
            if (array_key_exists($tag, ImportConstants::PROJECT_KEY_FUNCTION)) {
                if ($tag === ImportConstants::PROJECT_NAME_TAG) {
                    if ($this->em->getRepository(Project::class)->findBy(['name' => (string) $element])) {
                        return;
                    }
                }

                $action = ImportConstants::PROJECT_KEY_FUNCTION[$tag];
                if (is_callable([$project, $action])) {
                    if ($this->checkIsDate((string) $element)) {
                        $date = str_replace('T', ' ', (string) $element);
                        $project->$action(new \DateTime($date));
                    } elseif ($tag === ImportConstants::PROJECT_COMPANY_TAG) {
                        $company = $this->importCompany((string) $element);
                        $project->$action($company);
                    } else {
                        $project->$action((string) $element);
                    }
                }
            } else {
                switch ($tag) {
                    case ImportConstants::CALENDARS_TAG:
                        $this->importCalendars($project, (array) $element);
                        break;
                    case ImportConstants::TASKS_TAG:
                        $this->importWorkPackages($project, (array) $element);
                        break;
                    case ImportConstants::RESOURCES_TAG:
                        $this->importWorkPackageProjectWorkCostTypes((array) $element);
                        break;
                    case ImportConstants::ASSIGNMENTS_TAG:
                        $this->importAssignments((array) $element);
                        break;
                    default:
                        if (!is_array($element)) {
                            $this->addCustomField($tag, $element, $project, Project::class, $projectSaveQueue);
                        }
                }
            }
        }
        $this->em->persist($project);
        $this->em->flush();
        $this->saveQueueItems($projectSaveQueue);
    }

    /**
     * Import calendars based on xml content.
     *
     * @param $project
     * @param $calendars
     */
    public function importCalendars($project, $calendars)
    {
        $calendarSaveQueue = new ArrayCollection();

        if (is_array($calendars) && is_object($calendars['Calendar'])) {
            $calendar = $calendars['Calendar'];
            $calendars['Calendar'] = [$calendar];
        }

        foreach ($calendars['Calendar'] as $calendar) {
            $newCalendar = new Calendar();
            foreach ((array) $calendar as $calendarTag => $element) {
                if (array_key_exists($calendarTag, ImportConstants::CALENDAR_KEY_FUNCTION)) {
                    $action = ImportConstants::CALENDAR_KEY_FUNCTION[$calendarTag];
                    if (is_callable([$newCalendar, $action])) {
                        $newCalendar->$action((string) $element);
                    }
                } else {
                    switch ($calendarTag) {
                        case ImportConstants::BASE_CALENDAR_TAG:
                            if ($element != '-1') {
                                $baseCalendar = $this
                                    ->em
                                    ->getRepository(Calendar::class)
                                    ->findOneBy([
                                        'externalId' => (int) $element,
                                    ])
                                ;
                                if ($baseCalendar) {
                                    $newCalendar->setParent($baseCalendar);
                                }
                            }
                            break;
                        case ImportConstants::WEEKDAYS_TAG:
                            $this->importDays($newCalendar, (array) $element);
                            break;
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField($calendarTag, $element, $newCalendar, Calendar::class, $calendarSaveQueue);
                            }
                    }
                }
            }

            $newCalendar->setProject($project);
            $this->em->persist($newCalendar);
            $this->em->flush();
            $this->saveQueueItems($calendarSaveQueue);
        }
    }

    /**
     * Import days based on xml content.
     *
     * @param $calendar
     * @param $days
     */
    public function importDays($calendar, $days)
    {
        $daySaveQueue = new ArrayCollection();

        if (is_array($days) && is_object($days['WeekDay'])) {
            $day = $days['WeekDay'];
            $days['WeekDay'] = [$day];
        }

        foreach ($days['WeekDay'] as $day) {
            $newDay = new Day();
            foreach ((array) $day as $dayTag => $element) {
                if (array_key_exists($dayTag, ImportConstants::DAY_KEY_FUNCTION)) {
                    $action = ImportConstants::DAY_KEY_FUNCTION[$dayTag];
                    if (is_callable([$newDay, $action])) {
                        $newDay->$action((int) $element);
                    }
                } else {
                    switch ($dayTag) {
                        case ImportConstants::WORKING_TIMES_TAG:
                            $this->importWorkingTimes($newDay, (array) $element);
                            break;
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField($dayTag, $element, $newDay, Day::class, $daySaveQueue);
                            }
                    }
                }
            }

            $newDay->setCalendar($calendar);
            $this->em->persist($newDay);
            $this->em->flush();
            $this->saveQueueItems($daySaveQueue);
        }
    }

    /**
     * Import work times based on xml content.
     *
     * @param $day
     * @param $workingTimes
     */
    public function importWorkingTimes($day, $workingTimes)
    {
        $workingTimeSaveQueue = new ArrayCollection();

        if (is_array($workingTimes) && is_object($workingTimes['WorkingTime'])) {
            $workingTime = $workingTimes['WorkingTime'];
            $workingTimes['WorkingTime'] = [$workingTime];
        }

        foreach ($workingTimes['WorkingTime'] as $workingTime) {
            $newWorkingTime = new WorkingTime();
            foreach ((array) $workingTime as $workingTag => $element) {
                if (array_key_exists($workingTag, ImportConstants::WORKTIME_KEY_FUNCTION)) {
                    $action = ImportConstants::WORKTIME_KEY_FUNCTION[$workingTag];
                    if (is_callable([$newWorkingTime, $action])) {
                        $newWorkingTime->$action(new \DateTime($element));
                    }
                } else {
                    switch ($workingTag) {
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField($workingTag, $element, $newWorkingTime, WorkingTime::class, $workingTimeSaveQueue);
                            }
                    }
                }
            }

            $newWorkingTime->setDay($day);
            $this->em->persist($newWorkingTime);
            $this->em->flush();
            $this->saveQueueItems($workingTimeSaveQueue);
        }
    }

    /**
     * @TODO: What field should be used for our entity field "puid"
     *
     * Import work packages (tasks) based on xml content.
     *
     * @param $project
     * @param $workPackages
     */
    public function importWorkPackages($project, $workPackages)
    {
        $workPackageSaveQueue = new ArrayCollection();
        /** @var ArrayCollection|WorkPackage[] $processedWorkPackages */
        $processedWorkPackages = new ArrayCollection();
        $outlineNumber2Entity = [];

        if (is_array($workPackages) && is_object($workPackages['Task'])) {
            $workPackage = $workPackages['Task'];
            $workPackages['Task'] = [$workPackage];
        }

        foreach ($workPackages['Task'] as $workPackage) {
            $newWorkPackage = new WorkPackage();
            foreach ((array) $workPackage as $workPackageTag => $element) {
                if (array_key_exists($workPackageTag, ImportConstants::WORKPACKAGE_KEY_FUNCTION)) {
                    $action = ImportConstants::WORKPACKAGE_KEY_FUNCTION[$workPackageTag];
                    if (is_callable([$newWorkPackage, $action])) {
                        if ($this->checkIsDate((string) $element)) {
                            $date = str_replace('T', ' ', (string) $element);
                            $newWorkPackage->$action(new \DateTime($date));
                        } else {
                            $newWorkPackage->$action((string) $element);
                        }
                    }
                } else {
                    switch ($workPackageTag) {
                        case ImportConstants::UID:
                            $workPackage = $this
                                ->em
                                ->getRepository(WorkPackage::class)
                                ->findOneBy([
                                    'externalId' => (int) $element,
                                ])
                            ;

                            if ($workPackage) {
                                $newWorkPackage->setParent($workPackage);
                            }
                            break;
                        case ImportConstants::CALENDAR_UID_TAG:
                            $calendar = $this
                                ->em
                                ->getRepository(Calendar::class)
                                ->findOneBy([
                                    'externalId' => (int) $element,
                                ])
                            ;

                            if ($calendar) {
                                $newWorkPackage->setCalendar($calendar);
                            }
                            break;
                        default:
                            if ($workPackageTag === ImportConstants::OUTLINE_NUMBER) {
                                $outlineNumber2Entity[(string) $element] = $newWorkPackage;
                            }
                            if (!is_array($element)) {
                                $this->addCustomField($workPackageTag, $element, $newWorkPackage, WorkPackage::class, $workPackageSaveQueue);
                            }
                    }
                }
            }

            // set type
            switch (true) {
                case stripos($newWorkPackage->getName(), 'phase') !== false:
                    $newWorkPackage->setType(WorkPackage::TYPE_PHASE);
                    break;
                case stripos($newWorkPackage->getName(), 'milestone') !== false:
                    $newWorkPackage->setType(WorkPackage::TYPE_MILESTONE);
                    break;
                default:
                    $newWorkPackage->setType(WorkPackage::TYPE_TASK);
            }

            $newWorkPackage->setProject($project);
            $newWorkPackage->setWorkPackageStatus(
                $this
                    ->em
                    ->getReference(
                        WorkPackageStatus::class,
                        WorkPackageStatus::OPEN
                    )
            );

            $errors = $this->validator->validate($newWorkPackage);
            if (count($errors) > 0) {
                throw new \Exception((string) $errors);
            }

            $this->em->persist($newWorkPackage);
            $this->em->flush();
            $this->saveQueueItems($workPackageSaveQueue);

            $processedWorkPackages->add($newWorkPackage);
        }

        foreach ($processedWorkPackages as $processedWorkPackage) {
            $puid = null;
            foreach ($outlineNumber2Entity as $outlineNumber => $entity) {
                if ($entity === $processedWorkPackage) {
                    $puid = $outlineNumber;
                    break;
                }
            }
            // exclude weird stuff
            if (!$puid) {
                continue;
            }
            // exclude 1st level items
            $pos = strpos($puid, '.');
            if ($pos === false) {
                continue;
            }
            $parentPuid = substr($puid, 0, $pos);

            $parent = $outlineNumber2Entity[$parentPuid] ?: null;
            if (!$parent) {
                continue;
            }

            switch ($parent->getType()) {
                case WorkPackage::TYPE_PHASE:
                    $processedWorkPackage->setPhase($parent);
                    break;
                case WorkPackage::TYPE_MILESTONE:
                    $processedWorkPackage->setMilestone($parent);
                    break;
                default:
                    $processedWorkPackage->setParent($parent);
            }

            $this->em->persist($processedWorkPackage);
            $this->em->flush($processedWorkPackage);
        }
    }

    /**
     * Import workPackagesProjectWorkCostTypes based on xml content.
     *
     * @param $workPackagesProjectWorkCostTypes
     */
    public function importWorkPackageProjectWorkCostTypes($workPackagesProjectWorkCostTypes)
    {
        $workPackagesProjectWorkCostTypeSaveQueue = new ArrayCollection();

        if (is_array($workPackagesProjectWorkCostTypes) && is_object($workPackagesProjectWorkCostTypes['Resource'])) {
            $workPackagesProjectWorkCostType = $workPackagesProjectWorkCostTypes['Resource'];
            $workPackagesProjectWorkCostTypes['Resource'] = [$workPackagesProjectWorkCostType];
        }

        foreach ($workPackagesProjectWorkCostTypes['Resource'] as $workPackagesProjectWorkCostType) {
            $newWorkPackagesProjectWorkCostType = new WorkPackageProjectWorkCostType();
            foreach ((array) $workPackagesProjectWorkCostType as $workPackageTag => $element) {
                if (array_key_exists($workPackageTag, ImportConstants::WPPWCT_KEY_FUNCTION)) {
                    $action = ImportConstants::WPPWCT_KEY_FUNCTION[$workPackageTag];
                    if (is_callable([$newWorkPackagesProjectWorkCostType, $action])) {
                        if ($this->checkIsDate((string) $element)) {
                            $date = str_replace('T', ' ', (string) $element);
                            $newWorkPackagesProjectWorkCostType->$action(new \DateTime($date));
                        } else {
                            $newWorkPackagesProjectWorkCostType->$action((string) $element);
                        }
                    }
                } else {
                    switch ($workPackageTag) {
                        case ImportConstants::UID:
                            $workPackage = $this
                                ->em
                                ->getRepository(WorkPackage::class)
                                ->findOneBy([
                                    'externalId' => (int) $element,
                                ])
                            ;

                            if ($workPackage) {
                                $newWorkPackagesProjectWorkCostType->setWorkPackage($workPackage);
                            }
                            break;
                        case ImportConstants::CALENDAR_UID_TAG:
                            $calendar = $this
                                ->em
                                ->getRepository(Calendar::class)
                                ->findOneBy([
                                    'externalId' => (int) $element,
                                ])
                            ;
                            if ($calendar) {
                                $newWorkPackagesProjectWorkCostType->setCalendar($calendar);
                            }
                            break;
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField(
                                    $workPackageTag,
                                    $element,
                                    $newWorkPackagesProjectWorkCostType,
                                    WorkPackageProjectWorkCostType::class,
                                    $workPackagesProjectWorkCostTypeSaveQueue
                                );
                            }
                    }
                }
            }
            $this->em->persist($newWorkPackagesProjectWorkCostType);
            $this->em->flush();
            $this->saveQueueItems($workPackagesProjectWorkCostTypeSaveQueue);
        }
    }

    /**
     * Import assignments based on xml content.
     *
     * @param $assignments
     */
    public function importAssignments($assignments)
    {
        $assignmentSaveQueue = new ArrayCollection();

        if (is_array($assignments) && is_object($assignments['Assignment'])) {
            $assignment = $assignments['Assignment'];
            $assignments['Assignment'] = [$assignment];
        }

        foreach ($assignments['Assignment'] as $assignment) {
            $newAssignment = new Assignment();
            foreach ((array) $assignment as $assignmentTag => $element) {
                if (array_key_exists($assignmentTag, ImportConstants::ASSIGNMENT_KEY_FUNCTION)) {
                    $action = ImportConstants::ASSIGNMENT_KEY_FUNCTION[$assignmentTag];
                    if (is_callable([$newAssignment, $action])) {
                        if ($this->checkIsDate((string) $element)) {
                            $date = str_replace('T', ' ', (string) $element);
                            $newAssignment->$action(new \DateTime($date));
                        } else {
                            $newAssignment->$action((string) $element);
                        }
                    }
                } else {
                    switch ($assignmentTag) {
                        case ImportConstants::TASK_UID_TAG:
                            $workPackage = $this
                                ->em
                                ->getRepository(WorkPackage::class)
                                ->findOneBy([
                                    'externalId' => (int) $element,
                                ])
                            ;

                            if ($workPackage) {
                                $newAssignment->setWorkPackage($workPackage);
                            }
                            break;
                        case ImportConstants::RESOURCE_UID_TAG:
                            $workPackageProjectWorkCostType = $this
                                ->em
                                ->getRepository(WorkPackageProjectWorkCostType::class)
                                ->find((int) $element)
                            ;

                            if ($workPackageProjectWorkCostType) {
                                $newAssignment->setWorkPackageProjectWorkCostType($workPackageProjectWorkCostType);
                            }
                            break;
                        case ImportConstants::TIMEPHASED_TAG:
                            $this->importTimephased($newAssignment, (array) $element);
                            break;
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField($assignmentTag, $element, $newAssignment, Assignment::class, $assignmentSaveQueue);
                            }
                    }
                }
            }
            $this->em->persist($newAssignment);
            $this->em->flush();
            $this->saveQueueItems($assignmentSaveQueue);
        }
    }

    /**
     * Import timephases based on xml content.
     *
     * @param $assignment
     * @param $timephasedData
     */
    public function importTimephased($assignment, $timephasedData)
    {
        $timephaseSaveQueue = new ArrayCollection();

        if (is_array($timephasedData) && is_string(key($timephasedData))) {
            $node = new \SimpleXMLElement('<TimephaseData></TimephaseData>');
            foreach ($timephasedData as $key => $value) {
                $node->addChild($key, $value);
            }
            $timephasedData = [$node];
        }

        foreach ((array) $timephasedData as $timephased) {
            $newTimephase = new Timephase();
            foreach ((array) $timephased as $timephasedTag => $element) {
                if (array_key_exists($timephasedTag, ImportConstants::TIMEPHASE_KEY_FUNCTION)) {
                    $action = ImportConstants::TIMEPHASE_KEY_FUNCTION[$timephasedTag];
                    if (is_callable([$newTimephase, $action])) {
                        if ($this->checkIsDate((string) $element)) {
                            $date = str_replace('T', ' ', (string) $element);
                            $newTimephase->$action(new \DateTime($date));
                        } else {
                            $newTimephase->$action((string) $element);
                        }
                    }
                } else {
                    switch ($timephasedTag) {
                        default:
                            if (!is_array($element)) {
                                $this->addCustomField($timephasedTag, $element, $newTimephase, Timephase::class, $timephaseSaveQueue);
                            }
                    }
                }
            }
            $newTimephase->setAssignment($assignment);
            $this->em->persist($newTimephase);
            $this->em->flush();
            $this->saveQueueItems($timephaseSaveQueue);
        }
    }

    private function importCompany($companyName)
    {
        $company = $this
            ->em
            ->getRepository(Company::class)
            ->findOneBy([
                'name' => $companyName,
            ])
        ;
        if ($company) {
            return $company;
        }

        return (new Company())
            ->setName($companyName)
        ;
    }

    private function addCustomField($fieldName, $value, $object, $className, $saveQueue)
    {
        $customField = $this->customFieldRepo->findOneByFieldNameAndClass($fieldName, $className);

        if (!$customField) {
            $customField = (new CustomField())
                ->setClass($this->customFieldRepo->getRootEntityName($className))
                ->setFieldName($fieldName)
            ;
            $this->em->persist($customField);
            $this->em->flush();
        }

        if (!$this->updateCustomFieldValue((string) $value, $saveQueue, $object, $customField)) {
            $customFieldValue = (new CustomFieldValue())
                ->setCustomField($customField)
                ->setObj($object)
                ->setValue((string) $value)
            ;

            $this->addToSaveQueue($customFieldValue, $saveQueue);
        }
    }

    private function updateCustomFieldValue($value, ArrayCollection $saveQueue, $object, CustomField $customField)
    {
        if (!$saveQueue->count()) {
            return false;
        }

        /** @var CustomFieldValue $entity */
        foreach ($saveQueue as $entity) {
            if ($entity->getObj() == $object && $entity->getCustomField() == $customField) {
                $entity->setValue($value);

                return true;
            }
        }

        return false;
    }

    private function addToSaveQueue(CustomFieldValue $customFieldValue, ArrayCollection $saveQueue)
    {
        $saveQueue->add($customFieldValue);
    }

    public function saveQueueItems(ArrayCollection $saveQueue)
    {
        if (!$saveQueue->count()) {
            return;
        }

        foreach ($saveQueue as $entity) {
            $entity->setObjId($entity->getObj()->getId());
            $this->em->persist($entity);
            $saveQueue->removeElement($entity);
        }
        $this->em->flush();
    }
}
