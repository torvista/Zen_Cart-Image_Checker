# Image-Checker
Image Checker: Zen Cart Admin tool

Originally forked from Missing Images but I decided to revise it completely and also (future) integrate other image checking plugins.
Very much a work in progress but wont destroy anything!

//////////////////////////////
****************************************************************************
    Missing Images Checker for ZenCart
    Copyright (C) 2014  Paul Williams/IWL Entertainment

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************
Features
========
Scans your ZenCart database and images folder for any missing images and reports
on them.

Version Date
==============


v2.0 2016 08 04
  * torvista - forked
  bug: was causing debug files, not easy to find, eventually rejigged the entire logic and created new branch!
  added admin page registration function (auto-deleting)
  missing_images.php
	changed to html5, removed obsolete tags, changed css to suit 1.55 admin pages
	added products model
	moved all hard-coded text to language defines
	added autosubmit checkbox to select listing of all products or only errors
	
	TODO
  * Work on "action buttons" to either remove the image from the database and/or
    delete the actual image file from the store.
  * Instead of just going through one LARGE scan of the database, possibly look
    into separating and searching by category instead.
    
v1.0.2	2014-07-24 13:50
  * Removed the choice of running the script as MySQLi or MySQL. The script 
    will now check if it can run mysqli_connect. If not, it will run 
    mysql_connect. If that fails, the script will not run. The user also
    has the option to force MySQL although this should NOT really be used. I
    will still reserve the right to remove this option at a later time once
    MySQL is removed from PHP.
  * Consolidated the location of the query that is being run by the script to
    one location. (There is no difference in SQL language between MySQL and 
    MySQLi. So having two seperate queries which produced the same result table
    was a bit redundant.)

v1.0.1	2014-07-24 12:58
  * Improved call for database_tables.php as suggested by lat9. 
    Thanks for the code suggestion.

v1.0	2014-07-16 02:37
  * Initial release

Author
======
Paul Williams (retched@iwle.com)

Description
===========
This script will run through your ZenCart database, provided by you in the
connection information of the script, and then retrieve all images from the 
products table of ZenCart. Then, the script will run through each of those 
images making sure that the image exists and is saved in the correct format. 
(For example, a .gif is actually a GIF.) This is useful if you use a batch 
product uploader like easyPopulate and you don't know which of your images 
are missing.

Known Issues
============
    * People on shared servers or those without the ability to turn Safe Mode off
      will experience more time outs on larger databases. This is because I 
      cannot force the script to essentially reset the execution_timer. This will
      be worked on in a later version. You can try to edit the script on line 118 
      and change the SQL query a little bit. If you do, you may end up having 
      to run this script a couple of times.

Support thread
==============
http://www.zen-cart.com/showthread.php?213966

GitHub
======
https://github.com/retched/missing-images-zen

Affected files
==============
None

Affects DB
==========
None

DISCLAIMER
==========
Installation of this contribution is done at your own risk.
While there are no changes made to your database or files, it is still suggested 
to backup your ZenCart database and any and all applicable files before 
proceeding.

Install (Needs to be rewritten)
=======
  0. Backup your database.
  1. Unzip and edit /missing_images.php under the area "Configuration Variables". 
     Be careful of ANY quotation marks. The quotation marks MUST stay. Be sure 
     to read the notes under each $variable.
  2. Save the edits.
  3. Upload your modified /missing_images.php to your store directory. (It 
     does not have to be in the root of the store directory but it does 
     have to be on the same server as it.)
  4. Run the script via web browser.

Un-Install (Needs to be rewritten)
==========
1. Delete all files that were copied from the installation package.
