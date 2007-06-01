create table screenshots (
  id unsigned int auto_increment not null,
  url varchar(256),
  description text,
  date timestamp default CURRENT_TIMESTAMP,
  
  primary key(id)
)