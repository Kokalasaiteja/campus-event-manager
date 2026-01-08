# Campus Event Manager

A WordPress plugin to manage campus events using Custom Post Types, shortcodes, admin menus, nonces, and WordPress actions/filters. Perfect for colleges, universities, or student organizations to showcase upcoming events on their WordPress site.  

---

## Features

- **Custom Post Type (Event)**: Create and manage campus events with fields like Event Name, Date, Time, Location, and Description.  
- **Shortcode**: `[campus_events]` displays a list of upcoming events on any page or post.  
- **Admin Menu**: Custom admin menu for easy event management.  
- **Security**: Nonces added for safe form submissions.  
- **Hooks**: WordPress actions and filters used to automatically order events and allow content customization.  
- **Frontend Styling**: Basic CSS for clean event listings.  

---

## Installation

1. Clone or download this repository:  

```bash
git clone https://github.com/Kokalasaiteja/campus-event-manager.git
````

2. Copy the folder to your WordPress plugins directory:

```
wp-content/plugins/campus-event-manager
```

3. Activate the plugin via the WordPress admin dashboard under **Plugins → Installed Plugins**.

---

## Usage

* **Create Events**: Go to the "Events" menu in the WordPress admin and add new events.
* **Display Events**: Add the `[campus_events]` shortcode to any page or post to display upcoming events.

---

## Example Shortcode

```html
[campus_events]
```

This will display a list of all upcoming events in chronological order.

---

## Screenshots

1. Admin Event Menu
2. Add New Event Form
3. Frontend Events List

---

## Changelog

### 1.0.0

* Initial release with custom post type, shortcode, admin menu, nonces, and frontend display.

---

## License

GPL2 or later – see [LICENSE](LICENSE) for details.

---

## Author

Kokala Sai Teja
