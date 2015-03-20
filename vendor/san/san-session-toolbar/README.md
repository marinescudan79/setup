SanSessionToolbar
=================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://secure.travis-ci.org/samsonasik/SanSessionToolbar.svg?branch=master)](http://travis-ci.org/samsonasik/SanSessionToolbar)
[![Coverage Status](https://coveralls.io/repos/samsonasik/SanSessionToolbar/badge.svg?branch=master)](https://coveralls.io/r/samsonasik/SanSessionToolbar)
[![Quality Score](https://img.shields.io/scrutinizer/g/samsonasik/SanSessionToolbar.svg?style=flat-square)](https://scrutinizer-ci.com/g/samsonasik/SanSessionToolbar/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/15956744-b35e-4504-ade8-14f46addcae9/mini.png)](https://insight.sensiolabs.com/projects/15956744-b35e-4504-ade8-14f46addcae9)
[![Total Downloads](https://img.shields.io/packagist/dt/san/san-session-toolbar.svg?style=flat-square)](https://packagist.org/packages/san/san-session-toolbar)

What is it about ?
-----------------
It is a ZF2 module as session toolbar for [ZendDeveloperTools](https://github.com/zendframework/ZendDeveloperTools). It shows you the ZF2 session data you've been using like this :

![Show ZF2 sessions data](https://cloud.githubusercontent.com/assets/459648/6427963/4f6afbf4-bfc3-11e4-9ae7-4f600b4ee94f.png)

You can reload, clear, remove, and update your sessions data.

Installation
------------

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
$ composer require san/san-session-toolbar 0.*
```

Enable this : 
```php
// config/application.config.php
return [
    'modules' => [
        // ...
        'ZendDeveloperTools',
        'SanSessionToolbar',
    ]
    // ...
],
```

Contributing
------------
Contributions are very welcome. Please read [CONTRIBUTING.md](https://github.com/samsonasik/SanSessionToolbar/blob/master/CONTRIBUTING.md)

Credit
------

- [Abdul Malik Ikhsan](https://github.com/samsonasik)
- [All SanSessionToolbar contributors](https://github.com/samsonasik/SanSessionToolbar/contributors)
- Toolbar Session icon from : http://png-5.findicons.com/files/icons/728/database/512/database_2_512.png, encoded with base64_encode.
- Remove Session icon from http://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/16/Actions-edit-delete-icon.png, encoded with base64_encode.
- Reload session icon from http://findicons.com/icon/261541/arrow_refresh, encoded with base64_encode.
- Edit Session icon from http://findicons.com/files/icons/140/toolbar_icons_3_by_ruby_softwar/128/edit.png, encoded with base64_encode.
- Clear session icon from http://findicons.com/icon/66997/gnome_edit_clear, encoded with base64_encode.
- Save Session icon from http://findicons.com/files/icons/980/yuuminco/48/3_disc.png, encoded with base64_encode.
- Cancel save session from http://findicons.com/files/icons/734/phuzion/128/stop.png, encoded with base64_encode.
