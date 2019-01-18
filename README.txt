=== A3 Robots Warning ===
Contributors: vaniivan
Donate link: https://appandapp.net/isvaljek
Tags: seo, robots.txt
Requires at least: 3.0.1
Tested up to: 5.0.2
Stable tag: trunk
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Warns you of server IP address changes, so you can take care of Search Engine Visibility settings. 

== Description ==

This plugin warns you of server IP address changes, so you can take care of Search Engine Visibility settings. 
This can happen if you move your database between development, staging and production environments, or on the first deployment of your site.
It's bad to keep robots.txt on for production, but could also be bad if you leave the robots.txt off on staging or development, 
since the whole staging.example.com domain could be indexed by Search Engines.

== External Services ==

This plugin makes use of the http://ipecho.net service for external IP address resolution. More info here: https://ipecho.net/developers.html