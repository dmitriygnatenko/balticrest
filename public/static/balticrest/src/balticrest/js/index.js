
function setIndexHeight() {
	"use strict";
	$('#promo_head').css({
		height: $(window).height() + 'px'
	});
}

function initializeMap() {

	var activePoint;

	ymaps.ready(init);

	function init() {

		var map = new ymaps.Map("map", {
			center: [map_data.center.lat, map_data.center.lon],
			zoom: map_data.zoom,
			controls: []
		});

		map.behaviors.disable(['scrollZoom']);

		map.events.add('click', function (e) {
			if (activePoint !== undefined) {
				activePoint.onCloseClick(e);
			}
		});

		map_data.points.forEach(function (item) {
			var point = new ymaps.Placemark([item.lat, item.lon], {
				hintContent: item.hint,
			}, {
				balloonShadow: false,
				balloonPanelMaxMapArea: 0,
				balloonContentLayout: getBalloonContent(item),
				balloonLayout: getBalloonLayout(),
				iconLayout: 'default#image',
				iconImageHref: item.icon.image,
				iconImageSize: [item.icon.size.width, item.icon.size.height],
			});

			map.geoObjects.add(point);

			// Image preload
			preloadImage(item.image);
		});
	}

	function preloadImage(item) {
		$('<img>').attr('src', item);
	}

	function getBalloonContent(item) {
		if (item.link !== '') {
			return ymaps.templateLayoutFactory.createClass(
				'<img src="' + item.image + '" class="logotype">' +
				'<h3>'+ item.title +'<span></span></h3>' +
				'<span>'+ item.description +'</span>' +
				'<a href="'+ item.link + '" class="green_btn">' + map_data.trans.point_button + '</a>'
			);

		} else {
			return ymaps.templateLayoutFactory.createClass(
				'<img src="' + item.image + '" class="logotype">' +
				'<h3>'+ item.title +'</h3>' +
				'<span>'+ item.description +'</span>'
			);
		}
	}

	function getBalloonLayout() {
		return ymaps.templateLayoutFactory.createClass(
			'<div class="marker_info">' +
			'<div class="info">'+
			'<button type="button" class="close"><span>&times;</span></button>' +
			'$[[options.contentLayout]]' +
			'<span class="arrow"></span>' +
			'</div>' +
			'</div>',
			{
				build: function () {
					activePoint = this;
					this.constructor.superclass.build.call(this);
					this._$element = $('.marker_info .info', this.getParentElement());
					this.applyElementOffset();
					this._$element.find('.close').on('click', $.proxy(this.onCloseClick, this));
				},
				onCloseClick: function (e) {
					activePoint = undefined;
					e.preventDefault();
					this.events.fire('userclose');
				},
				applyElementOffset: function () {
					this._$element.css({
						top: -(this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight)
					});
				},
				getShape: function () {
					var position = this._$element.position();

					return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
						[position.left, position.top - 110],
						[
							position.left + this._$element[0].offsetWidth,
							position.top + this._$element[0].offsetHeight
						]
					]));
				},
			}
		);
	}
}

$(function() {

	$('.tp-banner').show().revolution(
		{
			dottedOverlay:"none",
			delay:16000,
			startwidth:1170,
			startheight:700,
			hideThumbs:200,
			thumbWidth:100,
			thumbHeight:50,
			thumbAmount:5,
			navigationType:"bullet",
			navigationArrows:"solo",
			navigationStyle:"preview4",
			touchenabled:"on",
			onHoverStop:"on",
			swipe_velocity: 0.7,
			swipe_min_touches: 1,
			swipe_max_touches: 1,
			drag_block_vertical: false,
			parallax:"scroll",
			parallaxBgFreeze:"on",
			parallaxLevels:[10,7,4,3,2,5,4,3,2,1],
			keyboardNavigation:"off",
			navigationHAlign:"center",
			navigationVAlign:"bottom",
			navigationHOffset:0,
			navigationVOffset:20,
			soloArrowLeftHalign:"left",
			soloArrowLeftValign:"center",
			soloArrowLeftHOffset:20,
			soloArrowLeftVOffset:0,
			soloArrowRightHalign:"right",
			soloArrowRightValign:"center",
			soloArrowRightHOffset:20,
			soloArrowRightVOffset:0,
			shadow:0,
			fullWidth:"on",
			fullScreen:"off",
			spinner:"spinner4",
			stopLoop:"off",
			stopAfterLoops:-1,
			stopAtSlide:-1,
			shuffle:"off",
			autoHeight:"off",
			forceFullWidth:"off",
			hideThumbsOnMobile:"off",
			hideNavDelayOnMobile:1500,
			hideBulletsOnMobile:"off",
			hideArrowsOnMobile:"off",
			hideThumbsUnderResolution:0,
			hideSliderAtLimit:0,
			hideCaptionAtLimit:0,
			hideAllCaptionAtLilmit:0,
			startWithSlide:0,
			videoJsPath:"rs-plugin/videojs/",
			fullScreenOffsetContainer: ""
		}
	);
});

$('.category_block').parallax({

	'elements': [
		{
			'selector': '.category_block',
			'properties': {
				'x': {
					'background-position-x': {
						'initial': 0,
						'multiplier': 0.1,
						'invert': true
					}
				},
				'y': {
					'background-position-y': {
						'initial': -780,
						'multiplier': 0.1,
						'invert': true
					}
				}
			}
		}
	]
});
