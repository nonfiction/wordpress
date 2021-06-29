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
    let classes = classnames( className, 'editor', 'wp-block-nf-aside' );

    return (<>
      <div className={ classes }>
        <RichText tagName="header" className="nf-aside__header"
          value={ attributes.heading }
          onChange={ ( value ) => setAttributes( { heading: value } ) }
          placeholder={ 'Type Heading...' }
        />
        <div className="nf-aside__content">
          <InnerBlocks 
            orientation="vertical" 
            templateLock={false} 
            renderAppender={() => <InnerBlocks.ButtonBlockAppender />} 
          />
        </div>
      </div>
    </>)
  },


  save: (props) => {

    let { attributes, className } = props;
    let classes = classnames( className, 'wp-block-nf-aside' );

    return (
      <div className={ classes }>
        <RichText.Content tagName="header" className="nf-aside__header" value={ attributes.heading } />
        <div className="nf-aside__content"><InnerBlocks.Content /></div>
      </div>
    );

  },

});
