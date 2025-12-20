var tco="object"==typeof tco?tco:{};tco.csHead=function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=91)}({31:function(e,t){String.prototype.includes||(String.prototype.includes=function(e,t){"use strict";return"number"!=typeof t&&(t=0),!(t+e.length>this.length)&&-1!==this.indexOf(e,t)}),function(){if("function"==typeof window.CustomEvent)return!1;function e(e,t){t=t||{bubbles:!1,cancelable:!1,detail:void 0};var n=document.createEvent("CustomEvent");return n.initCustomEvent(e,t.bubbles,t.cancelable,t.detail),n}e.prototype=window.Event.prototype,window.CustomEvent=e}(),Array.prototype.includes||Object.defineProperty(Array.prototype,"includes",{value:function(e,t){if(null==this)throw new TypeError('"this" is null or not defined');var n=Object(this),r=n.length>>>0;if(0===r)return!1;var o=0|t,i=Math.max(o>=0?o:r-Math.abs(o),0);function s(e,t){return e===t||"number"==typeof e&&"number"==typeof t&&isNaN(e)&&isNaN(t)}for(;i<r;){if(s(n[i],e))return!0;i++}return!1}}),Array.prototype.find||Object.defineProperty(Array.prototype,"find",{value:function(e){if(null==this)throw new TypeError('"this" is null or not defined');var t=Object(this),n=t.length>>>0;if("function"!=typeof e)throw new TypeError("predicate must be a function");for(var r=arguments[1],o=0;o<n;){var i=t[o];if(e.call(r,i,o,t))return i;o++}},configurable:!0,writable:!0}),window.NodeList&&!NodeList.prototype.forEach&&(NodeList.prototype.forEach=function(e,t){t=t||window;for(var n=0;n<this.length;n++)e.call(t,this[n],n,this)}),"function"!=typeof Object.assign&&Object.defineProperty(Object,"assign",{value:function(e,t){"use strict";if(null==e)throw new TypeError("Cannot convert undefined or null to object");for(var n=Object(e),r=1;r<arguments.length;r++){var o=arguments[r];if(null!=o)for(var i in o)Object.prototype.hasOwnProperty.call(o,i)&&(n[i]=o[i])}return n},writable:!0,configurable:!0}),Object.values||(Object.values=function(e){return Object.keys(e).map(function(t){return e[t]})})},5:function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(t){return"function"==typeof Symbol&&"symbol"===n(Symbol.iterator)?e.exports=r=function(e){return n(e)}:e.exports=r=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":n(e)},r(t)}e.exports=r},91:function(e,t,n){"use strict";n.r(t);n(31);var r=n(5),o=n.n(r);function i(e,t){var n=document.getElementById(e);n&&(t=n.textContent+t,n.remove()),window.document.head.appendChild(function(e,t){var n=document.createElement("style");n.type="text/css",n.id=t,n.styleSheet?n.styleSheet.cssText=e:n.appendChild(window.document.createTextNode(e));return n}(t,e))}
/*!
* modernizr v3.7.1
* Build https://modernizr.com/download?-cssanimations-csspointerevents-csstransforms-csstransforms3d-csstransitions-passiveeventlisteners-preserve3d-touchevents-dontmin
*
* Copyright (c)
*  Faruk Ates
*  Paul Irish
*  Alex Sexton
*  Ryan Seddon
*  Patrick Kettner
*  Stu Cox
*  Richard Herrera
*  Veeck
* MIT License
*/
!function(e,t,n){var r=[],i={_version:"3.7.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){r.push({name:e,fn:t,options:n})},addAsyncTest:function(e){r.push({name:null,fn:e})}},s=function(){};s.prototype=i,s=new s;var u=[];function a(e,t){return o()(e)===t}var l=i._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];i._prefixes=l;var c=t.documentElement,f="svg"===c.nodeName.toLowerCase();function d(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):f?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function p(){var e=t.body;return e||((e=d(f?"svg":"body")).fake=!0),e}function y(e,n,r,o){var i,s,u,a,l="modernizr",f=d("div"),y=p();if(parseInt(r,10))for(;r--;)(u=d("div")).id=o?o[r]:l+(r+1),f.appendChild(u);return(i=d("style")).type="text/css",i.id="s"+l,(y.fake?y:f).appendChild(i),y.appendChild(f),i.styleSheet?i.styleSheet.cssText=e:i.appendChild(t.createTextNode(e)),f.id=l,y.fake&&(y.style.background="",y.style.overflow="hidden",a=c.style.overflow,c.style.overflow="hidden",c.appendChild(y)),s=n(f,e),y.fake?(y.parentNode.removeChild(y),c.style.overflow=a,c.offsetHeight):f.parentNode.removeChild(f),!!s}var v=function(){var t=e.matchMedia||e.msMatchMedia;return t?function(e){var n=t(e);return n&&n.matches||!1}:function(t){var n=!1;return y("@media "+t+" { #modernizr { position: absolute; } }",function(t){n="absolute"===(e.getComputedStyle?e.getComputedStyle(t,null):t.currentStyle).position}),n}}();i.mq=v,
/*!
  {
    "name": "Touch Events",
    "property": "touchevents",
    "caniuse": "touch",
    "tags": ["media", "attribute"],
    "notes": [{
      "name": "Touch Events spec",
      "href": "https://www.w3.org/TR/2013/WD-touch-events-20130124/"
    }],
    "warnings": [
      "Indicates if the browser supports the Touch Events spec, and does not necessarily reflect a touchscreen device"
    ],
    "knownBugs": [
      "False-positive on some configurations of Nokia N900",
      "False-positive on some BlackBerry 6.0 builds – https://github.com/Modernizr/Modernizr/issues/372#issuecomment-3112695"
    ]
  }
  !*/
s.addTest("touchevents",function(){if("ontouchstart"in e||e.TouchEvent||e.DocumentTouch&&t instanceof DocumentTouch)return!0;var n=["(",l.join("touch-enabled),("),"heartz",")"].join("");return v(n)});var m="Moz O ms Webkit",h=i._config.usePrefixes?m.split(" "):[];function b(e,t){return!!~(""+e).indexOf(t)}i._cssomPrefixes=h;var w={elem:d("modernizr")};s._q.push(function(){delete w.elem});var g={style:w.elem.style};function S(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function C(t,r){var o=t.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(S(t[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var i=[];o--;)i.push("("+S(t[o])+":"+r+")");return y("@supports ("+(i=i.join(" or "))+") { #modernizr { position: absolute; } }",function(t){return"absolute"===function(t,n,r){var o;if("getComputedStyle"in e){o=getComputedStyle.call(e,t,n);var i=e.console;null!==o?r&&(o=o.getPropertyValue(r)):i&&i[i.error?"error":"log"].call(i,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}else o=!n&&t.currentStyle&&t.currentStyle[r];return o}(t,null,"position")})}return n}function x(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}s._q.unshift(function(){delete g.style});var T=i._config.usePrefixes?m.toLowerCase().split(" "):[];function E(e,t){return function(){return e.apply(t,arguments)}}function j(e,t,r,o,i){var s=e.charAt(0).toUpperCase()+e.slice(1),u=(e+" "+h.join(s+" ")+s).split(" ");return a(t,"string")||a(t,"undefined")?function(e,t,r,o){if(o=!a(o,"undefined")&&o,!a(r,"undefined")){var i=C(e,r);if(!a(i,"undefined"))return i}for(var s,u,l,c,f,p=["modernizr","tspan","samp"];!g.style&&p.length;)s=!0,g.modElem=d(p.shift()),g.style=g.modElem.style;function y(){s&&(delete g.style,delete g.modElem)}for(l=e.length,u=0;u<l;u++)if(c=e[u],f=g.style[c],b(c,"-")&&(c=x(c)),g.style[c]!==n){if(o||a(r,"undefined"))return y(),"pfx"!==t||c;try{g.style[c]=r}catch(e){}if(g.style[c]!==f)return y(),"pfx"!==t||c}return y(),!1}(u,t,o,i):function(e,t,n){var r;for(var o in e)if(e[o]in t)return!1===n?e[o]:a(r=t[e[o]],"function")?E(r,n||t):r;return!1}(u=(e+" "+T.join(s+" ")+s).split(" "),t,r)}function O(e,t,r){return j(e,n,n,t,r)}i._domPrefixes=T,i.testAllProps=j,i.testAllProps=O,
/*!
  {
    "name": "CSS Animations",
    "property": "cssanimations",
    "caniuse": "css-animation",
    "polyfills": ["transformie", "csssandpaper"],
    "tags": ["css"],
    "warnings": ["Android < 4 will pass this test, but can only animate a single property at a time"],
    "notes": [{
      "name": "Article: 'Dispelling the Android CSS animation myths'",
      "href": "https://web.archive.org/web/20180602074607/https://daneden.me/2011/12/14/putting-up-with-androids-bullshit/"
    }]
  }
  !*/
s.addTest("cssanimations",O("animationName","a",!0)),
/*!
  {
    "name": "CSS Pointer Events",
    "caniuse": "pointer-events",
    "property": "csspointerevents",
    "authors": ["ausi"],
    "tags": ["css"],
    "builderAliases": ["css_pointerevents"],
    "notes": [{
        "name": "MDN Docs",
        "href": "https://developer.mozilla.org/en-US/docs/Web/CSS/pointer-events"
      },{
        "name": "Test Project Page",
        "href": "https://ausi.github.com/Feature-detection-technique-for-pointer-events/"
      },{
        "name": "Test Project Wiki",
        "href": "https://github.com/ausi/Feature-detection-technique-for-pointer-events/wiki"
      },{
        "name": "Related Github Issue",
        "href": "https://github.com/Modernizr/Modernizr/issues/80"
    }]
  }
  !*/
s.addTest("csspointerevents",function(){var e=d("a").style;return e.cssText="pointer-events:auto","auto"===e.pointerEvents}),
/*!
  {
    "name": "CSS Transforms",
    "property": "csstransforms",
    "caniuse": "transforms2d",
    "tags": ["css"]
  }
  !*/
s.addTest("csstransforms",function(){return-1===navigator.userAgent.indexOf("Android 2.")&&O("transform","scale(1)",!0)});
/*!
  {
    "name": "CSS Supports",
    "property": "supports",
    "caniuse": "css-featurequeries",
    "tags": ["css"],
    "builderAliases": ["css_supports"],
    "notes": [{
      "name": "W3C Spec",
      "href": "https://dev.w3.org/csswg/css3-conditional/#at-supports"
    },{
      "name": "Related Github Issue",
      "href": "https://github.com/Modernizr/Modernizr/issues/648"
    },{
      "name": "W3C Spec",
      "href": "https://dev.w3.org/csswg/css3-conditional/#the-csssupportsrule-interface"
    }]
  }
  !*/
var P="CSS"in e&&"supports"in e.CSS,_="supportsCSS"in e;s.addTest("supports",P||_),
/*!
  {
    "name": "CSS Transforms 3D",
    "property": "csstransforms3d",
    "caniuse": "transforms3d",
    "tags": ["css"],
    "warnings": [
      "Chrome may occasionally fail this test on some systems; more info: https://bugs.chromium.org/p/chromium/issues/detail?id=129004"
    ]
  }
  !*/
s.addTest("csstransforms3d",function(){return!!O("perspective","1px",!0)}),
/*!
  {
    "name": "CSS Transform Style preserve-3d",
    "property": "preserve3d",
    "authors": ["denyskoch", "aFarkas"],
    "tags": ["css"],
    "notes": [{
      "name": "MDN Docs",
      "href": "https://developer.mozilla.org/en-US/docs/Web/CSS/transform-style"
    },{
      "name": "Related Github Issue",
      "href": "https://github.com/Modernizr/Modernizr/issues/1748"
    }]
  }
  !*/
s.addTest("preserve3d",function(){var t,n,r=e.CSS,o=!1;return!!(r&&r.supports&&r.supports("(transform-style: preserve-3d)"))||(t=d("a"),n=d("a"),t.style.cssText="display: block; transform-style: preserve-3d; transform-origin: right; transform: rotateY(40deg);",n.style.cssText="display: block; width: 9px; height: 1px; background: #000; transform-origin: right; transform: rotateY(40deg);",t.appendChild(n),c.appendChild(t),o=n.getBoundingClientRect(),c.removeChild(t),o=o.width&&o.width<4)}),
/*!
  {
    "name": "CSS Transitions",
    "property": "csstransitions",
    "caniuse": "css-transitions",
    "tags": ["css"]
  }
  !*/
s.addTest("csstransitions",O("transition","all",!0)),
/*!
  {
    "property": "passiveeventlisteners",
    "tags": ["dom"],
    "authors": ["Rick Byers"],
    "name": "Passive event listeners",
    "notes": [{
        "name": "WHATWG Spec",
        "href": "https://dom.spec.whatwg.org/#dom-addeventlisteneroptions-passive"
      },{
        "name": "WICG explainer",
        "href": "https://github.com/WICG/EventListenerOptions/blob/gh-pages/explainer.md"
    }]
  }
  !*/
s.addTest("passiveeventlisteners",function(){var t=!1;try{var n=Object.defineProperty({},"passive",{get:function(){t=!0}}),r=function(){};e.addEventListener("testPassiveEventSupport",r,n),e.removeEventListener("testPassiveEventSupport",r,n)}catch(n){}return t}),function(){var e,t,n,o,i,l;for(var c in r)if(r.hasOwnProperty(c)){if(e=[],(t=r[c]).name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(o=a(t.fn,"function")?t.fn():t.fn,i=0;i<e.length;i++)1===(l=e[i].split(".")).length?s[l[0]]=o:(!s[l[0]]||s[l[0]]instanceof Boolean||(s[l[0]]=new Boolean(s[l[0]])),s[l[0]][l[1]]=o),u.push((o?"":"no-")+l.join("-"))}}(),delete i.addTest,delete i.addAsyncTest;for(var z=0;z<s._q.length;z++)s._q[z]();e.csModernizr=s}(window,document);window.Modernizr=window.Modernizr||window.csModernizr,window.csGlobal=window.csGlobal||{},window.csGlobal.lateCSS=function(e){for(var t="",n=window.document.querySelectorAll('script[data-cs-late-style="'.concat(e,'"]')),r=0;r<n.length;++r)t+=n[r].textContent;i(e,t)},window.addEventListener("DOMContentLoaded",function(e){window.csModernizr.preserve3d||document.body.classList.add("ie")})}});
//# sourceMappingURL=cs-head.6466c24.js.map