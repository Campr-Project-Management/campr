<?php

namespace AppBundle\Utils;

/**
 * Class ImportConstants
 * Constants used for project import.
 */
class ImportConstants
{
    // TAGS
    const PROJECT_NAME_TAG = 'Name';
    const PROJECT_COMPANY_TAG = 'Company';
    const CALENDARS_TAG = 'Calendars';
    const TASKS_TAG = 'Tasks';
    const RESOURCES_TAG = 'Resources';
    const WEEKDAYS_TAG = 'WeekDays';
    const ASSIGNMENTS_TAG = 'Assignments';
    const BASE_CALENDAR_TAG = 'BaseCalendarUID';
    const CALENDAR_UID_TAG = 'CalendarUID';
    const TASK_UID_TAG = 'TaskUID';
    const RESOURCE_UID_TAG = 'ResourceUID';
    const TIMEPHASED_TAG = 'TimephasedData';
    const UID = 'UID';
    const WORKING_TIMES_TAG = 'WorkingTimes';
    const OUTLINE_NUMBER = 'OutlineNumber';

    const PROJECT_KEY_FUNCTION = [
        'SaveVersion' => 'setNumber',
        'Name' => 'setName',
        'CreationDate' => 'setCreatedAt',
        'LastSaved' => 'setUpdatedAt',
        'Company' => 'setCompany',
    ];

    const CALENDAR_KEY_FUNCTION = [
        'UID' => 'setExternalId',
        'Name' => 'setName',
        'IsBaseCalendar' => 'setIsBased',
        'IsBaselineCalendar' => 'setIsBaseline',
    ];

    const DAY_KEY_FUNCTION = [
        'DayType' => 'setType',
        'DayWorking' => 'setWorking',
    ];

    const WORKTIME_KEY_FUNCTION = [
        'FromTime' => 'setFromTime',
        'ToTime' => 'setToTime',
    ];

    const WORKPACKAGE_KEY_FUNCTION = [
        'CreateDate' => 'setCreatedAt',
        'UID' => 'setPuid',
        'ID' => 'setExternalId',
        'Name' => 'setName',
        'Start' => 'setScheduledStartAt',
        'Finish' => 'setScheduledFinishAt',
        'Type' => 'setType',
    ];

    const WPPWCT_KEY_FUNCTION = [
        'IsGeneric' => 'setIsGeneric',
        'IsInactive' => 'setIsInactive',
        'IsEnterprise' => 'setIsEnterprise',
        'CreationDate' => 'setCreatedAt',
        'IsCostResource' => 'setIsCostResource',
        'IsBudget' => 'setIsBudget',
        'ID' => 'setExternalId',
    ];

    const ASSIGNMENT_KEY_FUNCTION = [
        'Confirmed' => 'setConfirmed',
        'Start' => 'setStartedAt',
        'Finish' => 'setFinishedAt',
        'CreationDate' => 'setCreatedAt',
        'Milestone' => 'setMilestone',
        'PercentWorkComplete' => 'setPercentWorkComplete',
        'UID' => 'setExternalId',
    ];

    const TIMEPHASE_KEY_FUNCTION = [
        'Type' => 'setType',
        'Unit' => 'setUnit',
        'Value' => 'setValue',
        'Start' => 'setStartedAt',
        'Finish' => 'setFinishedAt',
    ];
}
