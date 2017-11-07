uniCal
======

A simple tool to create an iCS file from Uni.lu Guichet Etudiant.
This tool has to be downloaded and set up on your own computer to respect your privacy.

License
------------

 Check out the [LICENSE](LICENSE) file.
 
Disclamer
-------------
I am not affiliated, associated, authorized, endorsed by, or in any way officially connected with the University of Luxembourg (Uni.lu). I'm not studying at Uni.lu.

All content provided on this page is for personal purposes only. The owner will not be liable for any losses, or damages from the use this guide.

Requirements
------------

  * PHP 5.5.9 or higher
  * MYSQL Server installed
  * [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).


Installation
------------

Install the uniCal Application executing
this command anywhere in your system:

```bash
$ git clone https://github.com/benoitfrisch/uniCal.git
$ cd uniCal
```
You have to install [composer](https://getcomposer.org/download/) on your computer if you don't have it already.

After installing composer run following command:
```bash
$ ./composer.phar install
```
Please update your MYSQL Settings in the parameters.yml file.
You can now generate the database schemas and create a first user, which will be promoted to Admin.
```bash
$ bin/console doctrine:schema:create
```

Open application
-----

If you want to use a fully-featured web server (like Nginx or Apache) to run the application, configure it to point at the `web/` directory of the project.
For more details, see:
http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
http://symfony.com/doc/current/deployment.html

You can access the application from the URL you installed it on.

If you haven't set up a special WebServer, you can run the application locally on your computer using the following command.

```bash
$ bin/console server:run
```
Copy the URL provided in the console and open it in your browser.

Usage of application
------------------
In following instructions, I assume that you have some basic knowledge of JSON files and Browser Inspector and I won't go into any details. Please use
    Google for further assistance.

### Get the data from Guichet Etudiant
* You have to visit your "Guichet Etudiant"
* Click "view source" in your browser
* Select month view
* You should see now an XHR Request on resources tab of your browser
### Create a JSON file
* Copy this data into a new file named "calendar.json"
* Check if your JSON file is valid [Online checker](https://jsonformatter.curiousconcept.com)
* Put "calendar.json" into the files/ directory of this project.
### Import the data
* Run 
```bash
bin/console import:json
```
### Select your groups
* Go to the admin interface (/admin) and create a user.
* Add groups to this user. (From User view, or from Groups view)
* Check out user ID.
### Get the final ICS file
* Go to /ics/{userID}. The download should begin.

You can now import this file to any calendar, for example iCal or Google Calendar.
