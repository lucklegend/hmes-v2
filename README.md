## Hydromech Engineering Services ##

###RELEASE NOTES###

This document provides the release notes for the Unified Laboratory Information Management System. 
It describes installation instructions, configuration changes compared to the previous releases of hmes, 
additional features and etc ...


####SYSTEM REQUIREMENTS####

This release requires at least PHP 5.1 and above. This release has been tested with Apache HTTP server on 
Windows and Linux. It may also run on other Web servers and platforms, provided PHP 5.1 is supported.


####COMPATIBILITY####
This release has been tested with the following:

    | Operating Systems     | Web Server / PHP / MySQL Version                          |
    | --------------------- |-----------------------------------------------------------|
    | Windows 7 Pro         | XAMPP 1.7.3 - Apache 2.2.14 / PHP 5.3.1 / MySQL 5.1.41    |
    |                       | XAMPP 5.6.3 - Apache 2.4.10 / PHP 5.6.3 / MySQL 5.6.21    |
    |                       |                                                           |
    | Ubuntu 14.04.1 LTS    | Apache 2.4.7 / PHP 5.5.9 / MySQL 5.5.40                   |
    

####PRE-INSTALLATION####

From your existing hmes installation do the following:
- Secure a copy of the `hmes/protected/config` and `hmes/images` directory. 
  You will need information from the files in that directory. 
    
- Export(backup) the databases (hmesaccounting, hmescashiering, hmeslab, hmesportal, phaddress).
    
(Skip this if you are installing from scratch)


####INSTALLATION####

1. Download or clone hmes from this repository.
2. Extract the release file to a Web-accessible directory:
    ```    
    X:/xampp/htdocs for xampp on windows environment
    /var/www or /var/www/html for linux
    ```  

3. hmes Configurations

    - Database credentials for hmes have been moved to `/hmes/protected/config/db.php` which resides on the same directory as the main.php file. In this way we will always have the same `main.php` file. 

    - Update the usernames and passwords for the different databases specified in the db.php file.
    
    - Replace the following files in the `/hmes/protected/config` with the ones you obtained from the Pre-Installation instruction:
        ```
        site-settings.ini
        form-settings.ini
        ```
    
    - Replace the directory `/hmes/images` from the Pre-Installation instruction:
    
4. Databases
 
    ##### A. New Installation #####

    - If you are installing from scratch - create and import clean databases from the `hmes/protected/data` directory.
    
    ##### B. Migrating from Existing Installation #####

    - Create and import the database (hmesaccounting, hmescashiering, hmeslab, hmesportal, phaddress) you obtained from the Pre-Installation instruction.
        
    - Check the structure of the `hmeslab.request` table. The datatype for field `requestDate` should be 'date' and there should be a field `create_time` with a 'TIMESTAMP' datatype. 
            
    - If not, execute the following sql commands separately.
        ```
        ALTER TABLE `request` CHANGE `requestDate` `requestDate` DATE NOT NULL
        ALTER TABLE `request` ADD `create_time` TIMESTAMP
        UPDATE `hmeslab`.`request` SET `create_time` = `requestDate`
        ALTER TABLE `request` CHANGE `create_time` `create_time` TIMESTAMP NOT NULL
        ALTER TABLE `request` CHANGE `create_time` `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ```
    This modifications with fix the issue on generating duplicate request reference when creating requests.
            
            
    - Truncate the tables `hmesportal.AuthItem` and `hmesportal.AuthItemChild`.
    
        ```
        TRUNCATE TABLE `AuthItem`
        TRUNCATE TABLE `AuthItemChild`
        ```        
    Import the `AuthItem.sql` and `AuthItemChild.sql` from `hmes/protected/data` directory to the respective tables in hmesportal database.
    
    ##### C. Additional Database #####
    
    - A new database has been added for the Referral Module. Create new database `onelabdb` and import  `hmes/protected/data/onelabdb.sql`. 


    - Select onelabdb and separately execute each of the four(4) sets of commands in the             `hmes/protected/data/onelabdb_views.txt`.
  

5.  File/Folder Permissions (for linux installation)

    - Grant read/write permissions to several files/folders by running the following commands:
 
        ```
        sudo chmod -R 777 hmes/assets
        sudo chmod -R 777 hmes/protected/runtime
        sudo chmod 777 hmes/config/site-settings.ini
        sudo chmod 777 hmes/config/form-settings.ini
        sudo chmod 777 hmes/config/api-settings.ini
        ```
        
        create the folder `hmes/assets` if does not exist.
    
    - The following tables are case-sensitive:
    
        ```
        hmesportal.AuthItem
        hmesportal.AuthItemChild
        hmesportal.Rights
        ```
        
        rename these tables as indicated above.

more info soon...
