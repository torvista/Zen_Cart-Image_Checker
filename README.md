Image Checker: Zen Cart Admin tool


Function
========
Checks 
- that the images linked to the products in the database exist
- that the format of the linked image corresponds with its filename extension
- that the format is a jpg/gif/bmp/png and not some other less common web format


Admin core files modified?
=========================
No.

Database Modifications?
======================
Yes: for the registration of the admin page.
No: no product data is modified, the script is read-only.

DISCLAIMER
==========
Installation of this contribution is done at your own risk.
Whilst there are no changes made to your database or core files, it is still best practice to install ANY so-called "Plugin" on a DEVELOPMENT server before letting it loose on a production shop.

License
========
You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.

Installation
============
1) Copy the content of the YOUR_ADMIN to your Admin folder. There should be no overwrites.
I recommend you ALWAYS compare any "Plugin" fileset (Beyond Compare/Winmerge etc.) first to ensure there are indeed no overwrites.
DO NOT TRUST ANY PLUGIN to be perfectly written for ALL sites.

2) On first refresh of any admin page, some installation messages should be shown and the file responsible for registering the page in the database will self-delete.
Attend to any error messages.

Use
===
Open the Tools->Image Checker page
It will run the tool immediately but showing only the errors it finds for ENABLED products. The error count is the total of all the products.
To include disabled products in the list, select the checkbox and the page will refresh.
To show all products, select the checkbox and the page will refresh using the pagination defined by the Admin->Maximum values page.  The error count is for that page only.

Known Issues
============
People on shared servers or those without the ability to turn Safe Mode off will experience more time outs on larger databases. This is because I cannot force the script to essentially reset the execution_timer. This will be worked on in a later version. You can try to edit the script on line 118 and change the SQL query a little bit. If you do, you may end up having to run this script a couple of times.


Uninstall
=========
Remove all the files copied during the installation.
Copy and run this single line of sql in the Admin->Install SQL Patches tool

DELETE FROM admin_pages WHERE page_key = 'toolsImageChecker';


History - Changelog
===================
https://github.com/torvista/Image-Checker.git

2016 08 torvista
Originally forked from Missing Images but I decided to revise it completely and also (in future) integrate other image checking plugins.
A work in progress but won't destroy anything!

v1.0 as Image Checker 2016 08
  bug: was causing debug files, not easy to determine why...eventually rejigged the entire logic.
  added admin page registration function (auto-deleting)
	changed page to html5, removed obsolete tags, changed css to suit 1.55 admin pages
	added products model to listing
	moved all hard-coded text to language defines
	added autosubmit checkboxs to select listing of all products or only errors +/- disabled products, +/- products with no images defined
	added column sorting by jquery tablesorter
	added pagination and corrected error counting
	changed listing format
	added edit button to product

	===================================
Previous history as Missing Images
Github Zen4All
https://github.com/Zen4All/missing-images-zen


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
Paul Williams (retched@iwle.com)


Support thread for Missing Images
=================================
http://www.zen-cart.com/showthread.php?213966



