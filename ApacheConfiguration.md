#How to configure your application on apache

# Introduction #

Add your content here.


# Details #

App following code to your httpd.conf:
```
Alias /<URI_PATH> <APPLICATION_ROOT>/public

<Directory <APPLICATION_ROOT>/public>
    Order Allow,Deny
    Allow from all
    AllowOverride All
    SetEnv roboframe_env <ENVIRONMENT>
</Directory>
```