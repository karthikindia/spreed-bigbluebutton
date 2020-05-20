# Nextcloud using Big Blue Button 

![](https://pix.cobrasoftwares.org/images/2020/05/20/talkwithbbb.jpg)

This is a fork of Nextcloud Talk to use [Big Blue Button](https://bigbluebutton.org/) as the video / audio conferencing server & client instead of Nextcloud's PHP-based signalling engine (or the Nextcloud paid cloud signaling servers)

## Why Big Blue Button

This is a tried-tested-and-true solution used by many educational institutions worldwide. It has been under active development for a very long time and is well-maintained. Uses Red5 and Kurento under the hood, and has a very nice and solid client (using our beloved [MeteorJS](https://www.meteor.com))

- A recommended BBB server can support over 100 simultaneous users (webmeet.cobrasoftwares.org for internal use only) - public site  - www.createwebinar.org
- BBB client can handle a lot more participants in the UI
- BBB client has drawing board, breakout rooms, uploading PDF,DOC,PPT,XLS
- BBB client has real-time notes
- BBB client UI is professional-grade (no offense intended for current Talk client UI)
- BBB uses WebRTC -- don't want to get used like Zoom because they used their own protocol

## A note about mobile

Big Blue Button uses webrtc protocol which is supported by all recent browsers (even on iOS). Therefore, they do not have a mobile application for it.

> If you are using the Talk mobile app it won't work with this app. If a mobile app is a must you are stuck with the original Talk app.

## How to setup BBB

Super easy, they have an [automated install script](http://docs.bigbluebutton.org/2.2/install.html). You do need to have an SSL certificate setup (the install script does it for you, but you need to setup your domains properly). **Don't forget that you need to use Ubuntu 16**
For assistance please contact sales@cobrasoftwares.org

# What we did

1. We load up an iframe with your BBB server where the old Talk client was, **only when in the Talk app**. The files details chat still uses the original signaling (which is easily capable of handling such a low-attendees call).

> Right now it is still internally called **spreed** which is the original name of the Talk App. The reasons are many, but prevent us from pushing into the NC Apps store. If you can help with this, please open an issue.

2. When a call is launched in the Talk app (not files sidebar) we hide both sidebars to have the call full width (you can reopen the call sidebar on the right by clicking on its icon)

3. We kept the chat as-is using the internal signaling server which is more than capable of handling chat traffic

4. We removed the Talk changelog that appears for new users (I understand the rationale, but annoying for system administrators)

> The original code is very well-written. Kudos to the original developers. Made it easier to make changes. We had to add a few helper functions here and there as some needs were not served well by existing ones (e.g. get the name of a guest to send to BBB)

# How to use this App

## First you have to manually clone it in your /apps folder

Of course, remove the original spreed
Clone this git repo.

Notes: 
- Run that clone command in your /apps folder
- We included the build dir (/js) in this repo. In the future we will add releases (help wanted) or push in the Apps store (help wanted)
- Supports NC 18 only -- sorry, too much work porting back
- If you intend to develop omit the --depth argument

## Add your BBB server info in config/config.php

```
  'spreed' =>
  array (
    'bbb_server' => 'https://YOURSERVER/bigbluebutton/',
    'bbb_secret' => 'YOURSECRET',
  ),
```
or, alternatively create a config/custom.config.php: 
```
<?php
$CONFIG = <?php
$CONFIG = array ('spreed' =>
  array (
    'bbb_server' => 'https://YOURSERVER/bigbluebutton/',
    'bbb_secret' => 'YOURSECRET',
  ),
);
```

Notes:
- The trailing slash is important in the URL above
- You can get both info from your BBB server: `sudo bbb-conf --secret`
- We hope to add soon to the admin settings UI of this App the ability to edit these manually (help wanted)

## Test it

Reload your browser, clear your cache, etc. and you can now start calls with BBB

# Troubleshooting

1. Is the iframe loading when you start a call in the Talk app --> If not you are still using the old App
2. Make sure BBB server is working. Open your browser to `https://DOMAIN/bigbluebutton/` and `https://DOMAIN/bigbluebutton/api/`. You should get a response for both calls like this
```
<response>
   <script/>
   <returncode>SUCCESS</returncode>
   <version>2.0</version>
</response>
```
# Nextcloud Talk

## Why is this so awesome?

* 💬 **Chat integration!** Nextcloud Talk comes with a simple text chat. Allowing you to share files from your Nextcloud and mentioning other participants.
* 👥 **Private, group, public and password protected calls!** Just invite somebody, a whole group or send a public link to invite to a call.
* 💻 **Screen sharing!** Share your screen with participants of your call. You just need to use Firefox version 52 (or newer), latest Edge or Chrome 49 (or newer) with this [Chrome extension](https://chrome.google.com/webstore/detail/screensharing-for-nextclo/kepnpjhambipllfmgmbapncekcmabkol).
* 🚀 **Integration with other Nextcloud apps** like Files, Contacts and Deck. More to come.
* 🙈 **We’re not reinventing the wheel!** Based on the great [simpleWebRTC](https://simplewebrtc.com/) library.

And in the works for the [coming versions](https://github.com/nextcloud/spreed/milestones/):
* 🙋 [Federated calls](https://github.com/nextcloud/spreed/issues/21), to call people on other Nextclouds

If you have suggestions or problems, please [open an issue](https://github.com/nextcloud/spreed/issues) or contribute directly :)

### Supported Browsers

| Browser | Compatible |
|---|---|
| Firefox | ✔️ 52 or later |
| Chrome/Chromium | ✔️ 49 or later |
| Edge | ⚠️ latest versions <br> 🎤 Speakers are not promoted <br> 🏷 Name changes while a call is on-going are not reflected |
| Safari | ⚠️ 12 or later <br> ❌ No screensharing support <br> 🖥 Viewing screens of others works |


## Installing for Production

Nextcloud Talk is really easy to install. You just need to enable the app from the [Nextcloud App Store](https://apps.nextcloud.com/apps/spreed) and everything will work out of the box.

There are some scenarios (users behind strict firewalls / symmetric NATs) where a TURN server is needed. That's a bit more tricky installation, but the guys from [Nextcloud VM](https://github.com/nextcloud/vm) have developed a script which takes care of everything for you. You can find the script [here](https://github.com/nextcloud/vm/blob/master/apps/talk.sh). The script is tested on Ubuntu Server 18.04, but should work on 16.04 as well. Please keep in mind that it's developed for the VM specifically and any issues should be reported in that repo, not here.

Here's a short [video](https://youtu.be/KdTsWIy4eN0) on how it's done.

If you need to use Talk in a enterprise environment, including the ability to have calls with more than 5-6 users, you can contact our sales team for access to our [high performance back-end](https://nextcloud.com/talk/#pricing). This is a set of components that replaces some of the PHP code with a more scalable and performant solution that decreases network traffic and allows dozens or hundreds of users in a call.

## Development setup

1. Simply clone this repository into the `apps` folder of your Nextcloud development instance.
2. Run `make dev-setup` to install the dependencies;
3. Run `make build-js`
4. Then activate it through the apps management. :tada:
5. To build the docs locally, install mkdocs locally: `apt install mkdocs mkdocs-bootstrap`


### API documentation

The API documentation is available at https://nextcloud-talk.readthedocs.io/en/latest/
