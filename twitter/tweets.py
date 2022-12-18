import json
import sys
from tweety.bot import Twitter

user = sys.argv[1]
app = Twitter(user)
tweets = app.get_tweets()
json = json.dumps(tweets, indent=4, sort_keys=True, default=str)
print(json)
