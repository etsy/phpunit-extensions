Etsy PHPUnit Extensions
=======================

[PHPUnit][phpunit] is the standard in testing PHP code. [Etsy][etsy] uses a lot of PHP and tests help us maintain quality while taking continuous deployment to the extreme.  We have encountered a few use cases here an there that let us work with PHPUnit better and faster, and we thought it would be awesome to share with everyone.

Tutorials
--------------------
* [Asserts](https://github.com/etsy/phpunit-extensions/wiki/Asserts)
* [Constraints](https://github.com/etsy/phpunit-extensions/wiki/Constraints)
* [Database](https://github.com/etsy/phpunit-extensions/wiki/Database)
* [Helpers](https://github.com/etsy/phpunit-extensions/wiki/Helpers)
* [Mock Object](https://github.com/etsy/phpunit-extensions/wiki/Mock-Object)
* [Mockery](https://github.com/etsy/phpunit-extensions/wiki/Mockery)
* [Multiple Database](https://github.com/etsy/phpunit-extensions/wiki/Multiple-Database)
* [Ticket Listener](https://github.com/etsy/phpunit-extensions/wiki/Ticket-Listener)
* [PHPUI Command](https://github.com/etsy/phpunit-extensions/wiki/PHPUI-Command) Experimental!!

Installation
-------------------

### PEAR

See [PEAR Channel](http://etsy.github.com/phpunit-extensions)


Version > 0.3.x requires PHP 5.3.6

Version < 0.2.x requires PHP 5.2.9

### Composer

To add the extensions as a local, per-project dependency to your project, simply add a dependency to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a development-time dependency:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/etsy/phpunit-extensions"
            }
        ]
        "require-dev": {
            "etsy/phpunit-extensions": "0.6.0"
        }
    }

Contribute
--------------------

You're interested in contributing to Etsy PHPUnit Extensions? 

Here are the basic steps:

fork phpunit-extensions from here: http://github.com/etsy/phpunit-extensions

1. Clone your fork
2. Hack away
3. If you are adding new functionality, document it in the Wiki
4. If necessary, rebase your commits into logical chunks, without errors
5. Push the branch up to GitHub
6. Send a pull request to the etsy/phpunit-extensions project.

We'll do our best to get your changes in!

[phpunit]: https://github.com/sebastianbergmann/phpunit
[etsy]: http://www.etsy.com
[blog post]: TBD


Contributors
-----------------

In lieu of a list of contributors, check out the commit history for the project: 
http://github.com/etsy/phpunit-extensions/commits/master
