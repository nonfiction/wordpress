// This is contains JavaScript for blocks (shared by front-end and editor)
// WP hook: enqueue_block_assets (in footer)

// Import block-related script.js files
// app/blocks/*/script.js
// app/*/blocks/*/script.js
function importAll(r) { r.keys().forEach(r) }
importAll(require.context(__dirname, true, /^\.\/blocks\/[\w\-]+\/script\.js$/));
importAll(require.context(__dirname, true, /^\.\/[\w\-]+\/blocks\/[\w\-]+\/script\.js$/));

// Custom scripting below
//
