import json from './block.json';
import classnames from 'classnames';

const { registerBlockType } = nf;
const { serverSideRender: ServerSideRender } = wp;
const { registerBlockStyle } = wp.blocks;
const { compose, withState } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { Button, TextControl, PanelBody, SelectControl, Toolbar, } = wp.components;
const { BlockControls, BlockIcon, ColorPalette, InnerBlocks, InspectorControls, MediaPlaceholder, MediaReplaceFlow, MediaUpload, MediaUploadCheck, PlainText, RichText, withColors, } = wp.blockEditor;


registerBlockType( json, { 

  edit: (props) => {
    let { attributes, setAttributes, className } = props;
    let classes = classnames( className, 'editor', 'nf-hotel-map' );

    return (
      <div className={ classes }>
        <a id="directions"></a>
        <h3>Maps &amp; Directions</h3>
        <div className="container">

          <TextControl
            label={ 'Latitude' } 
            value={attributes.latitude}
            onChange={(value) => setAttributes({latitude: value})}
          />
          <TextControl
            label={ 'Longitude' } 
            value={attributes.longitude}
            onChange={(value) => setAttributes({longitude: value})}
          />

          <TextControl
            label={ 'Directions Link' } 
            value={attributes.directions}
            onChange={(value) => setAttributes({directions: value})}
          />
          
        </div>
      </div>
    )
  },


  save: props => { return <InnerBlocks.Content /> }

});
