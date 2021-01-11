## Registering Artisan Commands:  
We no longer need to register Artisan commands in app/Console/Kernel.php. Simply make your command, and use it!  
(NOTE: If you would like to create sub-directories in the Commands folder, make sure to update the namespace to reflect the directory structure.)  

Here is how to make commands:  
`php artisan make:command MyNewCommand`