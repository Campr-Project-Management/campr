Campr - Project Management Tool
===============================

#### Genereate Sami documentation

To generate Sami documentation for the code just write the correct annotations and descriptions for classes and run the following command:

`php bin/sami/sami.phar update bin/sami/config.php` OR

`php bin/sami/sami.phar update bin/sami/config.php -v`  to display any existing errors.

The command will generate/update the html files for all php classes inside the /src folder.

**Use the following pages to find more about the structure of this project:**

* [Admin Controllers](src/AppBundle/Resources/docs/AdminControllers.md)
* [API Controllers](src/AppBundle/Resources/docs/ApiControllers.md)
* [Entities](src/AppBundle/Resources/docs/Entities.md)
* [Forms](src/AppBundle/Resources/docs/Forms.md)
* [Services](src/AppBundle/Resources/docs/Services.md)
* [Javascripts](src/AppBundle/Resources/docs/Javascripts.md)
