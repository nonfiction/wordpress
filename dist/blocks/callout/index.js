import json from './block.json';
import classnames from 'classnames';

const { registerBlockType } = nf;
const { serverSideRender: ServerSideRender } = wp;
const { registerBlockStyle } = wp.blocks;
const { compose, withState } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { Button, TextControl, PanelBody, SelectControl, Toolbar, } = wp.components;
const { BlockControls, BlockIcon, ColorPalette, InnerBlocks, InspectorControls, MediaPlaceholder, MediaReplaceFlow, MediaUpload, MediaUploadCheck, PlainText, RichText, withColors, URLInputButton } = wp.blockEditor;

// Register Block Type
registerBlockType( json, { 

  edit: (props) => {
    let { attributes, setAttributes, className } = props;

    let style =  { 
      backgroundImage: `url(${attributes.photo_url})`,
    };

    let icon = attributes.icon_url;
    // console.log(attributes);
    let color = (attributes.color) ? `is-color-${attributes.color}` : 'is-color-dark';
    // console.log(color);
    let classes = classnames( className, 'editor', 'nf-callout', color );
    // console.log(classes);

    return (<>

      <div className={ classes }>
        <div className="nf-callout__image" style={ style }></div>
        <div className="nf-callout__content">
          <img className="nf-callout__icon" src={ icon } />
          <RichText
            tagName="h3" className="nf-callout__title"
            value={ attributes.title }
            onChange={ ( value ) => setAttributes( { title: value } ) }
            placeholder={ 'Type title...' }
            keepPlaceholderOnFocus={true}
          />
          <RichText
            tagName="div" className="nf-callout__description"
            value={ attributes.description }
            onChange={ ( value ) => setAttributes( { description: value } ) }
            placeholder={ 'Type description...' }
            keepPlaceholderOnFocus={true}
          />
          <div className="nf-callout__button">
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
        </div>
      </div>


      <BlockControls>

        <MediaUploadCheck>
          <MediaUpload
            allowedTypes={ ['image'] }

            onSelect={ ( media ) => setAttributes({ photo_url: ( (media.sizes.large) ? media.sizes.large.url : media.url ), photo_id: media.id }) }
            value={ attributes.photo_id }
            
            render={ ( { open } ) => (
              <div className="components-toolbar">
                <Button onClick={ open } icon="format-image" label="Photo" />
              </div>
            ) }
          />
        </MediaUploadCheck>

      </BlockControls>

      <InspectorControls>
        <PanelBody title="Background Color">
          <SelectControl
            value={ attributes.color }
            options={[ {label:'Dark',value:'dark'}, {label:'Light',value:'light'}, {label:'Blue',value:'blue'}, {label:'Yellow',value:'yellow'} ]}
            onChange={ ( color ) => { props.setAttributes( { color } ); } }
          />
        </PanelBody>
        <PanelBody title="Callout Icon (optional)">
          <MediaUploadCheck>
            <MediaUpload
              allowedTypes={ ['image'] }

              label='Icon'
              onSelect={ ( media ) => setAttributes({ icon_url: ( (media.sizes.large) ? media.sizes.large.url : media.url ), icon_id: media.id }) }
              value={ attributes.icon_id }
              
              render={ ( { open } ) => (
                <div className="components-toolbar">
                  <Button onClick={ open } icon="format-image" label="Icon" />
                </div>
              ) }
            />
        </MediaUploadCheck>
        </PanelBody>
      </InspectorControls>

    </>)
  },


  save: (props) => null,

});
