# Behat & PhantomJS Example
An example of using the Behat Selenium2 Mink Driver to power PhantomJS, and integrate with both CircleCI and TravisCI

[![Build Status](https://travis-ci.org/jmauerhan/phantomjs-behat-selenium-example.svg?branch=master)](https://travis-ci.org/jmauerhan/phantomjs-behat-selenium-example) [![Circle CI](https://circleci.com/gh/jmauerhan/phantomjs-behat-selenium-example.svg?style=shield)](https://circleci.com/gh/jmauerhan/phantomjs-behat-selenium-example)

## Steps

### Install Dependencies
- [PhantomJS](http://phantomjs.org/download.html)
    - PhantomJS has specific builds for your operating system - these do not work cross-platform.
    - Ensure you add the installed location to your PATH.
- Behat, Mink, the Behat Mink Extension, and the Mink Selenium2 Driver (via Composer - see composer.json)
    - We will not *actually* be using Selenium, this is just the driver for Behat to interact with PhantomJS
    - This demo also requires `beberlei/assert` for assertions, but this is **not required** to get Behat & PhantomJS working, I just like using these assertions over writing my own all the time.

### Configure behat.yml
Configure your behat.yml file to use the Behat MinkExtension, and set up the Javascript driver to be Selenium2 with PhantomJS as the browser. I have also configured Goutte as the default browser. Any tests with @javascript will use PhantomJS, the others will use Goutte (a fast, PHP headless browser powered by cURL)

Example:
```
default:
  autoload:
    '': features/Bootstrap
  suites:
    default:
      contexts:
        - Features\Bootstrap\FeatureContext
  extensions:
    Behat\MinkExtension:
      #If you're running a server on a different port, or have a /etc/hosts file set up, change this.
      base_url:  'http://localhost:8000' 
      sessions:
        default:
          goutte: ~
        javascript:
          selenium2:
            browser: phantomjs
```

### Write some features. 
Feel free to browse the Features directory of this repo to see the FeatureContext code, I have included some tests which require JS and some tests which do not, which use Goutte.

### Start PhantomJS
In your terminal:
```
phantomjs --webdriver=4444
```
***Note**: *This is a foreground process, so you'll need to open another terminal window to run Behat**

### Optional: Start up your local dev
You can have your local development environment set up however you want, you might be using Docker containers, Vagrant, WAMP/MAMP, whatever. This demo has some PHP code you can run entirely on the built-in webserver. Open a new terminal and cd into the root of this project, then run:
```
php -S 127.0.0.1:8000
```

### Run Behat
In your terminal:
```
vendor/bin/behat
```

## Integrating with CI

Both CircleCI and Travis include PhantomJS, so there's no need to install or update. 

There's a one line script to start PHP's built-in server in this repository, so I'm starting that in the background in the ci build script. We'll also need to start the PhantomJS webdriver in the background. Then just run the Behat tests

### CircleCI
** circle.yml**

```
machine:
  php:
    version: 7.0.4

test:
  pre:
    - sh start-server.sh:
        background: true
    - phantomjs --webdriver=4444:
        background: true
  override:
    - vendor/bin/behat -f junit -o $CIRCLE_TEST_REPORTS -f pretty -o std
```

### TravisCI
```
language: php
php:
  - '7.0'

before_script:
  - composer install
  - "sh start-server.sh > /dev/null &"
  - "phantomjs --webdriver=4444 > /dev/null &"

script:
  - vendor/bin/behat
```
