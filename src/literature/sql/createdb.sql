create table author
( AUTHOR_ID    INTEGER       NOT NULL,
  FirstName    VARCHAR(255),
  LastName     VARCHAR(255)  NOT NULL check(LastName <> ''),
  Homepage     VARCHAR(255),
  PRIMARY KEY(AUTHOR_ID)
);

create table publisher
( PUBLISHER_ID    VARCHAR(255)    NOT NULL check(PUBLISHER_ID <> ''),
  Name            VARCHAR(255)    NOT NULL check(Name <> ''),
  PRIMARY KEY(PUBLISHER_ID)
);

create table conferences
( CONFERENCE_ID    INTEGER       NOT NULL,
  Name             VARCHAR(255)  NOT NULL check(Name <> ''),
  Homepage         VARCHAR(255),
  PUBLISHER_ID     VARCHAR(255)  NOT NULL,
  Location         VARCHAR(255)  NOT NULL,
  PRIMARY KEY(CONFERENCE_ID),
  FOREIGN KEY(PUBLISHER_ID) references publisher(PUBLISHER_ID) on update cascade on delete restrict
);

create table conferences_authors
( CONFERENCE_ID    INTEGER  NOT NULL,
  AUTHOR_ID        INTEGER  NOT NULL,
  PRIMARY KEY(CONFERENCE_ID, AUTHOR_ID),
  FOREIGN KEY(CONFERENCE_ID) references conferences(CONFERENCE_ID) on update cascade on delete restrict,
  FOREIGN KEY(AUTHOR_ID) references author(AUTHOR_ID) on update cascade on delete restrict
);

create table paper
( PAPER_ID       INTEGER       NOT NULL,
  CONFERENCE_ID  INTEGER       NOT NULL,
  Title          VARCHAR(255)  NOT NULL check(Title <> ''),
  Date           DATE          NOT NULL,
  Abstract       TEXT,
  URL            VARCHAR(255),
  Comment        VARCHAR(255),
  hasPDF         TINYINT       NOT NULL,
  PRIMARY KEY(PAPER_ID, CONFERENCE_ID),
  FOREIGN KEY(CONFERENCE_ID) references conferences(CONFERENCE_ID) on update cascade on delete restrict
);

create table paper_author
( PAPER_ID       INTEGER    NOT NULL,
  CONFERENCE_ID  INTEGER    NOT NULL,
  AUTHOR_ID      INTEGER    NOT NULL,
  PRIMARY KEY(PAPER_ID, CONFERENCE_ID, AUTHOR_ID),
  FOREIGN KEY(PAPER_ID, CONFERENCE_ID) references paper(PAPER_ID, CONFERENCE_ID) on update cascade on delete cascade,
  FOREIGN KEY(AUTHOR_ID) references author(AUTHOR_ID) on update cascade on delete restrict
);

insert into author values (1, 'Sander', 'van der Burg', 'http://sandervanderburg.nl');
insert into author values (2, 'Eelco', 'Dolstra', 'http://nixos.org/~eelco');
insert into author values (3, 'Eelco', 'Visser', 'http://eelcovisser.org');
insert into author values (4, 'Merijn', 'de Jonge', 'http://jongem.home.xs4all.nl');

insert into publisher values ('acm', 'ACM');
insert into publisher values ('ieee-cs', 'IEEE Computer Society');

insert into conferences values (1, 'First ACM Workshop on Hot Topics in Software Upgrades (HotSWUp)', 'http://www.hotswup.org/2008', 'acm', 'Nashville, Tennessee, USA');
insert into paper values (1, 1, 'Atomic Upgrading of Distributed Systems', '2008-10-01', 'Upgrading distributed systems is a complex process. It requires installing the right services on the right computer, configuring them correctly, and so on, which is error-prone and tedious. Moreover, since services in a distributed system depend on each other and are updated separately, upgrades typically are not atomic: there is a time window during which some but not all services are updated, and a new version of one service might temporarily talk to an old version of another service. Previously we implemented the Nix package management system, which allows atomic upgrades and rollbacks on single computers. In this paper we show an extension to Nix that enables the deployment of distributed systems on the basis of a declarative deployment model, and supports atomic upgrades of such systems.', NULL, NULL, 0);
insert into paper_author values (1, 1, 1);
insert into paper_author values (1, 1, 2);
insert into paper_author values (1, 1, 4);

insert into conferences values (2, 'First ICSE 2009 Workshop on Software Engineering Challenges in Cloud Computing', 'http://www.icse-cloud09.org', 'ieee-cs', 'Vancouver, British Columbia, Canada');
insert into paper values (1, 2, 'Software Deployment in a Dynamic Cloud: From Device to Service Orientation in a Hospital Environment', '2009-05-01', 'Upgrading distributed systems is a complex process. It requires installing the right services on the right computer, configuring them correctly, and so on, which is error-prone and tedious. Moreover, since services in a distributed system depend on each other and are updated separately, upgrades typically are not atomic: there is a time window during which some but not all services are updated, and a new version of one service might temporarily talk to an old version of another service. Previously we implemented the Nix package management system, which allows atomic upgrades and rollbacks on single computers. In this paper we show an extension to Nix that enables the deployment of distributed systems on the basis of a declarative deployment model, and supports atomic upgrades of such systems.', NULL, NULL, 0);
insert into paper_author values (1, 2, 1);
insert into paper_author values (1, 2, 2);
insert into paper_author values (1, 2, 3);
insert into paper_author values (1, 2, 4);

insert into conferences values (3, '36th EUROMICRO Conference on Software Engineering and Advanced Applications (SEAA)', 'http://seaa2010.liacs.nl', 'ieee-cs', 'Lille, France');
insert into paper values (1, 3, 'Automated Deployment of a Heterogeneous Service-Oriented System', '2010-09-01', 'Deployment of a service-oriented system in a network of machines is often complex and labourious. In many cases components implementing a service have to be built from source code for the right target platform, transferred to the right machines with the right capabilities and activated in the right order. Upgrading a running system is even more difficult as this may break the running system and cannot be performed atomically. Many approaches that deal with the complexity of a distributed deployment process only support certain types of components or specific environments, while general solutions lack certain desirable non-functional properties, such as atomic upgrading.This paper shows Disnix, a deployment tool which allows developers and administrators to reliably deploy, upgrade and roll back a service-oriented system consisting of various types of components in a heterogeneous environment from declarative specifications.', NULL, NULL, 0);
insert into paper_author values (1, 3, 1);
insert into paper_author values (1, 3, 2);
