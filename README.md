AntiSpamBundle
==================================

Provides Anti-Spam capabilities to Symfony in an easy to use package.

<<<<<<< HEAD
Installation
------------

```mappings:
    SithousAntiSpamBundle:
        type: yml
        is_bundle: true
```
=======
We use composer for version control: https://packagist.org/packages/sithous/antispam-bundle

## Requirements
- Symfony 2.3
- Doctrine
- Composer


## Installation

### Step 1: Add to composer

add the bundle to your root composer.json file under the `require` section
```
"sithous/antispam-bundle": "1.0.*@dev"
```
 After adding that to the composre file run composer to fetch the package into your vendors folder
```
php composer.phar update
```

### Step 2: Enable the bundle

Enable the bundle by adding the following to the `app/config/appKernel.php`
```
new Sithous\AntiSpamBundle\SithousAntiSpamBundle(),
```

### Step 3: Modify Symfony config 

The following needs to be added to `config.yml` under `orm:`

```
# app/config/config.yml
        mappings:
            SithousAntiSpamBundle:
                type: yml
                is_bundle: true
```

### Step 4: Update database

First, verify the SQL to make sure nothing will break
```
php app/console doctrine:schema:update --dump-sql
```
If everything looks good, execute the games.
```
php app/console doctrine:schema:update --force
```

# More to come...
>>>>>>> 1f9864ae127d1673f1f7c344cb86d4933d300300
