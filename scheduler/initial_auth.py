import json
from requests_oauthlib import OAuth1Session

# Twitter API credentials
API_KEY = 'MD3do0QjQHYpTlqHvY9Ehsd2d'
API_SECRET  = 'RPw7jMWPrrIa0Tgn54B3DxpiluGvuGgbBaDC2G0SknF0lPYoI5'

# Step 1: Get the request token
request_token_url = "https://api.twitter.com/oauth/request_token"
oauth = OAuth1Session(API_KEY, client_secret=API_SECRET)
fetch_response = oauth.fetch_request_token(request_token_url)

resource_owner_key = fetch_response.get("oauth_token")
resource_owner_secret = fetch_response.get("oauth_token_secret")

# Step 2: Direct user to authorize URL
base_authorization_url = "https://api.twitter.com/oauth/authorize"
authorization_url = oauth.authorization_url(base_authorization_url)
print(f"Please go to the following URL and authorize: {authorization_url}")
verifier = input("Enter the PIN provided by Twitter: ")

# Step 3: Exchange the PIN for access tokens
access_token_url = "https://api.twitter.com/oauth/access_token"
oauth = OAuth1Session(
    API_KEY,
    client_secret=API_SECRET,
    resource_owner_key=resource_owner_key,
    resource_owner_secret=resource_owner_secret,
    verifier=verifier,
)

access_tokens = oauth.fetch_access_token(access_token_url)

# Step 4: Save the access tokens to a file
with open("access_tokens.json", "w") as token_file:
    json.dump(access_tokens, token_file)

print("Access tokens saved successfully!")
