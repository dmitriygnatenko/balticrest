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
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/balticrest/balticrest/js/map_index.js":
/*!******************************************************!*\
  !*** ./assets/balticrest/balticrest/js/map_index.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.initialize = function initialize() {
  var activePoint;
  ymaps.ready(init);

  function init() {
    var map = new ymaps.Map("map", {
      center: [map_data.center.lat, map_data.center.lon],
      zoom: map_data.zoom,
      controls: getControls()
    });
    map.events.add('click', function (e) {
      if (activePoint !== undefined) {
        activePoint.onCloseClick(e);
      }
    });
    map_data.points.forEach(function (item) {
      var point = new ymaps.Placemark([item.lat, item.lon], {
        hintContent: item.hint
      }, {
        balloonShadow: false,
        balloonPanelMaxMapArea: 0,
        balloonContentLayout: getBalloonContent(item),
        balloonLayout: getBalloonLayout(),
        iconLayout: 'default#image',
        iconImageHref: item.icon.image,
        iconImageSize: [item.icon.size.x, item.icon.size.y]
      });
      map.geoObjects.add(point);
    });
  }

  function getBalloonContent(item) {
    return ymaps.templateLayoutFactory.createClass('<img src="' + item.image + '" class="logotype">' + '<h3>' + item.title + '<span></span></h3>' + '<span>' + item.description + '</span>' + '<a href="' + item.link + '" class="green_btn">' + map_data.trans.point_button + '</a>');
  }

  function getBalloonLayout() {
    return ymaps.templateLayoutFactory.createClass('<div class="marker_info">' + '<div class="info">' + '<button type="button" class="close"><span>&times;</span></button>' + '$[[options.contentLayout]]' + '<span class="arrow"></span>' + '</div>' + '</div>', {
      build: function build() {
        activePoint = this;
        this.constructor.superclass.build.call(this);
        this._$element = $('.marker_info .info', this.getParentElement());
        this.applyElementOffset();

        this._$element.find('.close').on('click', $.proxy(this.onCloseClick, this));
      },
      onCloseClick: function onCloseClick(e) {
        activePoint = undefined;
        e.preventDefault();
        this.events.fire('userclose');
      },
      applyElementOffset: function applyElementOffset() {
        this._$element.css({
          top: -(this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight)
        });
      },
      getShape: function getShape() {
        var position = this._$element.position();

        return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([[position.left, position.top], [position.left + this._$element[0].offsetWidth, position.top - this._$element[0].offsetHeight]]));
      }
    });
  }

  function getControls() {
    var typeSelector = new ymaps.control.TypeSelector({
      options: {
        "float": 'none',
        position: {
          top: '15px',
          left: '12px'
        }
      }
    });
    var zoomControl = new ymaps.control.ZoomControl({
      options: {
        "float": 'none',
        position: {
          top: '57px',
          left: '12px'
        }
      }
    });
    var geolocationControl = new ymaps.control.GeolocationControl({
      options: {
        "float": 'none',
        position: {
          top: '275px',
          left: '12px'
        }
      }
    });
    var rulerControl = new ymaps.control.RulerControl({
      options: {
        "float": 'none',
        position: {
          top: '315px',
          left: '12px'
        },
        scaleLine: false
      }
    });
    return [geolocationControl, typeSelector, rulerControl, zoomControl];
  }
};

/***/ }),

/***/ 10:
/*!************************************************************!*\
  !*** multi ./assets/balticrest/balticrest/js/map_index.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/balticrest/balticrest/js/map_index.js */"./assets/balticrest/balticrest/js/map_index.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2JhbHRpY3Jlc3QvYmFsdGljcmVzdC9qcy9tYXBfaW5kZXguanMiXSwibmFtZXMiOlsid2luZG93IiwiaW5pdGlhbGl6ZSIsImFjdGl2ZVBvaW50IiwieW1hcHMiLCJyZWFkeSIsImluaXQiLCJtYXAiLCJNYXAiLCJjZW50ZXIiLCJtYXBfZGF0YSIsImxhdCIsImxvbiIsInpvb20iLCJjb250cm9scyIsImdldENvbnRyb2xzIiwiZXZlbnRzIiwiYWRkIiwiZSIsInVuZGVmaW5lZCIsIm9uQ2xvc2VDbGljayIsInBvaW50cyIsImZvckVhY2giLCJpdGVtIiwicG9pbnQiLCJQbGFjZW1hcmsiLCJoaW50Q29udGVudCIsImhpbnQiLCJiYWxsb29uU2hhZG93IiwiYmFsbG9vblBhbmVsTWF4TWFwQXJlYSIsImJhbGxvb25Db250ZW50TGF5b3V0IiwiZ2V0QmFsbG9vbkNvbnRlbnQiLCJiYWxsb29uTGF5b3V0IiwiZ2V0QmFsbG9vbkxheW91dCIsImljb25MYXlvdXQiLCJpY29uSW1hZ2VIcmVmIiwiaWNvbiIsImltYWdlIiwiaWNvbkltYWdlU2l6ZSIsInNpemUiLCJ4IiwieSIsImdlb09iamVjdHMiLCJ0ZW1wbGF0ZUxheW91dEZhY3RvcnkiLCJjcmVhdGVDbGFzcyIsInRpdGxlIiwiZGVzY3JpcHRpb24iLCJsaW5rIiwidHJhbnMiLCJwb2ludF9idXR0b24iLCJidWlsZCIsImNvbnN0cnVjdG9yIiwic3VwZXJjbGFzcyIsImNhbGwiLCJfJGVsZW1lbnQiLCIkIiwiZ2V0UGFyZW50RWxlbWVudCIsImFwcGx5RWxlbWVudE9mZnNldCIsImZpbmQiLCJvbiIsInByb3h5IiwicHJldmVudERlZmF1bHQiLCJmaXJlIiwiY3NzIiwidG9wIiwib2Zmc2V0SGVpZ2h0IiwiZ2V0U2hhcGUiLCJwb3NpdGlvbiIsInNoYXBlIiwiUmVjdGFuZ2xlIiwiZ2VvbWV0cnkiLCJwaXhlbCIsImxlZnQiLCJvZmZzZXRXaWR0aCIsInR5cGVTZWxlY3RvciIsImNvbnRyb2wiLCJUeXBlU2VsZWN0b3IiLCJvcHRpb25zIiwiem9vbUNvbnRyb2wiLCJab29tQ29udHJvbCIsImdlb2xvY2F0aW9uQ29udHJvbCIsIkdlb2xvY2F0aW9uQ29udHJvbCIsInJ1bGVyQ29udHJvbCIsIlJ1bGVyQ29udHJvbCIsInNjYWxlTGluZSJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBOzs7UUFHQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMENBQTBDLGdDQUFnQztRQUMxRTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLHdEQUF3RCxrQkFBa0I7UUFDMUU7UUFDQSxpREFBaUQsY0FBYztRQUMvRDs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0EseUNBQXlDLGlDQUFpQztRQUMxRSxnSEFBZ0gsbUJBQW1CLEVBQUU7UUFDckk7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwyQkFBMkIsMEJBQTBCLEVBQUU7UUFDdkQsaUNBQWlDLGVBQWU7UUFDaEQ7UUFDQTtRQUNBOztRQUVBO1FBQ0Esc0RBQXNELCtEQUErRDs7UUFFckg7UUFDQTs7O1FBR0E7UUFDQTs7Ozs7Ozs7Ozs7O0FDakZBQSxNQUFNLENBQUNDLFVBQVAsR0FBb0IsU0FBU0EsVUFBVCxHQUF1QjtBQUUxQyxNQUFJQyxXQUFKO0FBRUFDLE9BQUssQ0FBQ0MsS0FBTixDQUFZQyxJQUFaOztBQUVBLFdBQVNBLElBQVQsR0FBZ0I7QUFFZixRQUFNQyxHQUFHLEdBQUcsSUFBSUgsS0FBSyxDQUFDSSxHQUFWLENBQWMsS0FBZCxFQUFxQjtBQUNoQ0MsWUFBTSxFQUFFLENBQUNDLFFBQVEsQ0FBQ0QsTUFB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LGFBQU8sRUFBRTtBQUNSLGlCQUFPLE1BREM7QUFFUlYsZ0JBQVEsRUFBRTtBQUNUSCxhQUFHLEVBQUUsTUFESTtBQUVUUSxjQUFJLEVBQUU7QUFGRztBQUZGO0FBRHdDLEtBQTlCLENBQXBCO0FBVUEsUUFBTVEsa0JBQWtCLEdBQUcsSUFBSTVFLEtBQUssQ0FBQ3VFLE9BQU4sQ0FBY00sa0JBQWxCLENBQXFDO0FBQy9ESixhQUFPLEVBQUU7QUFDUixpQkFBTyxNQURDO0FBRVJWLGdCQUFRLEVBQUU7QUFDVEgsYUFBRyxFQUFFLE9BREk7QUFFVFEsY0FBSSxFQUFFO0FBRkc7QUFGRjtBQURzRCxLQUFyQyxDQUEzQjtBQVVBLFFBQU1VLFlBQVksR0FBRyxJQUFJOUUsS0FBSyxDQUFDdUUsT0FBTixDQUFjUSxZQUFsQixDQUErQjtBQUNuRE4sYUFBTyxFQUFFO0FBQ1IsaUJBQU8sTUFEQztBQUVSVixnQkFBUSxFQUFFO0FBQ1RILGFBQUcsRUFBRSxPQURJO0FBRVRRLGNBQUksRUFBRTtBQUZHLFNBRkY7QUFNUlksaUJBQVMsRUFBRTtBQU5IO0FBRDBDLEtBQS9CLENBQXJCO0FBV0EsV0FBTyxDQUFDSixrQkFBRCxFQUFxQk4sWUFBckIsRUFBbUNRLFlBQW5DLEVBQWlESixXQUFqRCxDQUFQO0FBQ0E7QUFFRCxDQXBJRCxDIiwiZmlsZSI6ImJhbHRpY3Jlc3RfanNfbWFwX2luZGV4LmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCIvYnVpbGQvXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAxMCk7XG4iLCJcbndpbmRvdy5pbml0aWFsaXplID0gZnVuY3Rpb24gaW5pdGlhbGl6ZSAoKSB7XG5cblx0bGV0IGFjdGl2ZVBvaW50O1xuXG5cdHltYXBzLnJlYWR5KGluaXQpO1xuXG5cdGZ1bmN0aW9uIGluaXQoKSB7XG5cblx0XHRjb25zdCBtYXAgPSBuZXcgeW1hcHMuTWFwKFwibWFwXCIsIHtcblx0XHRcdGNlbnRlcjogW21hcF9kYXRhLmNlbnRlci5sYXQsIG1hcF9kYXRhLmNlbnRlci5sb25dLFxuXHRcdFx0em9vbTogbWFwX2RhdGEuem9vbSxcblx0XHRcdGNvbnRyb2xzOiBnZXRDb250cm9scygpXG5cdFx0fSk7XG5cblx0XHRtYXAuZXZlbnRzLmFkZCgnY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuXHRcdFx0aWYgKGFjdGl2ZVBvaW50ICE9PSB1bmRlZmluZWQpIHtcblx0XHRcdFx0YWN0aXZlUG9pbnQub25DbG9zZUNsaWNrKGUpO1xuXHRcdFx0fVxuXHRcdH0pO1xuXG5cdFx0bWFwX2RhdGEucG9pbnRzLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcblx0XHRcdGxldCBwb2ludCA9IG5ldyB5bWFwcy5QbGFjZW1hcmsoW2l0ZW0ubGF0LCBpdGVtLmxvbl0sIHtcblx0XHRcdFx0aGludENvbnRlbnQ6IGl0ZW0uaGludCxcblx0XHRcdH0sIHtcblx0XHRcdFx0YmFsbG9vblNoYWRvdzogZmFsc2UsXG5cdFx0XHRcdGJhbGxvb25QYW5lbE1heE1hcEFyZWE6IDAsXG4gXHRcdFx0XHRiYWxsb29uQ29udGVudExheW91dDogZ2V0QmFsbG9vbkNvbnRlbnQoaXRlbSksXG5cdFx0XHRcdGJhbGxvb25MYXlvdXQ6IGdldEJhbGxvb25MYXlvdXQoKSxcblx0XHRcdFx0aWNvbkxheW91dDogJ2RlZmF1bHQjaW1hZ2UnLFxuXHRcdFx0XHRpY29uSW1hZ2VIcmVmOiBpdGVtLmljb24uaW1hZ2UsXG5cdFx0XHRcdGljb25JbWFnZVNpemU6IFtpdGVtLmljb24uc2l6ZS54LCBpdGVtLmljb24uc2l6ZS55XSxcblx0XHRcdH0pO1xuXG5cdFx0XHRtYXAuZ2VvT2JqZWN0cy5hZGQocG9pbnQpO1xuXHRcdH0pO1xuXHR9XG5cblx0ZnVuY3Rpb24gZ2V0QmFsbG9vbkNvbnRlbnQoaXRlbSkge1xuXHRcdHJldHVybiB5bWFwcy50ZW1wbGF0ZUxheW91dEZhY3RvcnkuY3JlYXRlQ2xhc3MoXG5cdFx0XHQnPGltZyBzcmM9XCInICsgaXRlbS5pbWFnZSArICdcIiBjbGFzcz1cImxvZ290eXBlXCI+JyArXG5cdFx0XHQnPGgzPicrIGl0ZW0udGl0bGUgKyc8c3Bhbj48L3NwYW4+PC9oMz4nICtcblx0XHRcdCc8c3Bhbj4nKyBpdGVtLmRlc2NyaXB0aW9uICsnPC9zcGFuPicgK1xuXHRcdFx0JzxhIGhyZWY9XCInKyBpdGVtLmxpbmsgKyAnXCIgY2xhc3M9XCJncmVlbl9idG5cIj4nICsgbWFwX2RhdGEudHJhbnMucG9pbnRfYnV0dG9uICsgJzwvYT4nKTtcblx0fVxuXG5cdGZ1bmN0aW9uIGdldEJhbGxvb25MYXlvdXQoKSB7XG5cdFx0cmV0dXJuIHltYXBzLnRlbXBsYXRlTGF5b3V0RmFjdG9yeS5jcmVhdGVDbGFzcyhcblx0XHRcdCc8ZGl2IGNsYXNzPVwibWFya2VyX2luZm9cIj4nICtcblx0XHRcdFx0JzxkaXYgY2xhc3M9XCJpbmZvXCI+Jytcblx0XHRcdFx0XHQnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJjbG9zZVwiPjxzcGFuPiZ0aW1lczs8L3NwYW4+PC9idXR0b24+JyArXG5cdFx0XHRcdFx0JyRbW29wdGlvbnMuY29udGVudExheW91dF1dJyArXG5cdFx0XHRcdFx0JzxzcGFuIGNsYXNzPVwiYXJyb3dcIj48L3NwYW4+JyArXG5cdFx0XHRcdCc8L2Rpdj4nICtcblx0XHRcdCc8L2Rpdj4nLFxuXHRcdFx0e1xuXHRcdFx0XHRidWlsZDogZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdGFjdGl2ZVBvaW50ID0gdGhpcztcblx0XHRcdFx0XHR0aGlzLmNvbnN0cnVjdG9yLnN1cGVyY2xhc3MuYnVpbGQuY2FsbCh0aGlzKTtcblx0XHRcdFx0XHR0aGlzLl8kZWxlbWVudCA9ICQoJy5tYXJrZXJfaW5mbyAuaW5mbycsIHRoaXMuZ2V0UGFyZW50RWxlbWVudCgpKTtcblx0XHRcdFx0XHR0aGlzLmFwcGx5RWxlbWVudE9mZnNldCgpO1xuXHRcdFx0XHRcdHRoaXMuXyRlbGVtZW50LmZpbmQoJy5jbG9zZScpLm9uKCdjbGljaycsICQucHJveHkodGhpcy5vbkNsb3NlQ2xpY2ssIHRoaXMpKTtcblx0XHRcdFx0fSxcblx0XHRcdFx0b25DbG9zZUNsaWNrOiBmdW5jdGlvbiAoZSkge1xuXHRcdFx0XHRcdGFjdGl2ZVBvaW50ID0gdW5kZWZpbmVkO1xuXHRcdFx0XHRcdGUucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHR0aGlzLmV2ZW50cy5maXJlKCd1c2VyY2xvc2UnKTtcblx0XHRcdFx0fSxcblx0XHRcdFx0YXBwbHlFbGVtZW50T2Zmc2V0OiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0dGhpcy5fJGVsZW1lbnQuY3NzKHtcblx0XHRcdFx0XHRcdHRvcDogLSh0aGlzLl8kZWxlbWVudFswXS5vZmZzZXRIZWlnaHQgKyB0aGlzLl8kZWxlbWVudC5maW5kKCcuYXJyb3cnKVswXS5vZmZzZXRIZWlnaHQpXG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdGdldFNoYXBlOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0bGV0IHBvc2l0aW9uID0gdGhpcy5fJGVsZW1lbnQucG9zaXRpb24oKTtcblxuXHRcdFx0XHRcdHJldHVybiBuZXcgeW1hcHMuc2hhcGUuUmVjdGFuZ2xlKG5ldyB5bWFwcy5nZW9tZXRyeS5waXhlbC5SZWN0YW5nbGUoW1xuXHRcdFx0XHRcdFx0W3Bvc2l0aW9uLmxlZnQsIHBvc2l0aW9uLnRvcF0sIFtcblx0XHRcdFx0XHRcdFx0cG9zaXRpb24ubGVmdCArIHRoaXMuXyRlbGVtZW50WzBdLm9mZnNldFdpZHRoLFxuXHRcdFx0XHRcdFx0XHRwb3NpdGlvbi50b3AgLSB0aGlzLl8kZWxlbWVudFswXS5vZmZzZXRIZWlnaHRcblx0XHRcdFx0XHRcdF1cblx0XHRcdFx0XHRdKSk7XG5cdFx0XHRcdH0sXG5cdFx0XHR9XG5cdFx0KTtcblx0fVxuXG5cdGZ1bmN0aW9uIGdldENvbnRyb2xzKCkge1xuXG5cdFx0Y29uc3QgdHlwZVNlbGVjdG9yID0gbmV3IHltYXBzLmNvbnRyb2wuVHlwZVNlbGVjdG9yKHtcblx0XHRcdG9wdGlvbnM6IHtcblx0XHRcdFx0ZmxvYXQ6ICdub25lJyxcblx0XHRcdFx0cG9zaXRpb246IHtcblx0XHRcdFx0XHR0b3A6ICcxNXB4Jyxcblx0XHRcdFx0XHRsZWZ0OiAnMTJweCdcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0pO1xuXG5cdFx0Y29uc3Qgem9vbUNvbnRyb2wgPSBuZXcgeW1hcHMuY29udHJvbC5ab29tQ29udHJvbCh7XG5cdFx0XHRvcHRpb25zOiB7XG5cdFx0XHRcdGZsb2F0OiAnbm9uZScsXG5cdFx0XHRcdHBvc2l0aW9uOiB7XG5cdFx0XHRcdFx0dG9wOiAnNTdweCcsXG5cdFx0XHRcdFx0bGVmdDogJzEycHgnXG5cdFx0XHRcdH0sXG5cdFx0XHR9XG5cdFx0fSk7XG5cblx0XHRjb25zdCBnZW9sb2NhdGlvbkNvbnRyb2wgPSBuZXcgeW1hcHMuY29udHJvbC5HZW9sb2NhdGlvbkNvbnRyb2woe1xuXHRcdFx0b3B0aW9uczoge1xuXHRcdFx0XHRmbG9hdDogJ25vbmUnLFxuXHRcdFx0XHRwb3NpdGlvbjoge1xuXHRcdFx0XHRcdHRvcDogJzI3NXB4Jyxcblx0XHRcdFx0XHRsZWZ0OiAnMTJweCdcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0pO1xuXG5cdFx0Y29uc3QgcnVsZXJDb250cm9sID0gbmV3IHltYXBzLmNvbnRyb2wuUnVsZXJDb250cm9sKHtcblx0XHRcdG9wdGlvbnM6IHtcblx0XHRcdFx0ZmxvYXQ6ICdub25lJyxcblx0XHRcdFx0cG9zaXRpb246IHtcblx0XHRcdFx0XHR0b3A6ICczMTVweCcsXG5cdFx0XHRcdFx0bGVmdDogJzEycHgnXG5cdFx0XHRcdH0sXG5cdFx0XHRcdHNjYWxlTGluZTogZmFsc2UsXG5cdFx0XHR9XG5cdFx0fSk7XG5cblx0XHRyZXR1cm4gW2dlb2xvY2F0aW9uQ29udHJvbCwgdHlwZVNlbGVjdG9yLCBydWxlckNvbnRyb2wsIHpvb21Db250cm9sXTtcblx0fVxuXG59O1xuIl0sInNvdXJjZVJvb3QiOiIifQ==