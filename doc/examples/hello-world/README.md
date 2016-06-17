# Hellow world example

It's very simple to create your own "Hello world" application.

Open %mezon-dir%/index.php file and write this simple code (it can be found in %mezon-path%/doc/examples/hello-world/index.php):
```PHP
require_once( './mezon.php' );

class           HelloWorldApplication extends Application
{
    /**
    *   Main page.
    */
    public function action_index()
    {
        return( 'Hello world!' );
    }
}

$App = new HelloWorldApplication();
$App->run();
```

That's all!

But lets look at this code once again. 

Here we can see Mezon php files.

```PHP
require_once( './mezon.php' );
```

We also can see HelloWorldApplication wich is derived from the Application class.

```PHP
class           HelloWorldApplication extends Application
{
    // ...
}
```
Application class is the most important class of the Mezon framework. In your every application you will override a part of it's methods.

In this example we override only one method - action_index wich draws main page of your application.

```PHP
public function action_index()
{
    return( 'Hello world!' );
}
```

What else can we see? Each page drawing method (or 'view') must return generated content wich will be used for page renderring functions of the Application class and actual template engine.

Then we create the instance of our application and running it.

```PHP
$App = new HelloWorldApplication();
$App->run();
```

Now you are ready for [more complex example](https://github.com/alexdodonov/mezon/tree/master/doc/examples/simple-site/#simple-site-example)