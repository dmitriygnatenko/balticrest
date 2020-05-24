/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/build/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/balticrest/balticrest-ie9.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/balticrest/balticrest-ie9.js":
/*!*********************************************!*\
  !*** ./assets/balticrest/balticrest-ie9.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _html5shiv_js_html5shiv_min_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./html5shiv/js/html5shiv.min.js */ "./assets/balticrest/html5shiv/js/html5shiv.min.js");
/* harmony import */ var _html5shiv_js_html5shiv_min_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_html5shiv_js_html5shiv_min_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _respond_js_respond_min_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./respond/js/respond.min.js */ "./assets/balticrest/respond/js/respond.min.js");
/* harmony import */ var _respond_js_respond_min_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_respond_js_respond_min_js__WEBPACK_IMPORTED_MODULE_1__);



/***/ }),

/***/ "./assets/balticrest/html5shiv/js/html5shiv.min.js":
/*!*********************************************************!*\
  !*** ./assets/balticrest/html5shiv/js/html5shiv.min.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * @preserve HTML5 Shiv 3.7.2 | @afarkas @jdalton @jon_neal @rem | MIT/GPL2 Licensed
 */
!function (a, b) {
  function c(a, b) {
    var c = a.createElement("p"),
        d = a.getElementsByTagName("head")[0] || a.documentElement;
    return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild);
  }

  function d() {
    var a = t.elements;
    return "string" == typeof a ? a.split(" ") : a;
  }

  function e(a, b) {
    var c = t.elements;
    "string" != typeof c && (c = c.join(" ")), "string" != typeof a && (a = a.join(" ")), t.elements = c + " " + a, j(b);
  }

  function f(a) {
    var b = s[a[q]];
    return b || (b = {}, r++, a[q] = r, s[r] = b), b;
  }

  function g(a, c, d) {
    if (c || (c = b), l) return c.createElement(a);
    d || (d = f(c));
    var e;
    return e = d.cache[a] ? d.cache[a].cloneNode() : p.test(a) ? (d.cache[a] = d.createElem(a)).cloneNode() : d.createElem(a), !e.canHaveChildren || o.test(a) || e.tagUrn ? e : d.frag.appendChild(e);
  }

  function h(a, c) {
    if (a || (a = b), l) return a.createDocumentFragment();
    c = c || f(a);

    for (var e = c.frag.cloneNode(), g = 0, h = d(), i = h.length; i > g; g++) {
      e.createElement(h[g]);
    }

    return e;
  }

  function i(a, b) {
    b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function (c) {
      return t.shivMethods ? g(c, a, b) : b.createElem(c);
    }, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + d().join().replace(/[\w\-:]+/g, function (a) {
      return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")';
    }) + ");return n}")(t, b.frag);
  }

  function j(a) {
    a || (a = b);
    var d = f(a);
    return !t.shivCSS || k || d.hasCSS || (d.hasCSS = !!c(a, "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")), l || i(a, d), a;
  }

  var k,
      l,
      m = "3.7.2",
      n = a.html5 || {},
      o = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
      p = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
      q = "_html5shiv",
      r = 0,
      s = {};
  !function () {
    try {
      var a = b.createElement("a");
      a.innerHTML = "<xyz></xyz>", k = "hidden" in a, l = 1 == a.childNodes.length || function () {
        b.createElement("a");
        var a = b.createDocumentFragment();
        return "undefined" == typeof a.cloneNode || "undefined" == typeof a.createDocumentFragment || "undefined" == typeof a.createElement;
      }();
    } catch (c) {
      k = !0, l = !0;
    }
  }();
  var t = {
    elements: n.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",
    version: m,
    shivCSS: n.shivCSS !== !1,
    supportsUnknownElements: l,
    shivMethods: n.shivMethods !== !1,
    type: "default",
    shivDocument: j,
    createElement: g,
    createDocumentFragment: h,
    addElements: e
  };
  a.html5 = t, j(b);
}(this, document);

/***/ }),

/***/ "./assets/balticrest/respond/js/respond.min.js":
/*!*****************************************************!*\
  !*** ./assets/balticrest/respond/js/respond.min.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*! Respond.js v1.4.2: min/max-width media query polyfill * Copyright 2013 Scott Jehl
 * Licensed under https://github.com/scottjehl/Respond/blob/master/LICENSE-MIT
 *  */
!function (a) {
  "use strict";

  a.matchMedia = a.matchMedia || function (a) {
    var b,
        c = a.documentElement,
        d = c.firstElementChild || c.firstChild,
        e = a.createElement("body"),
        f = a.createElement("div");
    return f.id = "mq-test-1", f.style.cssText = "position:absolute;top:-100em", e.style.background = "none", e.appendChild(f), function (a) {
      return f.innerHTML = '&shy;<style media="' + a + '"> #mq-test-1 { width: 42px; }</style>', c.insertBefore(e, d), b = 42 === f.offsetWidth, c.removeChild(e), {
        matches: b,
        media: a
      };
    };
  }(a.document);
}(this), function (a) {
  "use strict";

  function b() {
    u(!0);
  }

  var c = {};
  a.respond = c, c.update = function () {};

  var d = [],
      e = function () {
    var b = !1;

    try {
      b = new a.XMLHttpRequest();
    } catch (c) {
      b = new a.ActiveXObject("Microsoft.XMLHTTP");
    }

    return function () {
      return b;
    };
  }(),
      f = function f(a, b) {
    var c = e();
    c && (c.open("GET", a, !0), c.onreadystatechange = function () {
      4 !== c.readyState || 200 !== c.status && 304 !== c.status || b(c.responseText);
    }, 4 !== c.readyState && c.send(null));
  };

  if (c.ajax = f, c.queue = d, c.regex = {
    media: /@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,
    keyframes: /@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,
    urls: /(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,
    findStyles: /@media *([^\{]+)\{([\S\s]+?)$/,
    only: /(only\s+)?([a-zA-Z]+)\s?/,
    minw: /\([\s]*min\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/,
    maxw: /\([\s]*max\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/
  }, c.mediaQueriesSupported = a.matchMedia && null !== a.matchMedia("only all") && a.matchMedia("only all").matches, !c.mediaQueriesSupported) {
    var g,
        h,
        i,
        j = a.document,
        k = j.documentElement,
        l = [],
        m = [],
        n = [],
        o = {},
        p = 30,
        q = j.getElementsByTagName("head")[0] || k,
        r = j.getElementsByTagName("base")[0],
        s = q.getElementsByTagName("link"),
        t = function t() {
      var a,
          b = j.createElement("div"),
          c = j.body,
          d = k.style.fontSize,
          e = c && c.style.fontSize,
          f = !1;
      return b.style.cssText = "position:absolute;font-size:1em;width:1em", c || (c = f = j.createElement("body"), c.style.background = "none"), k.style.fontSize = "100%", c.style.fontSize = "100%", c.appendChild(b), f && k.insertBefore(c, k.firstChild), a = b.offsetWidth, f ? k.removeChild(c) : c.removeChild(b), k.style.fontSize = d, e && (c.style.fontSize = e), a = i = parseFloat(a);
    },
        u = function u(b) {
      var c = "clientWidth",
          d = k[c],
          e = "CSS1Compat" === j.compatMode && d || j.body[c] || d,
          f = {},
          o = s[s.length - 1],
          r = new Date().getTime();
      if (b && g && p > r - g) return a.clearTimeout(h), h = a.setTimeout(u, p), void 0;
      g = r;

      for (var v in l) {
        if (l.hasOwnProperty(v)) {
          var w = l[v],
              x = w.minw,
              y = w.maxw,
              z = null === x,
              A = null === y,
              B = "em";
          x && (x = parseFloat(x) * (x.indexOf(B) > -1 ? i || t() : 1)), y && (y = parseFloat(y) * (y.indexOf(B) > -1 ? i || t() : 1)), w.hasquery && (z && A || !(z || e >= x) || !(A || y >= e)) || (f[w.media] || (f[w.media] = []), f[w.media].push(m[w.rules]));
        }
      }

      for (var C in n) {
        n.hasOwnProperty(C) && n[C] && n[C].parentNode === q && q.removeChild(n[C]);
      }

      n.length = 0;

      for (var D in f) {
        if (f.hasOwnProperty(D)) {
          var E = j.createElement("style"),
              F = f[D].join("\n");
          E.type = "text/css", E.media = D, q.insertBefore(E, o.nextSibling), E.styleSheet ? E.styleSheet.cssText = F : E.appendChild(j.createTextNode(F)), n.push(E);
        }
      }
    },
        v = function v(a, b, d) {
      var e = a.replace(c.regex.keyframes, "").match(c.regex.media),
          f = e && e.length || 0;
      b = b.substring(0, b.lastIndexOf("/"));

      var g = function g(a) {
        return a.replace(c.regex.urls, "$1" + b + "$2$3");
      },
          h = !f && d;

      b.length && (b += "/"), h && (f = 1);

      for (var i = 0; f > i; i++) {
        var j, k, n, o;
        h ? (j = d, m.push(g(a))) : (j = e[i].match(c.regex.findStyles) && RegExp.$1, m.push(RegExp.$2 && g(RegExp.$2))), n = j.split(","), o = n.length;

        for (var p = 0; o > p; p++) {
          k = n[p], l.push({
            media: k.split("(")[0].match(c.regex.only) && RegExp.$2 || "all",
            rules: m.length - 1,
            hasquery: k.indexOf("(") > -1,
            minw: k.match(c.regex.minw) && parseFloat(RegExp.$1) + (RegExp.$2 || ""),
            maxw: k.match(c.regex.maxw) && parseFloat(RegExp.$1) + (RegExp.$2 || "")
          });
        }
      }

      u();
    },
        w = function w() {
      if (d.length) {
        var b = d.shift();
        f(b.href, function (c) {
          v(c, b.href, b.media), o[b.href] = !0, a.setTimeout(function () {
            w();
          }, 0);
        });
      }
    },
        x = function x() {
      for (var b = 0; b < s.length; b++) {
        var c = s[b],
            e = c.href,
            f = c.media,
            g = c.rel && "stylesheet" === c.rel.toLowerCase();
        e && g && !o[e] && (c.styleSheet && c.styleSheet.rawCssText ? (v(c.styleSheet.rawCssText, e, f), o[e] = !0) : (!/^([a-zA-Z:]*\/\/)/.test(e) && !r || e.replace(RegExp.$1, "").split("/")[0] === a.location.host) && ("//" === e.substring(0, 2) && (e = a.location.protocol + e), d.push({
          href: e,
          media: f
        })));
      }

      w();
    };

    x(), c.update = x, c.getEmValue = t, a.addEventListener ? a.addEventListener("resize", b, !1) : a.attachEvent && a.attachEvent("onresize", b);
  }
}(this);

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2JhbHRpY3Jlc3QvYmFsdGljcmVzdC1pZTkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2JhbHRpY3Jlc3QvaHRtbDVzaGl2L2pzL2h0bWw1c2hpdi5taW4uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2JhbHRpY3Jlc3QvcmVzcG9uZC9qcy9yZXNwb25kLm1pbi5qcyJdLCJuYW1lcyI6WyJhIiwiYiIsImMiLCJjcmVhdGVFbGVtZW50IiwiZCIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwiZG9jdW1lbnRFbGVtZW50IiwiaW5uZXJIVE1MIiwiaW5zZXJ0QmVmb3JlIiwibGFzdENoaWxkIiwiZmlyc3RDaGlsZCIsInQiLCJlbGVtZW50cyIsInNwbGl0IiwiZSIsImpvaW4iLCJqIiwiZiIsInMiLCJxIiwiciIsImciLCJsIiwiY2FjaGUiLCJjbG9uZU5vZGUiLCJwIiwidGVzdCIsImNyZWF0ZUVsZW0iLCJjYW5IYXZlQ2hpbGRyZW4iLCJvIiwidGFnVXJuIiwiZnJhZyIsImFwcGVuZENoaWxkIiwiaCIsImNyZWF0ZURvY3VtZW50RnJhZ21lbnQiLCJpIiwibGVuZ3RoIiwiY3JlYXRlRnJhZyIsInNoaXZNZXRob2RzIiwiRnVuY3Rpb24iLCJyZXBsYWNlIiwic2hpdkNTUyIsImsiLCJoYXNDU1MiLCJtIiwibiIsImh0bWw1IiwiY2hpbGROb2RlcyIsInZlcnNpb24iLCJzdXBwb3J0c1Vua25vd25FbGVtZW50cyIsInR5cGUiLCJzaGl2RG9jdW1lbnQiLCJhZGRFbGVtZW50cyIsImRvY3VtZW50IiwibWF0Y2hNZWRpYSIsImZpcnN0RWxlbWVudENoaWxkIiwiaWQiLCJzdHlsZSIsImNzc1RleHQiLCJiYWNrZ3JvdW5kIiwib2Zmc2V0V2lkdGgiLCJyZW1vdmVDaGlsZCIsIm1hdGNoZXMiLCJtZWRpYSIsInUiLCJyZXNwb25kIiwidXBkYXRlIiwiWE1MSHR0cFJlcXVlc3QiLCJBY3RpdmVYT2JqZWN0Iiwib3BlbiIsIm9ucmVhZHlzdGF0ZWNoYW5nZSIsInJlYWR5U3RhdGUiLCJzdGF0dXMiLCJyZXNwb25zZVRleHQiLCJzZW5kIiwiYWpheCIsInF1ZXVlIiwicmVnZXgiLCJrZXlmcmFtZXMiLCJ1cmxzIiwiZmluZFN0eWxlcyIsIm9ubHkiLCJtaW53IiwibWF4dyIsIm1lZGlhUXVlcmllc1N1cHBvcnRlZCIsImJvZHkiLCJmb250U2l6ZSIsInBhcnNlRmxvYXQiLCJjb21wYXRNb2RlIiwiRGF0ZSIsImdldFRpbWUiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwidiIsImhhc093blByb3BlcnR5IiwidyIsIngiLCJ5IiwieiIsIkEiLCJCIiwiaW5kZXhPZiIsImhhc3F1ZXJ5IiwicHVzaCIsInJ1bGVzIiwiQyIsInBhcmVudE5vZGUiLCJEIiwiRSIsIkYiLCJuZXh0U2libGluZyIsInN0eWxlU2hlZXQiLCJjcmVhdGVUZXh0Tm9kZSIsIm1hdGNoIiwic3Vic3RyaW5nIiwibGFzdEluZGV4T2YiLCJSZWdFeHAiLCIkMSIsIiQyIiwic2hpZnQiLCJocmVmIiwicmVsIiwidG9Mb3dlckNhc2UiLCJyYXdDc3NUZXh0IiwibG9jYXRpb24iLCJob3N0IiwicHJvdG9jb2wiLCJnZXRFbVZhbHVlIiwiYWRkRXZlbnRMaXN0ZW5lciIsImF0dGFjaEV2ZW50Il0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNB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QUE4RSxPQUFDO0FBQUcsS0FBM2pGOztBQUE0akZDLEtBQUMsSUFBRzlGLENBQUMsQ0FBQ2dFLE1BQUYsR0FBUzhCLENBQVosRUFBYzlGLENBQUMsQ0FBQzZILFVBQUYsR0FBYXBILENBQTNCLEVBQTZCWCxDQUFDLENBQUNnSSxnQkFBRixHQUFtQmhJLENBQUMsQ0FBQ2dJLGdCQUFGLENBQW1CLFFBQW5CLEVBQTRCL0gsQ0FBNUIsRUFBOEIsQ0FBQyxDQUEvQixDQUFuQixHQUFxREQsQ0FBQyxDQUFDaUksV0FBRixJQUFlakksQ0FBQyxDQUFDaUksV0FBRixDQUFjLFVBQWQsRUFBeUJoSSxDQUF6QixDQUFsRztBQUE4SDtBQUFDLENBQTluSCxDQUErbkgsSUFBL25ILENBQXZlLEMiLCJmaWxlIjoiYmFsdGljcmVzdF9pZTkuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9idWlsZC9cIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9hc3NldHMvYmFsdGljcmVzdC9iYWx0aWNyZXN0LWllOS5qc1wiKTtcbiIsIlxyXG5pbXBvcnQgJy4vaHRtbDVzaGl2L2pzL2h0bWw1c2hpdi5taW4uanMnO1xyXG5pbXBvcnQgJy4vcmVzcG9uZC9qcy9yZXNwb25kLm1pbi5qcyc7XHJcbiIsIi8qKlxyXG4gKiBAcHJlc2VydmUgSFRNTDUgU2hpdiAzLjcuMiB8IEBhZmFya2FzIEBqZGFsdG9uIEBqb25fbmVhbCBAcmVtIHwgTUlUL0dQTDIgTGljZW5zZWRcclxuICovXHJcbiFmdW5jdGlvbihhLGIpe2Z1bmN0aW9uIGMoYSxiKXt2YXIgYz1hLmNyZWF0ZUVsZW1lbnQoXCJwXCIpLGQ9YS5nZXRFbGVtZW50c0J5VGFnTmFtZShcImhlYWRcIilbMF18fGEuZG9jdW1lbnRFbGVtZW50O3JldHVybiBjLmlubmVySFRNTD1cIng8c3R5bGU+XCIrYitcIjwvc3R5bGU+XCIsZC5pbnNlcnRCZWZvcmUoYy5sYXN0Q2hpbGQsZC5maXJzdENoaWxkKX1mdW5jdGlvbiBkKCl7dmFyIGE9dC5lbGVtZW50cztyZXR1cm5cInN0cmluZ1wiPT10eXBlb2YgYT9hLnNwbGl0KFwiIFwiKTphfWZ1bmN0aW9uIGUoYSxiKXt2YXIgYz10LmVsZW1lbnRzO1wic3RyaW5nXCIhPXR5cGVvZiBjJiYoYz1jLmpvaW4oXCIgXCIpKSxcInN0cmluZ1wiIT10eXBlb2YgYSYmKGE9YS5qb2luKFwiIFwiKSksdC5lbGVtZW50cz1jK1wiIFwiK2EsaihiKX1mdW5jdGlvbiBmKGEpe3ZhciBiPXNbYVtxXV07cmV0dXJuIGJ8fChiPXt9LHIrKyxhW3FdPXIsc1tyXT1iKSxifWZ1bmN0aW9uIGcoYSxjLGQpe2lmKGN8fChjPWIpLGwpcmV0dXJuIGMuY3JlYXRlRWxlbWVudChhKTtkfHwoZD1mKGMpKTt2YXIgZTtyZXR1cm4gZT1kLmNhY2hlW2FdP2QuY2FjaGVbYV0uY2xvbmVOb2RlKCk6cC50ZXN0KGEpPyhkLmNhY2hlW2FdPWQuY3JlYXRlRWxlbShhKSkuY2xvbmVOb2RlKCk6ZC5jcmVhdGVFbGVtKGEpLCFlLmNhbkhhdmVDaGlsZHJlbnx8by50ZXN0KGEpfHxlLnRhZ1Vybj9lOmQuZnJhZy5hcHBlbmRDaGlsZChlKX1mdW5jdGlvbiBoKGEsYyl7aWYoYXx8KGE9YiksbClyZXR1cm4gYS5jcmVhdGVEb2N1bWVudEZyYWdtZW50KCk7Yz1jfHxmKGEpO2Zvcih2YXIgZT1jLmZyYWcuY2xvbmVOb2RlKCksZz0wLGg9ZCgpLGk9aC5sZW5ndGg7aT5nO2crKyllLmNyZWF0ZUVsZW1lbnQoaFtnXSk7cmV0dXJuIGV9ZnVuY3Rpb24gaShhLGIpe2IuY2FjaGV8fChiLmNhY2hlPXt9LGIuY3JlYXRlRWxlbT1hLmNyZWF0ZUVsZW1lbnQsYi5jcmVhdGVGcmFnPWEuY3JlYXRlRG9jdW1lbnRGcmFnbWVudCxiLmZyYWc9Yi5jcmVhdGVGcmFnKCkpLGEuY3JlYXRlRWxlbWVudD1mdW5jdGlvbihjKXtyZXR1cm4gdC5zaGl2TWV0aG9kcz9nKGMsYSxiKTpiLmNyZWF0ZUVsZW0oYyl9LGEuY3JlYXRlRG9jdW1lbnRGcmFnbWVudD1GdW5jdGlvbihcImgsZlwiLFwicmV0dXJuIGZ1bmN0aW9uKCl7dmFyIG49Zi5jbG9uZU5vZGUoKSxjPW4uY3JlYXRlRWxlbWVudDtoLnNoaXZNZXRob2RzJiYoXCIrZCgpLmpvaW4oKS5yZXBsYWNlKC9bXFx3XFwtOl0rL2csZnVuY3Rpb24oYSl7cmV0dXJuIGIuY3JlYXRlRWxlbShhKSxiLmZyYWcuY3JlYXRlRWxlbWVudChhKSwnYyhcIicrYSsnXCIpJ30pK1wiKTtyZXR1cm4gbn1cIikodCxiLmZyYWcpfWZ1bmN0aW9uIGooYSl7YXx8KGE9Yik7dmFyIGQ9ZihhKTtyZXR1cm4hdC5zaGl2Q1NTfHxrfHxkLmhhc0NTU3x8KGQuaGFzQ1NTPSEhYyhhLFwiYXJ0aWNsZSxhc2lkZSxkaWFsb2csZmlnY2FwdGlvbixmaWd1cmUsZm9vdGVyLGhlYWRlcixoZ3JvdXAsbWFpbixuYXYsc2VjdGlvbntkaXNwbGF5OmJsb2NrfW1hcmt7YmFja2dyb3VuZDojRkYwO2NvbG9yOiMwMDB9dGVtcGxhdGV7ZGlzcGxheTpub25lfVwiKSksbHx8aShhLGQpLGF9dmFyIGssbCxtPVwiMy43LjJcIixuPWEuaHRtbDV8fHt9LG89L148fF4oPzpidXR0b258bWFwfHNlbGVjdHx0ZXh0YXJlYXxvYmplY3R8aWZyYW1lfG9wdGlvbnxvcHRncm91cCkkL2kscD0vXig/OmF8Ynxjb2RlfGRpdnxmaWVsZHNldHxoMXxoMnxoM3xoNHxoNXxoNnxpfGxhYmVsfGxpfG9sfHB8cXxzcGFufHN0cm9uZ3xzdHlsZXx0YWJsZXx0Ym9keXx0ZHx0aHx0cnx1bCkkL2kscT1cIl9odG1sNXNoaXZcIixyPTAscz17fTshZnVuY3Rpb24oKXt0cnl7dmFyIGE9Yi5jcmVhdGVFbGVtZW50KFwiYVwiKTthLmlubmVySFRNTD1cIjx4eXo+PC94eXo+XCIsaz1cImhpZGRlblwiaW4gYSxsPTE9PWEuY2hpbGROb2Rlcy5sZW5ndGh8fGZ1bmN0aW9uKCl7Yi5jcmVhdGVFbGVtZW50KFwiYVwiKTt2YXIgYT1iLmNyZWF0ZURvY3VtZW50RnJhZ21lbnQoKTtyZXR1cm5cInVuZGVmaW5lZFwiPT10eXBlb2YgYS5jbG9uZU5vZGV8fFwidW5kZWZpbmVkXCI9PXR5cGVvZiBhLmNyZWF0ZURvY3VtZW50RnJhZ21lbnR8fFwidW5kZWZpbmVkXCI9PXR5cGVvZiBhLmNyZWF0ZUVsZW1lbnR9KCl9Y2F0Y2goYyl7az0hMCxsPSEwfX0oKTt2YXIgdD17ZWxlbWVudHM6bi5lbGVtZW50c3x8XCJhYmJyIGFydGljbGUgYXNpZGUgYXVkaW8gYmRpIGNhbnZhcyBkYXRhIGRhdGFsaXN0IGRldGFpbHMgZGlhbG9nIGZpZ2NhcHRpb24gZmlndXJlIGZvb3RlciBoZWFkZXIgaGdyb3VwIG1haW4gbWFyayBtZXRlciBuYXYgb3V0cHV0IHBpY3R1cmUgcHJvZ3Jlc3Mgc2VjdGlvbiBzdW1tYXJ5IHRlbXBsYXRlIHRpbWUgdmlkZW9cIix2ZXJzaW9uOm0sc2hpdkNTUzpuLnNoaXZDU1MhPT0hMSxzdXBwb3J0c1Vua25vd25FbGVtZW50czpsLHNoaXZNZXRob2RzOm4uc2hpdk1ldGhvZHMhPT0hMSx0eXBlOlwiZGVmYXVsdFwiLHNoaXZEb2N1bWVudDpqLGNyZWF0ZUVsZW1lbnQ6ZyxjcmVhdGVEb2N1bWVudEZyYWdtZW50OmgsYWRkRWxlbWVudHM6ZX07YS5odG1sNT10LGooYil9KHRoaXMsZG9jdW1lbnQpOyIsIi8qISBSZXNwb25kLmpzIHYxLjQuMjogbWluL21heC13aWR0aCBtZWRpYSBxdWVyeSBwb2x5ZmlsbCAqIENvcHlyaWdodCAyMDEzIFNjb3R0IEplaGxcclxuICogTGljZW5zZWQgdW5kZXIgaHR0cHM6Ly9naXRodWIuY29tL3Njb3R0amVobC9SZXNwb25kL2Jsb2IvbWFzdGVyL0xJQ0VOU0UtTUlUXHJcbiAqICAqL1xyXG5cclxuIWZ1bmN0aW9uKGEpe1widXNlIHN0cmljdFwiO2EubWF0Y2hNZWRpYT1hLm1hdGNoTWVkaWF8fGZ1bmN0aW9uKGEpe3ZhciBiLGM9YS5kb2N1bWVudEVsZW1lbnQsZD1jLmZpcnN0RWxlbWVudENoaWxkfHxjLmZpcnN0Q2hpbGQsZT1hLmNyZWF0ZUVsZW1lbnQoXCJib2R5XCIpLGY9YS5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO3JldHVybiBmLmlkPVwibXEtdGVzdC0xXCIsZi5zdHlsZS5jc3NUZXh0PVwicG9zaXRpb246YWJzb2x1dGU7dG9wOi0xMDBlbVwiLGUuc3R5bGUuYmFja2dyb3VuZD1cIm5vbmVcIixlLmFwcGVuZENoaWxkKGYpLGZ1bmN0aW9uKGEpe3JldHVybiBmLmlubmVySFRNTD0nJnNoeTs8c3R5bGUgbWVkaWE9XCInK2ErJ1wiPiAjbXEtdGVzdC0xIHsgd2lkdGg6IDQycHg7IH08L3N0eWxlPicsYy5pbnNlcnRCZWZvcmUoZSxkKSxiPTQyPT09Zi5vZmZzZXRXaWR0aCxjLnJlbW92ZUNoaWxkKGUpLHttYXRjaGVzOmIsbWVkaWE6YX19fShhLmRvY3VtZW50KX0odGhpcyksZnVuY3Rpb24oYSl7XCJ1c2Ugc3RyaWN0XCI7ZnVuY3Rpb24gYigpe3UoITApfXZhciBjPXt9O2EucmVzcG9uZD1jLGMudXBkYXRlPWZ1bmN0aW9uKCl7fTt2YXIgZD1bXSxlPWZ1bmN0aW9uKCl7dmFyIGI9ITE7dHJ5e2I9bmV3IGEuWE1MSHR0cFJlcXVlc3R9Y2F0Y2goYyl7Yj1uZXcgYS5BY3RpdmVYT2JqZWN0KFwiTWljcm9zb2Z0LlhNTEhUVFBcIil9cmV0dXJuIGZ1bmN0aW9uKCl7cmV0dXJuIGJ9fSgpLGY9ZnVuY3Rpb24oYSxiKXt2YXIgYz1lKCk7YyYmKGMub3BlbihcIkdFVFwiLGEsITApLGMub25yZWFkeXN0YXRlY2hhbmdlPWZ1bmN0aW9uKCl7NCE9PWMucmVhZHlTdGF0ZXx8MjAwIT09Yy5zdGF0dXMmJjMwNCE9PWMuc3RhdHVzfHxiKGMucmVzcG9uc2VUZXh0KX0sNCE9PWMucmVhZHlTdGF0ZSYmYy5zZW5kKG51bGwpKX07aWYoYy5hamF4PWYsYy5xdWV1ZT1kLGMucmVnZXg9e21lZGlhOi9AbWVkaWFbXlxce10rXFx7KFteXFx7XFx9XSpcXHtbXlxcfVxce10qXFx9KSsvZ2ksa2V5ZnJhbWVzOi9AKD86XFwtKD86b3xtb3p8d2Via2l0KVxcLSk/a2V5ZnJhbWVzW15cXHtdK1xceyg/OlteXFx7XFx9XSpcXHtbXlxcfVxce10qXFx9KStbXlxcfV0qXFx9L2dpLHVybHM6Lyh1cmxcXCgpWydcIl0/KFteXFwvXFwpJ1wiXVteOlxcKSdcIl0rKVsnXCJdPyhcXCkpL2csZmluZFN0eWxlczovQG1lZGlhICooW15cXHtdKylcXHsoW1xcU1xcc10rPykkLyxvbmx5Oi8ob25seVxccyspPyhbYS16QS1aXSspXFxzPy8sbWludzovXFwoW1xcc10qbWluXFwtd2lkdGhcXHMqOltcXHNdKihbXFxzXSpbMC05XFwuXSspKHB4fGVtKVtcXHNdKlxcKS8sbWF4dzovXFwoW1xcc10qbWF4XFwtd2lkdGhcXHMqOltcXHNdKihbXFxzXSpbMC05XFwuXSspKHB4fGVtKVtcXHNdKlxcKS99LGMubWVkaWFRdWVyaWVzU3VwcG9ydGVkPWEubWF0Y2hNZWRpYSYmbnVsbCE9PWEubWF0Y2hNZWRpYShcIm9ubHkgYWxsXCIpJiZhLm1hdGNoTWVkaWEoXCJvbmx5IGFsbFwiKS5tYXRjaGVzLCFjLm1lZGlhUXVlcmllc1N1cHBvcnRlZCl7dmFyIGcsaCxpLGo9YS5kb2N1bWVudCxrPWouZG9jdW1lbnRFbGVtZW50LGw9W10sbT1bXSxuPVtdLG89e30scD0zMCxxPWouZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJoZWFkXCIpWzBdfHxrLHI9ai5nZXRFbGVtZW50c0J5VGFnTmFtZShcImJhc2VcIilbMF0scz1xLmdldEVsZW1lbnRzQnlUYWdOYW1lKFwibGlua1wiKSx0PWZ1bmN0aW9uKCl7dmFyIGEsYj1qLmNyZWF0ZUVsZW1lbnQoXCJkaXZcIiksYz1qLmJvZHksZD1rLnN0eWxlLmZvbnRTaXplLGU9YyYmYy5zdHlsZS5mb250U2l6ZSxmPSExO3JldHVybiBiLnN0eWxlLmNzc1RleHQ9XCJwb3NpdGlvbjphYnNvbHV0ZTtmb250LXNpemU6MWVtO3dpZHRoOjFlbVwiLGN8fChjPWY9ai5jcmVhdGVFbGVtZW50KFwiYm9keVwiKSxjLnN0eWxlLmJhY2tncm91bmQ9XCJub25lXCIpLGsuc3R5bGUuZm9udFNpemU9XCIxMDAlXCIsYy5zdHlsZS5mb250U2l6ZT1cIjEwMCVcIixjLmFwcGVuZENoaWxkKGIpLGYmJmsuaW5zZXJ0QmVmb3JlKGMsay5maXJzdENoaWxkKSxhPWIub2Zmc2V0V2lkdGgsZj9rLnJlbW92ZUNoaWxkKGMpOmMucmVtb3ZlQ2hpbGQoYiksay5zdHlsZS5mb250U2l6ZT1kLGUmJihjLnN0eWxlLmZvbnRTaXplPWUpLGE9aT1wYXJzZUZsb2F0KGEpfSx1PWZ1bmN0aW9uKGIpe3ZhciBjPVwiY2xpZW50V2lkdGhcIixkPWtbY10sZT1cIkNTUzFDb21wYXRcIj09PWouY29tcGF0TW9kZSYmZHx8ai5ib2R5W2NdfHxkLGY9e30sbz1zW3MubGVuZ3RoLTFdLHI9KG5ldyBEYXRlKS5nZXRUaW1lKCk7aWYoYiYmZyYmcD5yLWcpcmV0dXJuIGEuY2xlYXJUaW1lb3V0KGgpLGg9YS5zZXRUaW1lb3V0KHUscCksdm9pZCAwO2c9cjtmb3IodmFyIHYgaW4gbClpZihsLmhhc093blByb3BlcnR5KHYpKXt2YXIgdz1sW3ZdLHg9dy5taW53LHk9dy5tYXh3LHo9bnVsbD09PXgsQT1udWxsPT09eSxCPVwiZW1cIjt4JiYoeD1wYXJzZUZsb2F0KHgpKih4LmluZGV4T2YoQik+LTE/aXx8dCgpOjEpKSx5JiYoeT1wYXJzZUZsb2F0KHkpKih5LmluZGV4T2YoQik+LTE/aXx8dCgpOjEpKSx3Lmhhc3F1ZXJ5JiYoeiYmQXx8ISh6fHxlPj14KXx8IShBfHx5Pj1lKSl8fChmW3cubWVkaWFdfHwoZlt3Lm1lZGlhXT1bXSksZlt3Lm1lZGlhXS5wdXNoKG1bdy5ydWxlc10pKX1mb3IodmFyIEMgaW4gbiluLmhhc093blByb3BlcnR5KEMpJiZuW0NdJiZuW0NdLnBhcmVudE5vZGU9PT1xJiZxLnJlbW92ZUNoaWxkKG5bQ10pO24ubGVuZ3RoPTA7Zm9yKHZhciBEIGluIGYpaWYoZi5oYXNPd25Qcm9wZXJ0eShEKSl7dmFyIEU9ai5jcmVhdGVFbGVtZW50KFwic3R5bGVcIiksRj1mW0RdLmpvaW4oXCJcXG5cIik7RS50eXBlPVwidGV4dC9jc3NcIixFLm1lZGlhPUQscS5pbnNlcnRCZWZvcmUoRSxvLm5leHRTaWJsaW5nKSxFLnN0eWxlU2hlZXQ/RS5zdHlsZVNoZWV0LmNzc1RleHQ9RjpFLmFwcGVuZENoaWxkKGouY3JlYXRlVGV4dE5vZGUoRikpLG4ucHVzaChFKX19LHY9ZnVuY3Rpb24oYSxiLGQpe3ZhciBlPWEucmVwbGFjZShjLnJlZ2V4LmtleWZyYW1lcyxcIlwiKS5tYXRjaChjLnJlZ2V4Lm1lZGlhKSxmPWUmJmUubGVuZ3RofHwwO2I9Yi5zdWJzdHJpbmcoMCxiLmxhc3RJbmRleE9mKFwiL1wiKSk7dmFyIGc9ZnVuY3Rpb24oYSl7cmV0dXJuIGEucmVwbGFjZShjLnJlZ2V4LnVybHMsXCIkMVwiK2IrXCIkMiQzXCIpfSxoPSFmJiZkO2IubGVuZ3RoJiYoYis9XCIvXCIpLGgmJihmPTEpO2Zvcih2YXIgaT0wO2Y+aTtpKyspe3ZhciBqLGssbixvO2g/KGo9ZCxtLnB1c2goZyhhKSkpOihqPWVbaV0ubWF0Y2goYy5yZWdleC5maW5kU3R5bGVzKSYmUmVnRXhwLiQxLG0ucHVzaChSZWdFeHAuJDImJmcoUmVnRXhwLiQyKSkpLG49ai5zcGxpdChcIixcIiksbz1uLmxlbmd0aDtmb3IodmFyIHA9MDtvPnA7cCsrKWs9bltwXSxsLnB1c2goe21lZGlhOmsuc3BsaXQoXCIoXCIpWzBdLm1hdGNoKGMucmVnZXgub25seSkmJlJlZ0V4cC4kMnx8XCJhbGxcIixydWxlczptLmxlbmd0aC0xLGhhc3F1ZXJ5OmsuaW5kZXhPZihcIihcIik+LTEsbWludzprLm1hdGNoKGMucmVnZXgubWludykmJnBhcnNlRmxvYXQoUmVnRXhwLiQxKSsoUmVnRXhwLiQyfHxcIlwiKSxtYXh3OmsubWF0Y2goYy5yZWdleC5tYXh3KSYmcGFyc2VGbG9hdChSZWdFeHAuJDEpKyhSZWdFeHAuJDJ8fFwiXCIpfSl9dSgpfSx3PWZ1bmN0aW9uKCl7aWYoZC5sZW5ndGgpe3ZhciBiPWQuc2hpZnQoKTtmKGIuaHJlZixmdW5jdGlvbihjKXt2KGMsYi5ocmVmLGIubWVkaWEpLG9bYi5ocmVmXT0hMCxhLnNldFRpbWVvdXQoZnVuY3Rpb24oKXt3KCl9LDApfSl9fSx4PWZ1bmN0aW9uKCl7Zm9yKHZhciBiPTA7YjxzLmxlbmd0aDtiKyspe3ZhciBjPXNbYl0sZT1jLmhyZWYsZj1jLm1lZGlhLGc9Yy5yZWwmJlwic3R5bGVzaGVldFwiPT09Yy5yZWwudG9Mb3dlckNhc2UoKTtlJiZnJiYhb1tlXSYmKGMuc3R5bGVTaGVldCYmYy5zdHlsZVNoZWV0LnJhd0Nzc1RleHQ/KHYoYy5zdHlsZVNoZWV0LnJhd0Nzc1RleHQsZSxmKSxvW2VdPSEwKTooIS9eKFthLXpBLVo6XSpcXC9cXC8pLy50ZXN0KGUpJiYhcnx8ZS5yZXBsYWNlKFJlZ0V4cC4kMSxcIlwiKS5zcGxpdChcIi9cIilbMF09PT1hLmxvY2F0aW9uLmhvc3QpJiYoXCIvL1wiPT09ZS5zdWJzdHJpbmcoMCwyKSYmKGU9YS5sb2NhdGlvbi5wcm90b2NvbCtlKSxkLnB1c2goe2hyZWY6ZSxtZWRpYTpmfSkpKX13KCl9O3goKSxjLnVwZGF0ZT14LGMuZ2V0RW1WYWx1ZT10LGEuYWRkRXZlbnRMaXN0ZW5lcj9hLmFkZEV2ZW50TGlzdGVuZXIoXCJyZXNpemVcIixiLCExKTphLmF0dGFjaEV2ZW50JiZhLmF0dGFjaEV2ZW50KFwib25yZXNpemVcIixiKX19KHRoaXMpOyJdLCJzb3VyY2VSb290IjoiIn0=