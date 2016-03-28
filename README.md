# Bitrix module for create user's order properties
This module allows you to create and delete properties of the order.
The default setting 5 properties:
- utm_source
- utm_medium
- utm_campaign
- utm_term
- utm_content
These properties allow you to save utm-label in order to create the properties of the order.

To set the folder with the module you want to copy to a folder / locale / modules /.
The module can be installed in the section "Marketplace> Installed solutions."
After ustvnovki you can connect the module anywhere using:

\ Bitrix \ Main \ Loader :: includeModule ( "tzepart.proporder");

It is also possible to add or remove a property on the page in the administrative section of the site.

![ScreenShot](http://joxi.ru/zANaGezclgXxP2)
