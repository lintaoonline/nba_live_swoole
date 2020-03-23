//球队表
create table `live_team`(
    `id` tinyint(1) unsigned not null auto_increment,
    `name` varchar(20) not null default '',
    `image` varchar(20) not null default '',
    `type` tinyint(1) unsigned not null default 0 comment '球队分区，0西部 1东部',
    `create_time` int(10) unsigned not null default 0,
    `update_time` int(10) unsigned not null default 0,
    primary key (`id`)
)engine=InnoDB auto_increment = 1 default charset=utf8;