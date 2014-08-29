AntiSpamBundle
==================================

Provides Anti-Spam capabilities to Symfony in an easy to use package.


We use composer! https://packagist.org/packages/sithous/antispam-bundle

## Requirements
- => Symfony 2.3
- Doctrine


## Installation

### Step 1: Add to composer

add the bundle to your root composer.json file under the `require` section
```
"sithous/antispam-bundle": "1.0.*@dev"
```
 After adding that run composer to fetch the package into your vendors folder
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

By default the AntiSpamBundle runs garbage collection every time a verify() call is made. You can change this to run in a cron every couple minutes by setting `active_gc` to true.
```
# app/config/config.yml
sithous_anti_spam:
    active_gc: true   # set to false to use cron garbage collection
```
If you want to use cron garbage collection you will need to make a cron job run the following command:
```
php app/console sithous:antispambundle:gc
```

## Example Usage

A good example is if you want users to be able to vote on something but only want the user to vote once a day. To do this you would first generate the SithousAntiSpamType using the following command:
```
$ php app/console sithous:antispam:generate
Please enter the ID for this type: vote_protection
Track IP [Y/N]? Y
Track User [Y/N]? Y
Max Time to track action (seconds): 86400
Max Calls that can happin in MaxTime: 1

ID: vote_protection
trackIp: true
trackUser: true
maxTime: 86400 seconds
maxCalls: 1

Is the above information correct [Y/N]? Y
Successfully added SithousAntiSpamType "vote_protection"
```
Now in your controller function you will run the verify command before submitting the vote
```
    public function submitVoteAction(Request $request)
    {
        // somewhere in your code...
        
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
If you later want to remove a SithousAntiSpamType run the following command:
```
$ php app/console sithous:antispam:delete
Enter the SithousAntiSpamType ID to delete: vote_protection
Successfully removed SithousAntiSpamType "vote_protection"
```

