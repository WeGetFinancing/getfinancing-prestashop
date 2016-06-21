General Instructions
-----------------------------
1. Create your merchant account to offer monthly payment options to your consumers directly on your ecommerce from here (http://www.getfinancing.com/signup) if you haven't done it yet.
2. Download our module from the latest release here (https://github.com/GetFinancing/getfinancing-prestashop/releases/latest) or all the code in a zip file from here (https://github.com/GetFinancing/getfinancing-prestashop/archive/master.zip)
3. Setup the module with the information found under the Integration section on your portal account https://partner.getfinancing.com/partner/portal/. Also remember to change the postback url on your account for both testing and production environments.
4. Once the module is working properly and the lightbox opens on the request, we suggest you to add some conversion tools to your store so your users know before the payment page that they can pay monthly for the purchases at your site. You can find these copy&paste tools under your account inside the Integration section.
5. Check our documentation (www.getfinancing.com/docs) or send us an email at (integrations@getfinancing.com) if you have any doubt or suggestions for this module. You can also send pull requests to our GitHub account (http://www.github.com/GetFinancing) if you feel you made an improvement to this module.

Installing the module
---------------------

- Use the Prestashop module menu to upload and install the module

Activating the module
---------------------
 - Go to the admin backoffice
 - Go to Modules and Services menu.
 - Select the GetFinancing Payment Module
 - On the right, Click the +Install button
 - Configure the settings that GetFinancing provided you.
 - Update the changes.

Postback url
------------

This is the default url for postbacks which you need to configure in your account.
http://YOUR_DOMAIN/modules/paylater/validation.php

Testing
-------

In the complete integration guide that you can download from our portal,
you can see various test personae that you can use for testing.

Switching to production
-----------------------

 - Go to the admin backoffice
 - Go to Modules and Services menu.
 - Select the GetFinancing Payment Module and click Edit Button.
 - In the settings, switch to Production.

Note that after this change, you should no longer use the test personae you
used for testing, and all requests go to our production platform.

Compatibility
-------------
 - This module has been tested with Prestashop versions 1.5.x and 1.6.x
