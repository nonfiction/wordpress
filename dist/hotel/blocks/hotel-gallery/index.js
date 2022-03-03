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
    let classes = classnames( className, 'editor' );
    let style = {
      marginBottom: '-30px',
    }

    return (
      <div className={ classes }>
        <div className="container">
          <h3 style={ style }>Image Gallery</h3>
          <p>Choose images in sidebar</p>
        </div>
      </div>
    )
  },


  save: (props) => null,

});
