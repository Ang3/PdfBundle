PdfBundle
==============================

[![Build Status](https://travis-ci.org/Ang3/PdfBundle.svg?branch=master)](https://travis-ci.org/Ang3/PdfBundle) [![Latest Stable Version](https://poser.pugx.org/ang3/pdf-bundle/v/stable)](https://packagist.org/packages/ang3/pdf-bundle) [![Latest Unstable Version](https://poser.pugx.org/ang3/pdf-bundle/v/unstable)](https://packagist.org/packages/ang3/pdf-bundle) [![Total Downloads](https://poser.pugx.org/ang3/pdf-bundle/downloads)](https://packagist.org/packages/ang3/pdf-bundle)

This [Symfony](https://symfony.com) bundle helps you to create PDF files easily with Google Chrome.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ang3/pdf-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
  public function registerBundles()
  {
    $bundles = array(
      // ...
      new Ang3\Bundle\PdfBundle\Ang3PdfBundle(),
    );

    // ...
  }

  // ...
}
```

Usage
=====

## From a controller

### From HTML content

```php
<?php
// src/YouBundle/Controller/YourController.php

// ...
$src = '/*....*/';
$target = '/*....*/';
$this->get('pdf_factory')->createFromUrl($src, $target); # Returns binaries (target is optional)
// ...

```

### From URL

```php
<?php
// src/YouBundle/Controller/YourController.php

// ...
$htmlContent = '/*....*/';
$target = '/*....*/';
$this->get('pdf_factory')->createFromHtml($htmlContent, $target); # Returns binaries (target is optional)
// ...

```

## From anywhere

Inject the service and calls methods as above.