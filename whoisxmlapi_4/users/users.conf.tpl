<?php
#
# Users and Permissions Config File
#

# The name of the company people are registering with. Users will see this in 
# emails sent from the php_users system.
$COMPANY_NAME     = "Whois XML API, Inc.";



# This is the base url that maps to the installation directory
# of php-users. This must *not* end in a trailing slash '/'.
$USERS_BASE_URL   = "@USERS_BASE_URL@";

# Emails will be sent from this person
$WEB_MASTER       = "Administrator";
$WEB_MASTER_EMAIL = "support@whoisxmlapi.com";

# The name of the cookie that contains the user's information
# Note: Due to a bug in PHP, COOKIE_DOMAIN must contain at least two decimals  
$USER_COOKIE   = "u_info";
$COOKIE_DOMAIN = ".localhost.localdomain";  # $SERVER_NAME;
$COOKIE_PATH   = "/";

# If COOKIE_KEY is not set then mcrypt support will be turned off and cookies
# will be stored on the client machine unencrypted. If the parameter is present
# then the value of COOKIE_KEY will be used to encrypt user cookies. If you 
# change the values of COOKIE key, it is recommended you change the value of 
# USER_COOKIE as well to force the user to login again.
# Again, to turn off cookie encryption, comment out the next line:
$COOKIE_KEY    = "my key";

# These files are included at the top and bottom of every page
$PHP_USERS_HEADER_FILE = "fragments/users_header.php";
$PHP_USERS_FOOTER_FILE = "fragments/users_footer.php";

# database connect information
$USERS_DB     = "@USERS_DB@";
$USERS_DBUSER = "@USERS_DBUSER@";
$USERS_DBPASS = "@USERS_DBPASS@";
$USERS_DBHOST = "@USERS_DBHOST@";

include_once("permissions.inc");
?>
