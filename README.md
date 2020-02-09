# Gillix PHP framework
Gillix is one of few frameworks for PHP developers that **does not** use MVC pattern, instead offering a flexible and intuitive abstraction level based on OOP principles.

## Installation
Use [composer](http://getcomposer.org/) to install Gillix framework
```bash
composer require gillix/framework
```

## About usage

One simple example for a common understanding of how Gillix works
```php
<?php
require_once __DIR__.'/vendor/autoload.php';
use function \glx\core\node;

$root = node([ 
    'about' => [
        'type'  => 'SECTION',
        'Title' => 'About',
        'Text'  => 'About World',
        'order' => 1
    ],
    'contacts' => [
        'type'  => 'SECTION',
        'Title' => 'Contact',
        'Text'  => 'Contact World',
        'order' => 3
    ],
    'products' => [
        'type'  => 'SECTION',
        'Title' => 'Products',
        'Text'  => 'Sell World',
        'order' => 2
    ],
    'Title' => 'Home',
    'Text'  => 'Hello World',
    'Header' => function() { ?>
        <h2 class="header"><?= $this->Title ?></h2>
    <? },
    'Menu' => function() { ?>
        <h2 class="menu">
         <? $this->root()->select('SECTION')->sort('order')->each(function(){ ?>
           <span class="item">
             <a href="<?= $this->path() ?>"><?= $this->Title ?></a>
           </span>
         <? }) ?>
        </h2>
    <? },
    'Content' => function() { ?>
        <div class="content"><?= $this->Text ?></div>
    <? },
    'main' => function(){ ?>
        <html lang="en">
        <body>
        <?= $this->Header() ?>
        <?= $this->Menu() ?>
        <?= $this->Content() ?>
        </body>
        </html>
    <? }
]);

echo $root->about->main();
```
The result of this example would be:
```html
<html lang="en">
<body>
  <h2 class="header">About</h2>
  <div class="menu">
    <span class="item">
      <a href="/about/">About</a>
    </span>
    <span class="item">
      <a href="/products/">Products</a>
    </span>
    <span class="item">
      <a href="/contacts/">Contact</a>
    </span>
  </div>
  <div class="content">About World</div>
</body>
</html>
```
And if we add some styles and open in browser than we will get something like this
##
#### About
  [About](/about/)  **[Products](/products/)**  **[Contact](/contacts/)**  
    </span>

About World
##
A very simple but working site in 5 minutes! You can do it yourself, this example really works. I don’t think that anyone will need it in this form, unless you need to make something simple really fast.

Gillix provides many more features. Read about all the features of the framework in the full documentation, as well as how to use it in everyday development.
