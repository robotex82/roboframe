#This page describes the installation of the roboframe php framework

# Prerequisites #

  * PHP > 5.3
  * Activated Short Open Tags


# Howto Install #

  * Get the roboframe framework from svn
```
cd <php_dir>
mkdir roboframe
cd roboframe
svn co svn checkout http://roboframe.googlecode.com/svn/trunk/
```
  * Get the needed libraries

# Creating an application #
  * Set the environment
```
set roboframe_env=development
```
  * Generate the application skeleton
```
php <php_dir>/roboframe/scripts/generate.php application example
```
  * Configure your webserver