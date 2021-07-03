Database CRUD D2S
=================
Работа с базой данных, расширенная работа с базой

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dev2studio/yii2-database-crud "*"
```

or add

```
"dev2studio/yii2-database-crud": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \dev2studio\database\D2SCrud::widget(); ?>