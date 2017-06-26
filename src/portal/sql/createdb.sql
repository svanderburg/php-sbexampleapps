create table newsmessages
(
	MESSAGE_ID    INTEGER      NOT NULL,
	Title         VARCHAR(255) NOT NULL check(Title <> ''),
	Date          DATE         NOT NULL,
	Message       TEXT,
	PRIMARY KEY(MESSAGE_ID)
);

create index newsmessages_ordering on newsmessages(MESSAGE_ID, Date);

create index newsmessages_date on newsmessages(Date);

create table changelogentries
(
	LOG_ID    VARCHAR(255)  NOT NULL,
	Date      DATE          NOT NULL,
	Summary   VARCHAR(255)  NOT NULL,
	PRIMARY KEY(LOG_ID)
);

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
