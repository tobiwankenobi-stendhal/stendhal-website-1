create table screenshots (
  id int auto_increment not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  approved boolean default false,
  
  primary key(id)
);

insert into screenshots values(null, "screenshots/screenshot.jpg", "Test image", null, null);

create table movies (
  id int auto_increment not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  approved boolean default false,
  
  primary key(id)
);


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

insert into events values(null, "2008/06/01 18:50", "raid", "Ados", "Website reading from database", "No extended description", null);
insert into events values(null, "2008/06/01 21:50", "raid", "Semos", "Goblins!", "No extended description", null);

create table event_images (
  id int auto_increment not null,
  event_id int not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id, event_id)
);

create table news (
  id int auto_increment not null,
  title varchar(256),
  shortDescription varchar(256),
  extendedDescription text,
  
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id)
);

insert into news values(null, "Edit this with your news title", "one line description one line description one line description one line description ", 
"An html formatted extended description can go here.", null);

create table news_images (
  id int auto_increment not null,
  news_id int not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id, news_id)
);
