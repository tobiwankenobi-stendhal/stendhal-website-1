create table screenshots (
  id int auto_increment not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  approved boolean default false,
  
  primary key(id)
);

insert into screenshots values(null, "screenshots/screenshot.jpg", "Test image", null, null);


create table events (
  id int auto_increment not null,
  date datetime not null,
  type varchar(32),
  location varchar(32),
  shortDescription varchar(256),
  extendedDescription text,
  
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id)
);

insert into events values(null, "2007/06/01 18:50", "raid", "miguel's house", "Website reading from database", "No extended description", null);
insert into events values(null, "2007/06/01 21:50", "raid", "Semos", "Goblins!", "No extended description", null);

create table event_images (
  id int auto_increment not null,
  event_id int not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id, event_id)
);