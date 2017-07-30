create table user
( Username    VARCHAR(255)    NOT NULL check(Username <> ''),
  Password    VARCHAR(255)    NOT NULL check(Password <> ''),
  FullName    VARCHAR(255),
  PRIMARY KEY(Username)
);

create table system
( SYSTEM_ID    VARCHAR(255)    NOT NULL check(SYSTEM_ID <> ''),
  Description  VARCHAR(255)    NOT NULL check(Description <> ''),
  PRIMARY KEY(SYSTEM_ID)
);

create table user_system
( Username    VARCHAR(255)    NOT NULL,
  SYSTEM_ID   VARCHAR(255)    NOT NULL,
  PRIMARY KEY(Username, SYSTEM_ID),
  FOREIGN KEY(Username) references user(Username) on update cascade on delete cascade,
  FOREIGN KEY(SYSTEM_ID) references system(SYSTEM_ID) on update cascade on delete cascade
);

insert into system values ('homework', 'Homework assistant');
insert into system values ('users', 'User management');
insert into system values ('literature', 'Literature');
insert into system values ('portal', 'Portal');
insert into system values ('cms', 'CMS');
insert into system values ('cmsgallery', 'CMS gallery');
insert into user values ('admin', '$2y$10$fV4gLxkB9kgcz3MU5wBwd.Pqybdl.x17M0HM9MxgOp3w5BpH1dZpW' , 'Administrator'); -- password is: secret
insert into user_system values ('admin', 'homework');
insert into user_system values ('admin', 'users');
insert into user_system values ('admin', 'literature');
insert into user_system values ('admin', 'portal');
insert into user_system values ('admin', 'cms');
insert into user_system values ('admin', 'cmsgallery');
