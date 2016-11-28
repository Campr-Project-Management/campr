Services
========

The following services are currently used:

* [Data Table Service](#data-table-service)
* [Import Service](#import-service)
* [Mailer Service](#mailer-service)
* [User Service](#user-service)
* [FileSystem Service](#filesystem-service)

## Data Table Service
>
path: src/AppBundle/Services/DataTableService.php
>

Used to populate data tables with information

* Use ```paginateByColumn``` function to retrieve data for a specific entity, pass search field and filter options.

## Import Service
>
path: src/AppBundle/Services/ImportService.php
>

Used to import information from different sources.

* ```importProjects``` function is used in in ```ProjectImportCommand.php``` to save project information based on an XML file.
This function relies on: ```importCalendars```,  ```importWorkPackages```, ```importWorkPackageProjectWorkCostTypes```, ```importAssignments``` functions to populate the related entities

## Mailer Service
>
path: src/AppBundle/Services/MailerService.php
>

Service used to send all types of emails.
 
* ```sendEmail``` function is used to send an email. This function takes multiple parameters such as template name, from email, to email, attachments or cc emails.
Usage: `$mailerService->sendEmail('AppBundle:Email:user_register.html.twig', 'info', 'email@email.com, $parameters);`

## Request Parser Service
>
path: src/AppBundle/Services/RequestParserService.php
>

Service used to parse the request parameters such as search string or filter options.

## User Service
>
path: src/AppBundle/Services/UserService.php
>

This service is used for different operations on user entity.

## FileSystem Service
>
path: src/AppBundle/Services/UserService.php
>

Service used to handle the projects filesystem.

* ```createFileSystem``` - creates a new filesystem based on settings saved in FileSystem entity.
* ```getFileSystemMap``` - returns the map with all existing filesystem.
* ```saveMediaFile``` - saves a specific Media entity into a filesystem.
* ```removeMediaFile``` - removes a specific Media entity from a filesystem.

**More information about functions and parameters of each services can be found in Sami Documentation -> Services.**

[<- README](README.md)
