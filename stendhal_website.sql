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

create table news (
  id int auto_increment not null,
  title varchar(256),
  shortDescription varchar(256),
  extendedDescription text,
  
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id)
);

insert into news values(null, "Stendhal BETA Testers server", "one line description one line description one line description one line description ", 
"An important milestone for 0.70<br/>
Welcome to this beta server.
<p/>
Please if you find any problem report it at <a href=\"http://sourceforge.net/tracker/?func=add&group_id=1111&atid=101111\">http://sourceforge.net/tracker/?func=add&group_id=1111&atid=101111</a>.
<p/>
We are interested in testing:<ul>
<li>Maps</li>
<li>Monster</li>
<li>New items</li>
<li>Quests</li>
</ul>
Please if you have any suggestion, post it at <a href=\"http://sourceforge.net/tracker/?func=add&group_id=1111&atid=351111\">http://sourceforge.net/tracker/?func=add&group_id=1111&atid=351111</a>", null);

create table news_images (
  id int auto_increment not null,
  news_id int not null,
  url varchar(256),
  description text,
  created timestamp default CURRENT_TIMESTAMP,
  
  primary key(id, news_id)
);



/*************************************************************************************************************
**************************************************************************************************************
**************************************************************************************************************
*********************** Until Marauroa 2.0 and Stendhal using it are released, use this **********************
**************************************************************************************************************
**************************************************************************************************************
**************************************************************************************************************/
CREATE TABLE `character_stats` (
  `name` varchar(32) NOT NULL,
  `sentence` varchar(256) default NULL,
  `age` int(11) default NULL,
  `level` int(11) default NULL,
  `outfit` varchar(32) default NULL,
  `xp` int(11) default NULL,
  `money` int(11) default NULL,
  `married` varchar(32) default NULL,
  `atk` int(11) default NULL,
  `def` int(11) default NULL,
  `hp` int(11) default NULL,
  `karma` int(11) default NULL,
  `head` varchar(32) default NULL,
  `armor` varchar(32) default NULL,
  `lhand` varchar(32) default NULL,
  `rhand` varchar(32) default NULL,
  `legs` varchar(32) default NULL,
  `feet` varchar(32) default NULL,
  `cloak` varchar(32) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `character_stats` */

insert into `character_stats` (`name`,`sentence`,`age`,`level`,`outfit`,`xp`,`money`,`married`,`atk`,`def`,`hp`,`karma`,`head`,`armor`,`lhand`,`rhand`,`legs`,`feet`,`cloak`) values ('miguel','',37,216,'1012701',100000035,101,NULL,226,226,2260,0,'null','black_armor','null','club','null','null','null');

