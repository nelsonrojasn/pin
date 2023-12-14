# Pin

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)

If you love PHP, but not the complexity of a massive framework, I've created Pin for simplify myself and my future works.

Pin requires PHP 7.0 at least.

I try to explain it in a few steps

**Pin** uses the Front Controller patern, it means that every request will be captured by the index.php file. The Front Controller translate the request into a **page** and an **action**. The **page** is just a php file, not a class, just a set of functions. The **action** corresponds to a function inside this file.

Is like a MVC framework, but using just functions. I've separated the business logic (or just logic) on every function of the **pages**. You can create classes to encapsulate the logic outside of pages. It's up to you.

Then, the result of applying logic is passed to the view.


## Location of its components

- Pages are located into pin/pages.
- Views must be placed into pin/views.
- You can include helpers into pin/helpers folder.
- If you need to create classes for handling logic, please put them inside pin/libs folder.
- Partials must be created in the pin/partials folder.


## Utility clases

There is a database class included inside the pin/libs (the **Db class**) that allow you to query and modify data in a database.

To handle session variables, you can use the **Session class** that is placed in the same folder of Db class.

Finally, there is a class to handle post and get requests elements: the **Request class** (in the same previous folder)

The global class **Load** allow you to "include" different contents inside the pages or even in classes or views.

Last, but not least, there are a set of functions that supporting write less code for loading css, js, draw forms, and other html ussesful elements.

It is possible to see any pages examples inside the pin/pages folder to get an idea about it works.

I'll be write more examples in the Wiki seccion.

Regards!
