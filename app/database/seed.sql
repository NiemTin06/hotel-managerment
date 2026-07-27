INSERT INTO User
( USER_USERNAME,USER_EMAIL, USER_PASSWORD, USER_PHONE, USER_ROLE,USER_STATUS)
VALUES
(
'admin',
'admin@gmail.com',
'$2y$10$gPySxFfne8mt5iPg0TgD3OVL79t9lsZn7UQaqvtOUV23FALGfkU8.',
'0987654321',
'Admin',
'Active'
);

INSERT INTO RoomType
(
ROOMTYPE_NAME,
ROOMTYPE_PRICE_PER_NIGHT,
ROOMTYPE_DISCOUNT_PERCENTAGE,
ROOMTYPE_DESCRIPTION,
ROOMTYPE_THUMBNAIL,
ROOMTYPE_MAX_GUESTS,
ROOMTYPE_BED_TYPE,
ROOMTYPE_STATUS,
ROOMTYPE_SLUG
)
VALUES
('Standard',30,0,'Standard Room','standard.jpg',2,'singleBed','Active','standard'),
('Superior',45,5,'Superior Room','superior.jpg',2,'doubleBed','Active','superior'),
('Deluxe',65,10,'Deluxe Room','deluxe.jpg',3,'queenBed','Active','deluxe'),
('Family',90,15,'Family Room','family.jpg',4,'kingBed','Active','family'),
('VIP Suite',150,20,'Luxury Suite','vip.jpg',4,'kingBed','Active','vip-suite');

INSERT INTO Room
(
ROOM_NUMBER,
ROOM_ROOMTYPE_ID,
ROOM_DESCRIPTION,
ROOM_STATUS,
ROOM_SLUG
)
VALUES
('101',1,'Floor 1','Available','room-101'),
('102',1,'Floor 1','Available','room-102'),
('103',1,'Floor 1','Occupied','room-103'),

('201',2,'Floor 2','Booked','room-201'),
('202',2,'Floor 2','Available','room-202'),
('203',2,'Floor 2','Maintenance','room-203'),

('301',3,'Floor 3','Available','room-301'),
('302',3,'Floor 3','Booked','room-302'),
('303',3,'Floor 3','Available','room-303'),

('401',4,'Floor 4','Occupied','room-401'),
('402',4,'Floor 4','Available','room-402'),
('403',4,'Floor 4','Available','room-403'),

('501',5,'VIP Floor','Available','room-501'),
('502',5,'VIP Floor','Booked','room-502'),
('503',5,'VIP Floor','Maintenance','room-503');

