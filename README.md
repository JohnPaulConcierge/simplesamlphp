# SAML Authentication
  
This project is a Zend Framework module. Its purpose is to manage authentication using SAML.
  
## Installation
  
Requirements : PHP5.6, PHP7  

In order to use this library in one of your projects, you just need to add the following lines in your composer.json:
```
...
"repositories": [
   {
     "type": "vcs",
     "url": "git@github.com:JohnPaulConcierge/zf-jpc-saml.git"
   }
 ], 
"require": {
  "johnpaulconcierge/zf-jpc-saml": "^2.1"
}
...
```

## Usage  
  
Locate config.php.dist and add these configurations to your zend application.
As this project is a fork of simplesamlphp, you can refer to their [documentation](https://simplesamlphp.org/docs/stable/)

## Disclaimer

Please don't use versions before 2.1 because they are very unsecure.