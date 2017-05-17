# Discord Oauth Script - [Login With Discord, PHP]

 # How To use it?
Just download the files and save it. The Oauth script is in the ```index.php``` file. Edit the file to add your Redirect URI, Client ID and CLient Secret. Then replace this URL with your data and redirect the user to that URL to authorize the application : https://discordapp.com/oauth2/authorize?client_id=YOUR_CLIENT_ID&scope=SCOPES&redirect_uri=THE_CALLBACK_URL&response_type=code

 # How do I integrate it into my website
You need to know PHP to integrate it. The script retreieves data for the ```identify``` and ```guilds``` scope. It uses GuzzleHttp to conduct the POST and GET requests. You don't have to learn GuzzleHttp, just copy paste one of the aldready written ones and edit the URL, in the format I have done. More info on how to sue scopes can be found [here](https://discordapp.com/developers/docs/topics/oauth2#scopes)

# How does it work?
After the user authorizes the application, a code is sent by discord to the Rediect URI. This script which is in your Reirect URI folder will grab the code sent by discord through a GET request and will POST it to the Oauth API along with your data to get an authorizaion token. This authorizatin token is again sent to the send to the Discord API (which depends on your required scope) to get the user data, which is then utilized by you, that is you make SESSIONS with the data you receieved.

# I have more doubts as on how to use it!
Join my server and DM me - Markis™#0227 your quieries and I will try to sort them ;)
Server Link : https://discord.io/ds
