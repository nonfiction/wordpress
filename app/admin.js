// This is contains JavaScript for admin pages
// WP hook: admin_enqueue_scripts (in footer)

import "./base.css";
import "../config/wp/admin.js";

// Import post type-related admin.js files
// app/*/admin.js
function importAll(r) { r.keys().forEach(r) }
importAll(require.context(__dirname, true, /^\.\/[\w\-]+\/admin\.js$/));

if (typeof window.disable_comments == "undefined") {
  window.disable_comments = { disabled_blocks: '' }
}

// Custom scripting below
//
