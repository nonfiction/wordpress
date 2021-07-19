// This is contains JavaScript for blocktypes (editor)
// WP hook: enqueue_block_editor_assets

const { registerBlockType } = wp.blocks;

// Initialize nf object
global.nf = global.nf || {};

// Method to register new blocktypes with json and name in args
nf.registerBlockType = function (json = {}, override = {}) {

  let args = { ...json, ...override };
  let name = args.name || false;

  let icon = args.icon || "";
  args.icon = icon.replace('dashicons-', '');

  if (name) {
    registerBlockType(name, args);
  }

}

// Import block-related index.js files
// app/blocks/*/index.js
// app/*/blocks/*/index.js
function importAll(r) { r.keys().forEach(r) }
importAll(require.context(__dirname, true, /^\.\/blocks\/[\w\-]+\/index\.js$/));
importAll(require.context(__dirname, true, /^\.\/[\w\-]+\/blocks\/[\w\-]+\/index\.js$/));

// Custom scripting below
//

// disable welcome guide
wp.data.select( "core/edit-post" ).isFeatureActive( "welcomeGuide" ) && wp.data.dispatch( "core/edit-post" ).toggleFeature( "welcomeGuide" )
