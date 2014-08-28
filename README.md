AntiSpamBundle
==================================

Provides Anti-Spam capabilities to Symfony in an easy to use package.


We use composer! https://packagist.org/packages/sithous/antispam-bundle

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
    active_gc: true   # set to false to use cron garbage collection
    identifiers:
        forgot_password:
            track_ip: true     # track IP of client
            track_user: false  # track client's user
            max_time: 30       # maximum time to track the call for
            max_calls: 1       # maximum number of calls that can be made in max_time seconds
```
* `active_gc` default: true. If true garbage collection will run every time a verification is ran. Otherwise set to false and make a cron run the `sithous:antispam:gc` command on a set interval.
* You will need to create an identifier for every action that you want to protect from being spammed.
* Every identifier requires `track_ip`, `track_user`, `max_time`, and `max_calls`.
* Both `track_ip` and `track_user` cannot be false. You must track at least one or both of them.

## Example Usage

A good example is if you want users to be able to vote on something but only want the user to vote once a day. To do this you would add this to your `app/config/config.yml`

```
sithous_anti_spam:
    active_gc: true   # set to false to use cron garbage collection
    identifiers:
        vote_protection:
            track_ip: false    # block ip and user from submitting another vote
            track_user: true
            max_time: 86400    # 86400 is one day
            max_calls: 1       # allow only one call
```
Now in your controller function you will run the verify command before submitting the vote
```
    public function submitVoteAction(Request $request)
    {
        // make sure user/ip hasn't subbmited today
        $spamCheck = $this->get('sithous.antispam');
        if(!$spamCheck->setIdentifier('vote_protection')->verify())
        {
            return new JsonResponse(array(
                'result'  => 'error',
                'message' => $spamCheck->getErrorMessage()
            ));
        }
    }
```
