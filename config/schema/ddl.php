
create switchcodes_user(
	
	id int(10) auto_increment,
	username varchar(200),
	password varchar(200),
	primary key(id)

)engine=innodb;

create switchcodes_category(

	id int(10) auto_increment,
	name varchar(200),
	primary key(id)

)engine=innodb;

create table switchcodes_article(
	id int(10) auto_increment,
	category_id int(10),
	title varchar(200),
	content text,
	primary key(id),
	
	foreign key(category_id) references switchcodes_category(id) on update cascade on delete cascade

)engine=innodb;
