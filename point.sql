/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B7A5F324F47645AE` (`url`),
  KEY `IDX_B7A5F3248BAC62AF` (`city_id`),
  KEY `IDX_B7A5F324C54C8C93` (`type_id`),
  KEY `IDX_B7A5F3241B5771DD` (`is_active`),
  CONSTRAINT `FK_B7A5F3248BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  CONSTRAINT `FK_B7A5F324C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `point_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `point` DISABLE KEYS */;
REPLACE INTO `point` (`id`, `city_id`, `type_id`, `lat`, `lon`, `url`, `logo`, `data`, `is_active`) VALUES
	(1, 2, 1, 54.9430281174, 20.1392688531, 'hotel-gotland', '/static/balticrest/images/logo/hotels/villa_gotland.png', '{"email": "hotelgotland@gmail.com", "phones": "+7 (4012) 555195", "comment": "", "website": "http://hotelgotland.ru", "services": ["parking", "safe", "library", "wi-fi", "sauna"], "detailed-type": ""}', 1),
	(2, 2, 1, 54.9407518335, 20.1373279095, 'hotel-lazur', '/static/balticrest/images/logo/hotels/lazur.png', '{"email": "lazur-bron@mail.ru", "phones": "+7 (40153) 21595", "comment": "", "website": "http://www.hotel-lazur.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "bar", "car-rental", "safe", "laundry", "wi-fi", "transfer"], "detailed-type": ""}', 1),
	(3, 2, 1, 54.941398932, 20.1578682661, 'alterdoktor', '/static/balticrest/images/logo/hotels/alterdoctor.png', '{"email": "reception@alterdoktor.ru", "phones": "+7 (40153) 21362", "comment": "", "website": "http://www.alterdoktor.ru", "services": ["parking", "restaurant", "bar", "safe", "wi-fi", "transfer"], "detailed-type": ""}', 1),
	(4, 2, 1, 54.9395623777, 20.1355844736, 'iz-doma-v-dom', '/static/balticrest/images/logo/hotels/izdomavdom.png', '{"email": "info@izdoma-vdom.ru", "phones": "+79062371640", "comment": "<p>АКЦИЯ 09,11,15 Два ящика watson-vvs@rambler.ru &nbsp;и еще один Точка на карте неточная. нет номера дома.</p>", "website": "http://www.izdoma-vdom.ru", "services": ["parking", "laundry", "wi-fi", "transfer", "sauna"], "detailed-type": ""}', 1),
	(5, 2, 1, 54.9406100867, 20.1358741522, 'my-green-house', '/static/balticrest/images/logo/hotels/greenhouse2.png', '{"email": "larisa@mygreenhouse.ru", "phones": "+79211009010", "comment": "", "website": "http://www.mygreenhouse.ru", "services": [], "detailed-type": ""}', 1),
	(6, 2, 1, 54.9465137249, 20.1655179262, 'russ', '/static/balticrest/images/logo/hotels/russ.png', '{"email": "info@russ-hotel.ru", "phones": "+7 (4012) 777787", "comment": "", "website": "http://www.russ-hotel.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "bar", "car-rental", "safe", "library", "wi-fi", "transfer", "children-playground"], "detailed-type": ""}', 1),
	(7, 2, 1, 54.9424281053, 20.1531046629, 'gk-sosny', '/static/balticrest/images/logo/hotels/sosny.png', '{"email": "tur@svetlogorsk-vvs.ru", "phones": "+7 (4012) 743742", "comment": "", "website": "http://svsosny.ru", "services": ["parking", "safe", "library", "wi-fi", "transfer"], "detailed-type": ""}', 1),
	(8, 2, 1, 54.94051148, 20.1460196078, 'hoffmann-house', '/static/balticrest/images/logo/hotels/hoffmannhouse.png', '{"email": "dom-skazochnika@mail.ru", "phones": "+7 (40153) 22396", "comment": "", "website": "http://hoffmann-house.ru", "services": ["parking", "restaurant", "library", "wi-fi"], "detailed-type": ""}', 1),
	(9, 2, 1, 54.9422894457, 20.1574042439, 'domik-na-sadovoy', '/static/balticrest/images/logo/hotels/domiknasadovoy.png', '{"email": "domiknasadovoy@gmail.com", "phones": "+79114704816", "comment": "", "website": "http://домикнасадовой.рф", "services": ["parking", "wi-fi", "transfer"], "detailed-type": ""}', 1),
	(10, 2, 1, 54.9418364879, 20.146061182, 'hotel-flamingo', '/static/balticrest/images/logo/hotels/flamingo.png', '{"email": "info@hotel-flamingo.ru", "phones": "+7 (4012) 743725", "comment": "<p>Не уверена с точкой на карте, так как на доме нет цифры. Будем в Светлогорске - проверим.</p>", "website": "http://www.hotel-flamingo.ru", "services": ["parking", "restaurant", "laundry", "wi-fi", "transfer", "sauna"], "detailed-type": ""}', 1),
	(11, 2, 1, 54.9427177481, 20.1460584998, 'golden-bay', '/static/balticrest/images/logo/hotels/goldenbay.png', '{"email": "goldbuhta@slavhotels.ru", "phones": "+7 (40153) 22351", "comment": "", "website": "", "services": ["parking"], "detailed-type": ""}', 1),
	(12, 2, 1, 54.9436513698, 20.1487675309, 'lumier', '/static/balticrest/images/logo/hotels/lumier.png', '{"email": "director@hotellumier.ru", "phones": "+7 (4012) 507750", "comment": "<p>ЭКСКУРСИИ. Директор - Эльвира петровна У них было кафе, кафе закрыли, но в нем проводят банкеты некий Владимир. 89097904599, vchaika_77@mail.ru никаких шоу он не проводит.</p>", "website": "http://www.hotellumier.ru", "services": ["parking", "conference-hall", "spa", "laundry", "wi-fi", "transfer", "sauna"], "detailed-type": ""}', 1),
	(13, 2, 1, 54.9427916991, 20.1472896338, 'hotel-universal', '/static/balticrest/images/logo/hotels/universal.png', '{"email": "hotel-universal@mail.ru", "phones": "+7 (4012) 743658", "comment": "<p>Как я поняла на карте не нарисован сам дом. и улица Некрасова прерывается.<br />\\r\\n&nbsp;</p>", "website": "http://hotel-universal.ru", "services": ["parking", "restaurant", "conference-hall", "bar", "safe", "laundry", "wi-fi", "transfer", "sauna"], "detailed-type": ""}', 1),
	(14, 2, 1, 54.9457804391, 20.1495145261, 'hotel-grand-palace', '/static/balticrest/images/logo/hotels/grandpalace.png', '{"email": "reception@grandhotel.ru", "phones": "+7 (40153) 33232", "comment": "", "website": "http://grandhotel.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "fitness", "bar", "indoor-pool", "outdoor-pool", "safe", "guide", "wi-fi", "transfer", "sauna", "children-playground"], "detailed-type": ""}', 1),
	(15, 2, 1, 54.9430043075, 20.16610533, 'falke-hotel', '/static/balticrest/images/logo/hotels/royalfalkeresort.png', '{"email": "info@falke-hotel.ru", "phones": "+7 (40153) 21600", "comment": "", "website": "http://www.falke-hotel.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "bar", "indoor-pool", "safe", "library", "laundry", "wi-fi", "transfer", "sauna", "children-playground"], "detailed-type": ""}', 1),
	(16, 2, 1, 54.9406532271, 20.1654066145, 'hotel-dream', '/static/balticrest/images/logo/hotels/hoteldream.png', '{"email": "palas39@mail.ru", "phones": "+7 (40153) 21350", "comment": "", "website": "http://hoteldream.ru", "services": ["parking", "conference-hall", "safe", "laundry", "wi-fi", "transfer", "children-playground"], "detailed-type": ""}', 1),
	(17, 2, 1, 54.944714378, 20.1458626986, 'dom-elena', '/static/balticrest/images/logo/hotels/elena.png', '{"email": "baza1514@yandex.ru", "phones": "+79114654469", "comment": "", "website": "http://www.domelena.ru", "services": ["parking", "car-rental", "transfer"], "detailed-type": ""}', 1),
	(18, 2, 6, 54.9413557924, 20.1389157772, 'sport-yantar', '/static/balticrest/images/logo/sport/yantar.png', '{"email": "san-yantar@mail.ru", "phones": "+7 (40153) 22659", "comment": "", "website": "http://www.s-yantar.ru", "services": [], "detailed-type": ""}', 1),
	(19, 2, 6, 54.9378551698, 20.1407665014, 'sport-olimp', '/static/balticrest/images/logo/sport/olimp.png', '{"email": "", "phones": "+78007000789", "comment": "", "website": "http://www.oliphotel.ru", "services": [], "detailed-type": ""}', 1),
	(20, 2, 6, 54.9471853783, 20.1643055677, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": ""}', 1),
	(21, 2, 6, 54.9393797262, 20.1492466713, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": ""}', 1),
	(22, 2, 6, 54.9439348414, 20.1503553987, 'sport-scvs', '/static/balticrest/images/logo/sport/scvs.png', '{"email": "scvs.su@gmail.com", "phones": "+7 (40153) 22007", "comment": "", "website": "http://www.scvs.su", "services": [], "detailed-type": ""}', 1),
	(23, 2, 6, 54.92590885, 20.1653730869, 'svetlogorsk-fok', '/static/balticrest/images/logo/sport/fok-svetl.png', '{"email": "fok39@list.ru", "phones": "8 (4015) 320808", "comment": "", "website": "http://svetlogorsk-fok.ru", "services": [], "detailed-type": ""}', 1),
	(24, 2, 6, 54.9439687346, 20.1610386372, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": ""}', 1),
	(25, 2, 2, 54.9396424976, 20.1417213678, 'volna', '/static/balticrest/images/logo/medicine/volna.png', '{"email": "reception@hotelvolna.ru", "phones": "+7 (40153) 22464", "comment": "", "website": "http://www.hotelvolna.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "bar", "library", "wi-fi", "transfer", "sauna", "children-playground"], "detailed-type": ""}', 1),
	(26, 2, 2, 54.9436975881, 20.1503366232, 'voyeniy-sanatoriy', '/static/balticrest/images/logo/medicine/scvs.png', '{"email": "svs.kom@mail.ru", "phones": "+7 (40153) 22007", "comment": "<p>Более точное расположение домов находится на карте http://maps-city.ru/map-svetlogorsk-20625.html (номер дома 22 А).</p>", "website": "http://www.scvs.su", "services": ["restaurant", "conference-hall", "fitness", "indoor-pool", "library", "children-playground"], "detailed-type": ""}', 1),
	(27, 2, 2, 54.9459468161, 20.165399909, 'pansionat-baltika', '/static/balticrest/images/logo/medicine/baltica.png', '{"email": "", "phones": "+7 (4012) 743696", "comment": "", "website": "http://pansionat-baltika.ru", "services": ["parking", "restaurant", "conference-hall", "spa", "fitness", "bar", "wi-fi", "transfer", "sauna", "children-playground"], "detailed-type": ""}', 1),
	(28, 2, 2, 54.9413927692, 20.1389801502, 's-yantar', '/static/balticrest/images/logo/medicine/yantar.png', '{"email": "san-yantar@mail.ru", "phones": "+7 (40153) 22659", "comment": "<p>Не уверена с точкой на карте.</p>", "website": "http://www.s-yantar.ru", "services": ["parking", "restaurant", "spa", "bar", "indoor-pool", "sauna", "children-playground"], "detailed-type": ""}', 1),
	(29, 2, 2, 54.9434202773, 20.1344901323, 'yantar-bereg', '/static/balticrest/images/logo/medicine/yantar-bereg.png', '{"email": "info@yantarbereg.ru", "phones": "+7 (40153) 21569", "comment": "", "website": "http://www.yantarbereg.ru", "services": ["parking", "restaurant", "conference-hall", "fitness", "indoor-pool", "library", "transfer", "children-playground"], "detailed-type": ""}', 1),
	(30, 2, 7, 54.9333865027, 20.1606363058, 'svetlogorsk1', '', '{"email": "", "phones": "+7 (40153) 21946", "comment": "", "website": "", "services": [], "detailed-type": ""}', 1),
	(31, 2, 7, 54.9441104695, 20.1470321417, 'svetlogorsk2', '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": ""}', 1),
	(32, 2, 7, 54.9394699315, 20.1477777958, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-parking"}', 1),
	(33, 2, 7, 54.9407518335, 20.131534338, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-parking"}', 1),
	(34, 2, 7, 54.9254896165, 20.1565539837, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-parking"}', 1),
	(35, 2, 7, 54.9469389013, 20.1649975777, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(36, 2, 7, 54.9382496214, 20.1593863964, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(37, 2, 7, 54.9386872116, 20.1575303078, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(38, 2, 7, 54.9391494496, 20.1471662521, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(39, 2, 7, 54.9409860227, 20.1398921013, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(40, 2, 7, 54.9257732161, 20.1522839069, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(41, 2, 7, 54.9431306396, 20.1581633091, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(42, 2, 7, 54.9273021537, 20.1586997509, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1),
	(43, 2, 7, 54.9282392157, 20.1581633091, NULL, '', '{"email": "", "phones": "", "comment": "", "website": "", "services": [], "detailed-type": "transport-free-parking"}', 1);
/*!40000 ALTER TABLE `point` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
