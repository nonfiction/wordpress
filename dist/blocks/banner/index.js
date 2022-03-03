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
    
    let color = (attributes.color) ? `is-color-${attributes.color}` : 'is-color-dark';
    let moreClasses = classnames( 'nf-banner', color );

    let style =  { 
      backgroundImage: `url(${attributes.background_url})`,
    };
              
    return (<div className={ classes }>
      
        <div className={ moreClasses } style={ style }>
          <div className="nf-banner__main">
            <div className="nf-banner__inner">
              <RichText tagName="h1" className="nf-banner__heading" 
                placeholder={ 'Enter text here' } 
                value={ attributes.heading }
                onChange={ ( value ) => setAttributes( { heading: value } ) }
              />
              <RichText tagName="p"  className="nf-banner__content"
                placeholder={ 'Enter content here' } 
                value={ attributes.content }
                onChange={ ( value ) => setAttributes( { content: value } ) }
              />
            </div>
          </div>
            
          <div className="nf-banner__aside">
          </div>

        </div>

        {/* <ServerSideRender block="nf/banner" attributes={ attributes } /> */}


        <BlockControls>

          <MediaUploadCheck>
            <MediaUpload
              allowedTypes={ ['image'] }

              onSelect={ ( media ) => setAttributes({ background_url: media.url, background_id: media.id }) }
              value={ attributes.background_id }
              
              render={ ( { open } ) => (
                <div className="components-toolbar">
                  <Button onClick={ open } icon="format-image" label="Background Image" />
                </div>
              ) }
            />
          </MediaUploadCheck>

        </BlockControls>

      <InspectorControls>
        <PanelBody title="Aside">
            <RichText tagName="h3" className="nf-banner__subheading editor" 
              placeholder={ 'Enter text here' } 
              value={ attributes.subheading }
              onChange={ ( value ) => setAttributes( { subheading: value } ) }
            />
            <div className="nf-banner__button editor">
              <PlainText
                value={ attributes.button_text }
                onChange={ ( value ) => setAttributes( { button_text: value } ) }
                placeholder={ 'Type Text...' }
              />
              <PlainText
                value={ attributes.button_link }
                onChange={ ( value ) => setAttributes( { button_link: value } ) }
                placeholder={ 'Type URL...' }
              />
            </div>
        </PanelBody>
      </InspectorControls>



      </div>
    )
  },


  save: (props) => null,

});

