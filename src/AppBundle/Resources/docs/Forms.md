Forms
=====
All project forms can be found under the following folder:
>
src/AppBundle/Form/
>

The folder contains **Create** and **Edit** form types for all the entities which are administrated in the admin section of the application.

Beside create and update forms, additional form types can be found here such as:

* [Login Type](#login-type)
* [Register Type](#register-type)
* [Reset Password Type](#reset-password-type)
* [Change Password Type](#change-password-type)
* [Filesystem Forms](#filesystem-forms)
* [Account Type](#account-type)
* [Contact Type](#contact-type)
* [InviteUser Type](#inviteuser-type)

## Login Type
>
path: src/AppBundle/Form/User/LoginType.php
>

Form used to log a specific user into application.

## Register Type
>
path: src/AppBundle/Form/User/RegisterType.php
>

Register type form allows user to register into application.

## Reset Password Type
>
path: src/AppBundle/Form/User/ResetPasswordType.php
>

Allows users to reset their password

## Change Password Type
>
path: src/AppBundle/Form/User/ChangePasswordType.php
>

Allows user to change their password.

## FileSystem Forms
>
path: src/AppBundle/Form/FileSystem/
>

Contains forms related to the projects filesystem functionality and contains the following form types:

* ```CreateType.php``` - Form that handles creation of a new FileSystem entity
* ```DropboxAdapterType.php``` - Used to display specific fields for the dropbox filesystem
* ```LocalAdapterType.php``` - Used to display specific fields for the local filesystem
* ```MediaUploadType.php``` - Form used to upload a new media file into the filesystem.

## Account Type
>
path: src/MainBundle/Form/User/AccountType.php
>

This form is used to allow users to modify their account personal information.

## Contact Type
>
path: src/MainBundle/Form/User/AccountType.php
>

Used to send messages to website administrators.

## InviteUser Type
>
path: src/MainBundle/Form/User/AccountType.php
>

This form is used to invite an user to a specific team based on user's email.

**More information about form fields can be found in Sami Documentation -> Form**

[<- README](README.md)
