# ProcessWire Pages Web Service (ServicePages Module)

Enables ProcessWire-selector style $pages->find() queries via a web service.
This web service is suitable for calling from Javascript/jQuery, PHP, etc.
All output is generated in JSON format. 

For the most part, you can do anything with this web service that you would
do with a $pages->find() call. However, for security reasons, the limits of 
what can be searched and retrieved must be specified in the module configuration.

This module essentially provides a safe front-end to the AJAX API provided in the
ProcessPageSearch module, enabling that capability to be used outside of the admin.

Once installed, this module creates a page called /service-pages/. View that page
for detailed instructions and examples on how to use it. 


