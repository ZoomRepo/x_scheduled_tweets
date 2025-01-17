 # X Scheduled Tweets


Project by: [Ollie Beumkes](https://x.com/olliejpb)

The aim of this project is to allow people to forcast tweets helping keep up their media presence and help with motivation, planning ahead of time and taking away the burden from thinking what to write and having to be emotionally envolved with that aspect of growing themselves whilst working on projects.

Think of it as a technical PR service that you can offload your thoughts, ideas and goals; set deadlines and start conversations without having to pysically keep track of whether or not you feel you are ready to post.

Set the content, time and click schedule and it will do the rest for you...

# Setup

### Prerequisits
- Visual Studio Code
- Python3 
- XAMPP

This section will go over the setup, we currently have only covered Windows machines although I will have this running on my Linux machine so contact me if you want me to go over that side of things as well.

## Windows setup

1. First things first is head over to [XAMPP](https://www.apachefriends.org/) and download the latest version.
2. Head over to [X Dev Portal](https://developer.x.com/en/portal/dashboard) on the left go into Project and App and you should find you keys and tokens.
You are going to want to generate some consumer keys and keep note of them.
3. Clone this repo into C:/xampp/htdocs/
4. Create a .env file in the scheduler folder and populate it with the following:
```
API_KEY = (YOUR CONSUMER API KEY)
API_SECRET  = (YOUR CONSUMER API SECRET)
DB=mydb
DBHOST=localhost
DBUSER=root
DBPASS=
```
## Preparing the scheduler
First things first I would recommend creating a virtual python environment in the scheduler folder.

Start a terminal in that folder (by left shift + right click and click open Powershell here)

Type ```python3 -m venv venv```

Now start the Virtual Environemnt by typing 
```./venv/Scripts/activate```

Install the dependencies by typing
```pip3 install -r requirements.txt```

Select the Python Interpreter for your venv environment press ```Left Shift + Ctrl + P```, search for Select Pyhton Interpreter. If it doesn't show /venve/scripts/python.exe find it and select it.

## Authenticating you app (one off process)
To authenticate your app open ```initial_auth.py``` and click the run button (▶) top right, alternatively run ```python ./initial_auth.py``` and follow the instructions in the terminal. A window will open with a PIN, enter it and thats your service authentication (its a one off deal, you will not have to do it again).

## Setup the Database
Go to http://localhost/phpmyadmin the username should be ```root``` and the password is blank.

On the left click New and at the top you will see SQL.

Type:
```
CREATE DATABASE mydb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

On the left click on the new database ```mydb```

Click on SQL at the top again and enter:
```
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    post_time DATETIME NOT NULL,
    status ENUM('pending', 'posted') DEFAULT 'pending'
);
```

# Running your scheduled Tweets

To get started running your schedules tweets, open the main.py file and click run button (▶) at the top right, alternatively run ```python ./run_scheduler.py```.

from within the scheduler foler, that should start looking for new posts.

To access the website to manage posts access the url, ```http://localhost/x_scheduler/php/```

## More Info

I will continue to upgrade the sevice and intend to keep it open source.

### Recommended Google Chrome Extensiosn
- Emjoi Extension - https://chromewebstore.google.com/detail/emoji-keyboard-emojis-for/fbcgkphadgmbalmlklhbdagcicajenei
- Text Formatting Extension - https://chromewebstore.google.com/detail/right-click-text-format/nbicfaklckciejicmnpgjhbhehacddgh

The functionality of the extensions above will potentially be introduced to the service itself but fior now as a work around you can use the extensions to allow formatting and adding emjois to your scheduled posts.

Please feel free to reach out to me on [X](https://x.com/olliejpb) or by email at ollie@motekso.co.uk
for any support, ideas or feedback.

I hope you find this useful! 

### All the best for 2025! 🎉