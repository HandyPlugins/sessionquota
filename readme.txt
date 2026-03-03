=== SessionQuota ===
Contributors:      handyplugins
Tags:              account-sharing, concurrent-sessions, concurrent-login, user-sessions, session
Requires at least: 5.9
Tested up to:      6.9
Requires PHP:      7.4
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Prevent account sharing by limiting concurrent user sessions in WordPress.

== Description ==

SessionQuota helps you prevent account sharing by controlling how many active sessions each user can have at the same time.

Set a global session limit and choose what happens when the limit is reached: block the new login, logout the oldest session(s), or keep only the latest session (single-session mode).

= Key Features =

- **Global session limit**: Set a maximum number of concurrent sessions per user (`0` = unlimited).
- **Enforcement modes**:
  - **Block new login** when the limit is reached.
  - **Logout oldest session(s)** required to stay within the limit.
  - **Logout all other sessions** after a successful login (single-session mode).
- **Simple setup**: Manage settings from `Settings -> SessionQuota`.

= Enforcement Modes Explained =

1. **Block new login**
   - Rejects the new login attempt when the user is already at the limit.

2. **Logout oldest session(s)**
   - Terminates the oldest existing session(s) required to keep the user within the limit.

3. **Logout all other sessions**
   - Keeps only the latest session and terminates all other sessions.

= Contributing & Bug Reports =

Bug reports and pull requests are welcome on GitHub: https://github.com/HandyPlugins/sessionquota

== Installation ==

= From the WordPress Dashboard =

1. Go to `Plugins -> Add New`.
2. Search for "SessionQuota".
3. Click Install, then Activate.
4. Go to `Settings -> SessionQuota` to configure.

= Manual Installation =

1. Upload the `sessionquota` folder to `/wp-content/plugins/`.
2. Activate SessionQuota through the `Plugins` menu in WordPress.
3. Go to `Settings -> SessionQuota`.

== Frequently Asked Questions ==

= Where do I configure SessionQuota? =
Go to `Settings -> SessionQuota` in wp-admin.

= What happens when the session limit is set to 0? =
`0` means unlimited sessions. In this case, session limiting is effectively disabled unless you choose `Logout all other sessions` (single-session mode).


== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of SessionQuota.