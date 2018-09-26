# SlashTrace - Awesome error handler - Raygun handler

This is the [Raygun](https://raygun.com/) handler for [SlashTrace](https://github.com/slashtrace/slashtrace). 
Use it to send your errors and exceptions to your Raygun account.

## Usage

1. Install using Composer:

   ```
   composer require slashtrace/slashtrace-raygun
   ```
   
2. Hook it into SlashTrace:

   ```PHP
   use SlashTrace\SlashTrace;
   use SlashTrace\Raygun\RaygunHandler;

   $handler = new RaygunHandler("Your Raygun API key");
    
   $slashtrace = new SlashTrace();
   $slashtrace->addHandler($handler);
   ```
   
   Alternatively, you can pass in a pre-configured Raygun client when you instantiate the handler:
   
   ```
   $raygun = new Raygun4php\RaygunClient("Your Raygun API key");
   $handler = new RaygunHandler($raygun);
   
   $slashtrace->addHandler($handler);
   ```   
   
Read the [SlashTrace](https://github.com/slashtrace/slashtrace) docs to see how to capture errors and exceptions, and how to attach additional data to your events.
