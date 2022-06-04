(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

/* jshint esversion: 6 */
(function (v, undefined) {
  'use strict'; // scroll to top button

  {
    var st_buttons = document.querySelectorAll('.vamtam-scroll-to-top');

    if (st_buttons.length) {
      var side_st_button = document.getElementById('scroll-to-top'),
          pageMiddle = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight) / 2;

      if (side_st_button) {
        v.addScrollHandler({
          init: function init() {},
          measure: function measure() {
            pageMiddle = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight) / 2;
          },
          mutate: function mutate(cpos) {
            if (cpos > pageMiddle) {
              side_st_button.style.opacity = 1;
              side_st_button.style.transform = 'scale3d( 1, 1, 1 )';
            } else {
              side_st_button.style.opacity = '';
              side_st_button.style.transform = '';
            }
          }
        });
      }

      document.addEventListener('click', function (e) {
        if (e.target.classList.contains('vamtam-scroll-to-top')) {
          e.preventDefault(); // iOS Safari uses a simple animation, normal browsers use scroll-behavior:smooth

          if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
            window.scrollTo(0, 0);
          } else {
            window.scroll({
              left: 0,
              top: 0,
              behavior: 'smooth'
            });
          }
        }
      }, true);
    }
  }
})(window.VAMTAM);

},{}]},{},[1]);
