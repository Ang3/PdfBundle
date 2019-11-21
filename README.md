PdfBundle
==============================

[![Build Status](https://travis-ci.org/Ang3/PdfBundle.svg?branch=master)](https://travis-ci.org/Ang3/PdfBundle) [![Latest Stable Version](https://poser.pugx.org/ang3/pdf-bundle/v/stable)](https://packagist.org/packages/ang3/pdf-bundle) [![Latest Unstable Version](https://poser.pugx.org/ang3/pdf-bundle/v/unstable)](https://packagist.org/packages/ang3/pdf-bundle) [![Total Downloads](https://poser.pugx.org/ang3/pdf-bundle/downloads)](https://packagist.org/packages/ang3/pdf-bundle)

This [Symfony](https://symfony.com) bundle helps you to create PDF files easily with Google Chrome.

Installation
============

Step 0: Download and install Google Chrome
------------------------------------------

Install Google Chrome on your server.

## On Ubuntu

Source: https://doc.ubuntu-fr.org/google_chrome

```console
$ sudo apt-get install google-chrome-stable
```

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
in the `config/bundles.php` file of your project if it's not already done:

```php
<?php
// config/bunles.php

return [
  // ...
  Ang3\Bundle\PdfBundle\Ang3PdfBundle::class => ['dev' => true, 'test' => true],
];
```

Step 3: Configure your app (recommended)
----------------------------------------

I highly recommand to configure the exact path of google chrome and pdfunite:

```yaml
# app/config/config.yml
ang3_pdf:
  chrome_path: ~ # default value : /usr/bin/google-chrome-stable
  pdfunite_path: ~ # default value : /usr/bin/pdfunite
```

Usage
=====

Inject the factory class.

```php
<?php

use Ang3\Component\Pdf\PdfFactory; // DO NOT FORGET

class PdfManager
{
  /**
   * @var PdfFactory
   */
  private $pdfFactory;

  public function __construct(PdfFactory $pdfFactory)
  {
    $this->pdfFactory = $pdfFactory;
  }

  // ...
}
```

- Factory class: ```Ang3\Component\Pdf\PdfFactory```
- Service public alias: ```ang3_pdf.factory```