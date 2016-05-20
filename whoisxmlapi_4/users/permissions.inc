<?php
#
# Permissions Configuration File
#

# Defines all default permissions in a domain
# Syntax for new permission options:
# $__PERMISSIONS[<realm>][<permission label>] = 0 or 1;

# php_users Permissions
$__PERMISSIONS['php_users']['list_users'] = 1;
$__PERMISSIONS['php_users']['edit_self'] = 0;
$__PERMISSIONS['php_users']['edit_users'] = 0;
$__PERMISSIONS['php_users']['set_perm'] = 0;
$__PERMISSIONS['php_users']['edit_perm'] = 0;
$__PERMISSIONS['php_users']['change_other_pass'] = 0;

?>
