// This is contains JavaScript for admin pages
// WP hook: admin_enqueue_scripts (in footer)

import "./views/css/base.css";
import "../config/wp/admin.js";

if (typeof window.disable_comments == "undefined") {
  window.disable_comments = { disabled_blocks: '' }
}

// Custom scripting below
//
