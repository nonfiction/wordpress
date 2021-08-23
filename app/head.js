// This is contains JavaScript for the theme that loads in the <head>
// WP hook: wp_enqueue_scripts

// Import primary styles
import "./base.css";
import './style.css';

// Import post type-related script.js files
// app/*/script.js
function importAll(r) { r.keys().forEach(r) }
importAll(require.context(__dirname, true, /^\.\/[\w\-]+\/script\.js$/));

// Custom scripting below
//
