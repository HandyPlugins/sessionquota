=== SessionQuota – Limit Concurrent Logins & Prevent Account Sharing ===
Contributors:      handyplugins
Tags:              concurrent-login, account-sharing, session-limit, user-sessions, login-security
Requires at least: 5.9
Tested up to:      7.0
Requires PHP:      7.4
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Limit concurrent WordPress logins, prevent account sharing, and choose whether to block new logins or remove older sessions.

== Description ==

**SessionQuota is a concurrent login limiter for WordPress.** Set how many active sessions each user can have, then choose exactly what happens when that limit is reached.

It is a practical way to reduce account sharing on membership sites, online courses, customer portals, private communities, and any WordPress site where one account should not stay active on too many devices.

The free edition gives you a complete global session policy with three enforcement modes. Set it once under `Settings → SessionQuota` and let WordPress handle the rest.

= Free Concurrent Login Controls =

* **Global session limit** – Set the maximum number of active WordPress sessions allowed per user.
* **Block new login** – Reject a new login when the account has already reached its limit.
* **Logout oldest session(s)** – Allow the new login and remove only the oldest sessions needed to stay within the limit.
* **Single-session mode** – Keep the latest login and automatically log out every other session for that account.
* **Unlimited mode** – Set the limit to `0` when you do not want to enforce a global cap.
* **No external service required** – Session enforcement runs on your WordPress site.

= Choose the Right Enforcement Mode =

**Block new login**

Use this when you want the clearest deterrent against shared credentials. Existing sessions stay active and the next login is refused.

**Logout oldest session(s)**

Use this when legitimate users frequently switch devices. The new login succeeds and SessionQuota removes the oldest session or sessions needed to enforce the limit.

**Logout all other sessions**

Use this for a strict one-device-at-a-time policy. The latest login stays active and every other session for that account is terminated.

= Need Granular Rules and Session Visibility? =

[SessionQuota Pro](https://handyplugins.co/sessionquota-pro/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=free_to_pro) adds advanced controls for membership sites, stores, communities, and multisite networks:

* Role-based concurrent session limits.
* Membership-level limits for MemberPress and Paid Memberships Pro.
* Per-user session limit overrides.
* Frontend active-session controls for logged-in users.
* Blocked-login recovery through secure one-time email links.
* Force logout and site-wide session management tools.
* Security event logging, optional device and country context, and activity alerts.
* WP-CLI commands and network-managed multisite support.

The free plugin remains fully functional for sites that only need one global limit. Upgrade when you need different rules for different users, self-service session controls, operational tools, or monitoring.

**[Compare features and get SessionQuota Pro](https://handyplugins.co/sessionquota-pro/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=free_to_pro&utm_content=primary_cta)**

= Privacy =

The free edition uses WordPress session data to enforce your configured limit. It does not send session data to HandyPlugins or require an external account.

= Support and Development =

* Free support: https://wordpress.org/support/plugin/sessionquota/
* Bug reports and contributions: https://github.com/HandyPlugins/sessionquota
* SessionQuota Pro documentation: https://handyplugins.co/docs-category/sessionquota-pro/

= More from HandyPlugins =

* [Magic Login Pro](https://handyplugins.co/magic-login-pro/) – Passwordless WordPress authentication with magic links and flexible integrations.
* [Powered Cache](https://poweredcache.com/) – WordPress caching and optimization for PageSpeed and Core Web Vitals.
* [Stream Integration Pro](https://handyplugins.co/stream-integration-pro/) – Upload, sync, restore, and manage Cloudflare Stream videos in WordPress.
* [Easy Text-to-Speech](https://handyplugins.co/easy-text-to-speech/) – Convert WordPress content into synthesized speech.
* [Handywriter](https://handyplugins.co/handywriter/) – AI-powered writing assistance inside WordPress.
* [PaddlePress PRO](https://handyplugins.co/paddlepress-pro/) – Sell digital products and software licenses with Paddle.
* [WP Accessibility Toolkit](https://handyplugins.co/wp-accessibility-toolkit/) – Add practical accessibility tools to your WordPress site.

== Installation ==

= From the WordPress Dashboard =

1. Go to `Plugins → Add New`.
2. Search for “SessionQuota”.
3. Click `Install Now`, then `Activate`.
4. Go to `Settings → SessionQuota`.
5. Set a concurrent session limit and choose an enforcement mode.
6. Click `Save Settings`.

= Manual Installation =

1. Upload the `sessionquota` folder to `/wp-content/plugins/`.
2. Activate SessionQuota through the `Plugins` screen in WordPress.
3. Go to `Settings → SessionQuota` and configure your session policy.

== Frequently Asked Questions ==

= Does SessionQuota prevent WordPress account sharing? =

SessionQuota helps reduce account sharing by limiting how many active sessions an account can have at the same time. The result depends on the limit and enforcement mode you choose.

= What happens when a user reaches the concurrent login limit? =

You decide. SessionQuota can block the new login, allow it and remove the oldest session or sessions, or keep only the newest session active.

= What happens when the session limit is set to 0? =

`0` means unlimited sessions. The global limit is not enforced unless you choose `Logout all other sessions`, which always keeps only the latest session active.

= Will existing users be logged out when I activate the plugin? =

No. SessionQuota applies your selected policy when login and session enforcement events occur. Activating the plugin by itself does not immediately destroy every existing session.

= Does the free plugin support role-based or membership-level limits? =

The free edition applies one global limit to all users. [SessionQuota Pro](https://handyplugins.co/sessionquota-pro/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=free_to_pro&utm_content=faq) adds role-based rules, supported membership-level rules, and per-user overrides.

= Can users view and end their own active sessions? =

Frontend session lists and self-service session controls are available in SessionQuota Pro. The free edition focuses on automatic enforcement of the global limit.

= Can I run SessionQuota and SessionQuota Pro at the same time? =

No. Only one edition should be active at a time. Deactivate the free edition before activating SessionQuota Pro.

= Does uninstalling SessionQuota remove its settings? =

Yes. Uninstalling the plugin through WordPress removes the `sessionquota_settings` option. It does not delete WordPress user accounts.

== Screenshots ==

1. Set a global concurrent session limit and choose how SessionQuota handles extra logins.

== Changelog ==

= Unreleased =
* Added locked previews for advanced limits, admin tools, and security monitoring available in SessionQuota Pro.
* Added a blocked-login recovery preview when using block mode.
* Added direct access to SessionQuota Pro from the settings and Plugins screens.
* Improved keyboard navigation and accessibility for settings tabs.
* Reworked the plugin listing to better explain free and Pro functionality.
* Confirmed compatibility with WordPress 7.0.

= 1.0.0 (Mar 10, 2026) =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of SessionQuota.
