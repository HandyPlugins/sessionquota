=== Session Limiter ===
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

Session Limiter helps you prevent account sharing by controlling how many active sessions each user can have at the same time.

Set a global session limit and choose what happens when the limit is reached: block the new login, logout the oldest session(s), or keep only the latest session (single-session mode).

**[Learn more about Session Limiter Pro](https://handyplugins.co/session-limiter-pro/)**

= Key Features =

- **Global session limit**: Set a maximum number of concurrent sessions per user (`0` = unlimited).
- **Enforcement modes**:
  - **Block new login** when the limit is reached.
  - **Logout oldest session(s)** required to stay within the limit.
  - **Logout all other sessions** after a successful login (single-session mode).
- **Simple setup**: Manage settings from `Settings -> Session Limiter`.

= Enforcement Modes Explained =

1. **Block new login**
   - Rejects the new login attempt when the user is already at the limit.

2. **Logout oldest session(s)**
   - Terminates the oldest existing session(s) required to keep the user within the limit.

3. **Logout all other sessions**
   - Keeps only the latest session and terminates all other sessions.

= Pro Features =

Session Limiter Pro adds advanced controls and management features such as:

- Role-based session limits
- Per-user session limit overrides
- Membership plugin integration (MemberPress, Paid Memberships Pro)
- Force logout users and bulk session management
- Frontend session management for users
- WordPress Multisite support
- Advanced monitoring and logging of session activity
- WP-CLI commands for automation
- Export/Import settings
- Priority support

**[Explore Session Limiter Pro](https://handyplugins.co/session-limiter-pro/)**

= Documentation =

Documentation and guides: https://handyplugins.co/docs-category/session-limiter-pro/

= Contributing & Bug Reports =

Bug reports and pull requests are welcome on GitHub: https://github.com/HandyPlugins/session-limiter

== Installation ==

= From the WordPress Dashboard =

1. Go to `Plugins -> Add New`.
2. Search for "Session Limiter".
3. Click Install, then Activate.
4. Go to `Settings -> Session Limiter` to configure.

= Manual Installation =

1. Upload the `session-limiter` folder to `/wp-content/plugins/`.
2. Activate Session Limiter through the `Plugins` menu in WordPress.
3. Go to `Settings -> Session Limiter`.

== Frequently Asked Questions ==

= Where do I configure Session Limiter? =
Go to `Settings -> Session Limiter` in wp-admin.

= What happens when the session limit is set to 0? =
`0` means unlimited sessions. In this case, session limiting is effectively disabled unless you choose `Logout all other sessions` (single-session mode).

= Can Free and Pro be active at the same time? =
No. Only one Session Limiter edition should be active.


== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of Session Limiter.