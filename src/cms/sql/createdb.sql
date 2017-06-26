create table albums
(
	ALBUM_ID           VARCHAR(255)  NOT NULL check(ALBUM_ID <> ''),
	Title              VARCHAR(255)  NOT NULL check(Title <> ''),
	Visible            TINYINT       NOT NULL,
	Description        VARCHAR(255),
	Ordering           INTEGER       NOT NULL,
	PRIMARY KEY(ALBUM_ID)
);

create index album_ordering on albums(Ordering);

create table pictures
(
	PICTURE_ID    VARCHAR(255)  NOT NULL check(PICTURE_ID <> ''),
	Title         VARCHAR(255)  NOT NULL check(Title <> ''),
	Description   VARCHAR(255),
	FileType      VARCHAR(3)    check(FileType in ('gif', 'jpg', 'png')),
	ALBUM_ID      VARCHAR(255)  NOT NULL,
	Ordering      INTEGER       NOT NULL,
	PRIMARY KEY(PICTURE_ID, ALBUM_ID),
	FOREIGN KEY(ALBUM_ID) references albums(ALBUM_ID) on update cascade on delete restrict
);

create index pictures_ordering on pictures(Ordering);

create table thumbnails
(
	ALBUM_ID    VARCHAR(255) NOT NULL,
	PICTURE_ID  VARCHAR(255),
	PRIMARY KEY(ALBUM_ID),
	FOREIGN KEY(ALBUM_ID) references albums(ALBUM_ID) on update cascade on delete cascade,
	FOREIGN KEY(PICTURE_ID) references pictures(PICTURE_ID) on update cascade on delete set null
);

create table page
( PAGE_ID    VARCHAR(255)    NOT NULL,
  Title      VARCHAR(255)    NOT NULL check(Title <> ''),
  Contents   TEXT,
  Ordering   INTEGER         NOT NULL,
  PARENT_ID  VARCHAR(255),
  -- FOREIGN KEY(PARENT_ID) references page(PAGE_ID) on update cascade on delete restrict,
  PRIMARY KEY(PAGE_ID)
);

create index page_parent on page(PARENT_ID);

create index page_ordering on page(Ordering);

insert into page values ('', 'Home', '<p>This is an example application allowing end users to manage the page structure and the page contents.</p><p>To gain write access, open the <a href="index.php/auth">login</a> page.</p>', 1, NULL);
insert into page values ('page1', 'Page 1', '<p>This is page 1</p>', 2, '');
insert into page values ('page1/page11', 'Page 1.1', '<p>This is sub page 1.1</p>', 3, 'page1');
insert into page values ('page1/page12', 'Page 1.2', '<p>This is sub page 1.2</p>', 4, 'page1');
insert into page values ('page2', 'Page 2', '<p>This is page 2</p>', 5, '');
insert into page values ('page2/page21', 'Page 2.1', '<p>This is sub page 2.1</p>', 6, 'page2');
insert into page values ('page2/page22', 'Page 2.2', '<p>This is sub page 2.2</p>', 7, 'page2');
insert into page values ('page3', 'Page 3', '<p>This is page 3</p>', 8, '');
insert into page values ('page3/page31', 'Page 3.1', '<p>This is sub page 3.1</p>', 9, 'page3');
insert into page values ('page3/page32', 'Page 3.2', '<p>This is sub page 3.2</p>', 10, 'page3');
