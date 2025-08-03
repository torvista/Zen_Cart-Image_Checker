# Image Checker: Zen Cart Admin tool

## Function
Checks 
- that the images linked to the products in the database exist
- that the format of the linked image corresponds with its filename extension
- that the format is a bmp/gif/jpg/png/webp and not some other less common web format

## Compatibility
Compatible from Zen Cart 1.57-2.10, on php8.  
Version 2.3.0 03/08/2025  

Note further minor tweaks and fixes may be posted on the GitHub repository.

## Admin core files modified?
No.

## Database Modifications?

Yes: only for the registration of the admin page.
No product data is modified, the script is read-only.

## Support thread for Missing Images/Image Checker
http://www.zen-cart.com/showthread.php?213966

## Github for Image Checker
https://github.com/torvista/zen-cart_Image-Checker

## DISCLAIMER
Installation of this contribution is done at your own risk.
Whilst there are no changes made to your database or core files, it is still best practice to install ANY so-called "Plugin" on a DEVELOPMENT server before letting it loose on a production shop.

## License
You should have received a copy of the GNU General Public License along with this program.  
If not, see <http://www.gnu.org/licenses/>.

## Installation
1) Try this (as with ANY plugins/modifications) on your DEVELOPMENT SERVER first. Trust no-one!
1) Backup your database and fileset.
1) Copy the CONTENTS of the "zc_plugins" folder to your "zc_plugins" folder. There should be no overwrites.
1) Admin->Modules->Plugin Modules->Image Checker->Install

Note that this includes jquery_tablesorter 2.32.0 from  
https://mottie.github.io/tablesorter/docs/  
to allow sorting by the column headings and some table formatting.

## Use
Open the Tools->Image Checker page  
Click on Go to run the check with the default filters.
It will show only the errors it finds for ENABLED products. The error count is the total of all the products.

To include disabled products in the list, select the checkbox and the page will refresh.

To show all products, select the checkbox and the page will refresh using the pagination defined by the Admin->Maximum values page.  The error count is for that page only.

## Known Issues
People on shared servers or those without the ability to turn Safe Mode off may experience more time-outs on larger databases.  
This is because the script cannot reset the execution_timer.

## Uninstall
1) Admin->Modules->Plugin Modules->Image Checker->Uninstall
1) Remove files from zc_plugin.

## History - Changelog
2025 08 03 torvista:  
added webp, Go button, removed some language constants already available in core files, added all tablesorter files for easier upgrading.

2024 08 25 v2.3.0 torvista:  
converted to an encapsulated plugin  
replaced <?php echo with <?=  
no functional changes

2023 10 31 torvista: v2.2.  
Use ZC158 admin header. No functional changes.

2020 12 29 torvista
No functional changes. Minor changes per IDE recommendations. Tested with Zen Cart 158.

2020 04 torvista: v2.1.
No functional changes. Fixes for php notices, strict comparisions and minor changes per IDE recommendations.

2018 12 torvista: uploaded to Plugins as v2.0.

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
    MySQLi. So having two separate queries which produced the same result table
    was a bit redundant.)

v1.0.1	2014-07-24 12:58
  * Improved call for database_tables.php as suggested by lat9. 
    Thanks for the code suggestion.

v1.0	2014-07-16 02:37
  * Initial release

Author
Paul Williams (retched@iwle.com)
