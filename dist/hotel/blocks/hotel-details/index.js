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
    let classes = classnames( className, 'editor', 'nf-hotel-details' );


    let
        allowedBlocks = [
            'core/heading',
            'core/paragraph'
        ],
        template = [
            [ 'core/heading', { placeholder: 'Enter heading...'} ],
            [ 'core/paragraph', { placeholder: 'Enter introduction...'} ],
        ];

    return (
      <div className={ classes }>
        <div className="container">

          <div className="nf-hotel-details__main">
            <InnerBlocks 
              orientation="vertical" 
              allowedBlocks = { allowedBlocks }
              template = { template }
              templateLock={false} 
            />
          </div>

          <div className="nf-hotel-details__aside">

            <header className="nf-hotel-details__aside-header">Hotel</header>

            <div className="nf-hotel-details__aside-content">

              <RichText
                tagName="div" className="nf-hotel-details__address"
                value={ attributes.address }
                onChange={ ( value ) => setAttributes( { address: value } ) }
                placeholder={ 'Type address...' }
              />
              <RichText
                tagName="div" className="nf-hotel-details__phone"
                value={ attributes.phone }
                onChange={ ( value ) => setAttributes( { phone: value } ) }
                placeholder={ 'Type phone...' }
              />

              <hr/>
              <div className="nf-hotel-details__times">
                <div className="nf-hotel-details__check-in">
                  <label>CHECK-IN</label>
                  <RichText
                    tagName="time"
                    value={ attributes.check_in }
                    onChange={ ( value ) => setAttributes( { check_in: value } ) }
                    placeholder={ '3:00 PM' }
                  />
                </div>
                <div className="nf-hotel-details__check-out">
                  <label>CHECK-OUT</label>
                  <RichText
                    tagName="time"
                    value={ attributes.check_out }
                    onChange={ ( value ) => setAttributes( { check_out: value } ) }
                    placeholder={ '11:00 AM' }
                  />
                </div>
              </div>
            </div>

          </div>

          {/* 
          
          <ServerSideRender block="nf/hotel-details" attributes={ attributes } /> 

          <TextControl
            label={ 'Introduction' } 
            value={attributes.introduction}
            onChange={(value) => setAttributes({introduction: value})}
          />
          <TextControl
            label={ 'event_rooms_count' } 
            value={attributes.event_rooms_count}
            onChange={(value) => setAttributes({event_rooms_count: value})}
          />
          
          */}


        </div>


          {/*
        <InspectorControls>
          <PanelBody title="Hotel Details">
              <div className="editor">
                <PlainText
                  value={ attributes.introduction }
                  onChange={ ( value ) => setAttributes( { introduction: value } ) }
                  placeholder={ 'Introduction' }
                />
                <PlainText
                  value={ attributes.event_rooms_count }
                  onChange={ ( value ) => setAttributes( { event_rooms_count: value } ) }
                  placeholder={ 'Event Rooms Count' }
                />
                <PlainText
                  value={ attributes.event_space_size }
                  onChange={ ( value ) => setAttributes( { event_space_size: value } ) }
                  placeholder={ 'Event Space Size' }
                />
              </div>
          </PanelBody>
        </InspectorControls>
          */}

      </div>
    )
  },


  // save: (props) => null,
  save: props => { return <InnerBlocks.Content /> }

});
