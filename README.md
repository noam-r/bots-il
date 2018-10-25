# BOTS-IL console

This console is built to allow basic research on the twitter API. 

Several add-ons allows connecting to the project database and comparing data from the known bot-networks to assist in the research.

The application uses [Application-only authentication](Application-only authentication) tokens, so no user details are required, just basic app credentials.

## Installation

First, clone the repository
```bash
git clone https://github.com/noam-r/bots-il.git
``` 
Run composer ([Installation instructions in case you don't have it installed](https://getcomposer.org/doc/00-intro.md))
```bash
composer update
```
Prepare your environment file
```bash
mv .env.example .env
``` 
Edit the file with your favorite editor to enter a **CONSUMER_KEY** and a **CONSUMER_SECRET** (if you don't have any, you can [create some](https://developer.twitter.com/en/docs/basics/authentication/guides/access-tokens.html) or just ask a friend).

Once you have the envronment set up, you should run:
```bash
./botsil.php token
```

You will get a token, which you will need to copy and place into your **.env** file as:
```bash
TWITTER_TOKEN = [whatever you received in the previous step]
```

That's it - you're ready to roll.

## Usage

Basic usage looks like:

```bash
./botsil.php profile [username]
```

If you have credentials for the Bots-IL project API, you will need to get 
