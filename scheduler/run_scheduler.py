import json
import mysql.connector
from datetime import datetime
import time
from requests_oauthlib import OAuth1Session
from dotenv import load_dotenv
import os

load_dotenv()

# Database configuration
db_config = {
    'user': os.getenv('DBUSER'),
    'password': os.getenv('DBPASS'),
    'host': os.getenv('DBHOST'),
    'database': os.getenv('DB'),
    'charset': 'utf8mb4'
}

# Twitter API credentials
API_KEY = os.getenv('API_KEY')
API_SECRET  = os.getenv('API_SECRET')

# Authenticate with Twitter API
with open("access_tokens.json", "r") as token_file:
    access_tokens = json.load(token_file)
    
oauth_token = access_tokens["oauth_token"]
oauth_token_secret = access_tokens["oauth_token_secret"]
tweet_url = "https://api.twitter.com/2/tweets"
oauth = OAuth1Session(
    API_KEY,
    client_secret=API_SECRET,
    resource_owner_key=oauth_token,
    resource_owner_secret=oauth_token_secret,
)

def fetch_pending_posts():
    """Fetch posts scheduled for the current time or earlier."""
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor(dictionary=True)
    query = "SELECT * FROM posts WHERE post_time <= NOW() AND status = 'pending'"
    cursor.execute(query)
    posts = cursor.fetchall()
    cursor.close()
    connection.close()
    return posts

def mark_posted(post_id):
    """Update post status to 'posted'."""
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()
    query = "UPDATE posts SET status = 'posted' WHERE id = %s"
    cursor.execute(query, (post_id,))
    connection.commit()
    cursor.close()
    connection.close()

def post_content(post):
    """Post content to Twitter."""
    try:
        response = oauth.post(tweet_url, json={"text": post['content']})
        if response.status_code == 201:
            print(f"Successfully posted: {post['content']} at {datetime.now()}")
            mark_posted(post['id'])
        else:
            error_json = response.json()
            print(f"Failed to post: {post['content']}. Status {response.status_code}, Error: {error_json}")
    except Exception as e:
        print(f"Failed to post: {post['content']} due to {e}")

def check_and_post():
    """Check for pending posts and post them."""
    posts = fetch_pending_posts()
    for post in posts:
        post_content(post)

print("Scheduler is running...")

# Keep the script running
try:
    while True:
        check_and_post()
        time.sleep(60)
except (KeyboardInterrupt, SystemExit):
    exit()
