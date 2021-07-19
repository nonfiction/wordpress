import './style.css';

// Custom scripting below
//

/*
import '@iconfu/svg-inject';

window.SVGInject.setOptions({
    afterInject: function (img, svg) {
        svg.removeAttribute('width');
        svg.removeAttribute('height');
    }
});
*/

global.q = ( selector ) => {
  return document.querySelectorAll( selector );
}

// Create new HTML element from string
const el = ( domstring ) => {
  const html = new DOMParser().parseFromString( domstring , 'text/html');
  return html.body.firstChild;
};

// Append HTML string to selector
const append = ( selector, domstring) => {
  document.querySelectorAll( selector ).forEach( (parent) => {
    parent.appendChild( el(domstring) );
  });
}

// Prepend HTML string to selector
const prepend = ( selector, domstring ) => {
  document.querySelectorAll( selector ).forEach( (parent) => {
    parent.insertBefore( el(domstring), parent.childNodes[0] );
  });
}