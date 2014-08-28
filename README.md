AntiSpamBundle
==================================

Provides Anti-Spam capabilities to Symfony in an easy to use package.


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

### Step 3: Update database

First, verify the SQL to make sure nothing will break
```
php app/console doctrine:schema:update --dump-sql
```
If everything looks good, execute the schema update.
```
php app/console doctrine:schema:update --force
```

## Configuration

Before you can use the AntiSpam service you must first create an identifier for your action that you do not want to get spammed. Below is an example of what to add to the `app/config/config.yml` file
```
sithous_anti_spam:
    identifiers:
        forgot_password:
            track_ip: true     # track IP of client
            track_user: false  # track client's user
            max_time: 30       # maximum time to track the call for
            max_calls: 1       # maximum number of calls that can be made in max_time seconds
```
* You will need to create an identifier for every action that you want to protect from being spammed.
* Every identifier requires `track_ip`, `track_user`, `max_time`, and `max_calls`.
* Both `track_ip` and `track_user` cannot be false. You must track at least one or both of them.

## Usage

***Soon to come***
