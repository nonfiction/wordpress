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
    let classes = classnames( className, 'editor', 'nf-hotel-meetings' );

    let
        allowedBlocks = [
            'core/image',
            'core/heading',
            'core/paragraph'
        ],
        template = [
            [ 'core/image', {} ],
            [ 'core/heading', { placeholder: 'Enter heading...'} ],
            [ 'core/paragraph', { placeholder: 'Enter information...'} ],
        ];

    return (
      <div className={ classes }>
        <div className="container">

          <div className="nf-hotel-meetings__main">
            <InnerBlocks 
              orientation="vertical" 
              allowedBlocks = { allowedBlocks }
              template = { template }
              templateLock={false} 
            />
            <a className="nf-hotel-meetings__button">Learn More</a>
            <TextControl
              value={attributes.link}
              onChange={(value) => setAttributes({link: value})}
            />
          </div>

          <div className="nf-hotel-meetings__aside">

            <header className="nf-hotel-meetings__aside-header">At a Glance</header>

            <div className="nf-hotel-meetings__aside-content">

              <RichText
                tagName="div" 
                value={ attributes.event_rooms_count }
                onChange={ ( value ) => setAttributes( { event_rooms_count: value.trim() } ) }
                placeholder={ 'Type number...' }
              />
              <label>{ `Event Room${(attributes.event_rooms_count !== '1')?'s':''}`}</label>
              <hr />

              <RichText
                tagName="div" 
                value={ attributes.event_space_size }
                onChange={ ( value ) => setAttributes( { event_space_size: value.trim() } ) }
                placeholder={ 'Type number...' }
              />
              <label>{ `Sq Ft of Largest Event Space` }</label>
              <hr />

              <RichText
                tagName="div" 
                value={ attributes.event_space_capacity }
                onChange={ ( value ) => setAttributes( { event_space_capacity: value.trim() } ) }
                placeholder={ 'Type number...' }
              />
              <label>{ `Capacity of Largest Event Space` }</label>
              <hr />

              <RichText
                tagName="div" 
                value={ attributes.max_breakout_rooms }
                onChange={ ( value ) => setAttributes( { max_breakout_rooms: value.trim() } ) }
                placeholder={ 'Type number...' }
              />
              <label>{ `Maximum Breakout Room${(attributes.max_breakout_rooms !== '1')?'s':''}`}</label>
              <hr />

              <a className="nf-hotel-meetings__button aside">Contact Events</a>

            </div>

          </div>

        </div>


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

      </div>
    )
  },


  // save: (props) => null,
  save: props => { return <InnerBlocks.Content /> }

});
