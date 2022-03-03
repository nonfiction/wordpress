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
    let classes = classnames( className, 'editor', 'nf-hotel-reviews' );

    return (
      <div className={ classes }>
        <h3>Reviews</h3>

        <TextControl
          label="TrustYou Hotel ID" 
          value={attributes.trustyou_id}
          onChange={(value) => setAttributes({trustyou_id: value})}
        />

        <iframe src={ `//api.trustyou.com/hotels/${attributes.trustyou_id}/tops_flops.html?iframe_resizer=true` } 
          id="trust-you-iframe" scrolling="no" frameBorder="0" />

      </div>
    )
  },


  save: (props) => null,

});
