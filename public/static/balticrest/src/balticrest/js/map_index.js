
const initializeMap = function() {

	let activePoint;

	ymaps.ready(init);

	function init() {

		const map = new ymaps.Map("map", {
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
			let point = new ymaps.Placemark([item.lat, item.lon], {
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
					let position = this._$element.position();

					return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
						[position.left, position.top - 30],
						[
							position.left + this._$element[0].offsetWidth,
							position.top + this._$element[0].offsetHeight
						]
					]));
				},
			}
		);
	}

	function getControls() {

		const typeSelector = new ymaps.control.TypeSelector({
			options: {
				float: 'none',
				position: {
					top: '15px',
					left: '12px'
				}
			}
		});

		const zoomControl = new ymaps.control.ZoomControl({
			options: {
				float: 'none',
				position: {
					top: '57px',
					left: '12px'
				},
			}
		});

		const geolocationControl = new ymaps.control.GeolocationControl({
			options: {
				float: 'none',
				position: {
					top: '275px',
					left: '12px'
				}
			}
		});

		const rulerControl = new ymaps.control.RulerControl({
			options: {
				float: 'none',
				position: {
					top: '315px',
					left: '12px'
				},
				scaleLine: false,
			}
		});

		return [geolocationControl, typeSelector, rulerControl, zoomControl];
	}

};
