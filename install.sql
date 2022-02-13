-- create database hoothin;
-- 123456  pass
use 1f1p;
/*用户列表*/
create table user
(
id int NOT NULL auto_increment,
wxid varchar(28),							/*微信ID*/
nickName varchar(10),						/*昵称*/
birthday date,								/*年龄*/
sex int,									/*性别 1男2女 奇数男 偶数女*/
phone varchar(11),							/*手机号*/
regTime datetime,							/*注册日期*/
realName varchar(10),						/*真实姓名*/
idCard varchar(18),							/*身份证*/
lastLoginTime datetime,						/*上次访问时间*/
cardID varchar(100),						/*卡ID 113121,21313,13,1314,131315*/
state int,									/*状态 0 未验证 1 已验证 2 禁止登录*/
isBan int,									/*封禁状态 0 未封禁 1 已封禁*/
banEndTime datetime,						/*封禁结束时间*/
orderTimes int,
avatar varchar(200),
primary key(id)
);
/*渠道*/
create table channel
(
id int NOT NULL auto_increment,
contact varchar(10),
phone varchar(20),
payType int,								/*支付方式 0 月结 1 预付*/
primary key(id)
);
/*代理*/
create table agent
(
id int NOT NULL auto_increment,
agentId int,
cardStart varchar(10),						/*可售卡号段 开始号码*/
cardEnd varchar(10),						/*可售卡号段 结尾号码*/
cardPrice int,								/*代理价格 230*/
primary key(id)
);
/*后台用户*/
create table backUser
(
id int NOT NULL auto_increment,
role int,									/*用户角色*/
targetId int,								/*对应ID*/
nickName varchar(10),						/*昵称*/
account varchar(10),						/*账户*/
pass varchar(10),							/*密码*/
contact varchar(10),
phone varchar(20),
primary key(id)
);
/*后台角色*/
create table backRole
(
role int NOT NULL unique,					/*角色 0 管理员 1 渠道 2 代理 3 酒店前台 4 客服*/
roleDesc varchar(10),						/*角色描述*/
rolePower TEXT								/*可以访问哪些页面，默认全部可以访问 0,1,1,1,1*/
);
/*账单记录*/
create table billLog
(
id int NOT NULL auto_increment,
billNum int,								/*账单金额*/
billType varchar(10),						/*账单类型*/
billDesc varchar(50),						/*账单描述*/
channelId int,
addTime datetime,
primary key(id)
);
/*卡列表*/
create table cards
(
cardID varchar(20),   						/*卡ID*/
cardPass varchar(20),						/*卡密*/
type int,									/*卡类型*/
state int(1),								/*卡状态 0 未激活 1 已激活 2已失效*/
activeTime datetime,						/*激活时间*/
userid int,									/*绑定用户id*/
primary key(cardID)
);
/*卡类型表*/
create table cardType
(
type int NOT NULL auto_increment,			/*卡类型*/
cardName varchar(6),						/*卡名称*/
validDays int,								/*激活后有效期日数*/
orderCD int,								/*预定冷却时间 小时*/
useTimes int,   							/*可使用次数*/
cardHead varchar(10),
primary key(type)
);
/*预约列表*/
create table orders
(
id int NOT NULL auto_increment,
userid int,									/*会员ID*/
usedCard varchar(20),						/*使用的卡片*/
orderHotel int,								/*预约酒店id*/
orderRoom int,								/*预约房型id*/
startTime date, 	        				/*预约入住时间*/
endTime date,         						/*预约离店时间*/
state varchar(1),   						/*状态 0 已预约 1 已确认 2 已取消 3 已入住 4 超时未入住*/
primary key(id)
);
/*任务执行列表*/
create table tasks
(
id int NOT NULL auto_increment,
type int,									/*任务类型 taskList-id*/
userid int,									/*用户id*/
description varchar(100),					/*任务完成备注*/
state varchar(1),   						/*状态 0 未执行 1 已执行*/
primary key(id)
);
create table taskList
(
id int NOT NULL auto_increment,
taskName varchar(10),						/*任务类型*/
taskNum int,
taskDone int,
addTime datetime,
primary key(id)
);
/*酒店列表*/
create table hotel
(
id int NOT NULL auto_increment,
name varchar(20),							/*酒店名称*/
starLv int,									/*酒店星级*/
description text,							/*酒店描述*/
pics varchar(100),							/*酒店展示图*/
pos varchar(30),							/*酒店位置*/
gps varchar(30),							/*酒店经纬信息*/
province int,								/*省份*/
city int,									/*城市*/
county int,									/*区县*/
phone varchar(13),							/*联系电话*/
addTime datetime,							/*添加时间*/
source int DEFAULT 0,						/*来源渠道*/
recom int(1),								/*推荐状态 0 未推荐 1~9 推荐*/
state varchar(1) DEFAULT 0, 	 	 		/*状态 0 不显示 1 显示*/
primary key(id)     	
);
/*房间列表*/
create table rooms
(
id int NOT NULL auto_increment,
type varchar(10),							/*房型名称*/
description TEXT,							/*房间介绍*/
pics varchar(100),							/*展示图*/
price int,									/*房价*/
channelPrice int,							/*渠道价*/
num varchar(50),							/*剩余房数*/
hotel int,									/*关联酒店*/
addtime datetime,							/*添加时间*/
enableCard varchar(10),						/*可以使用的卡 1,2*/
space int,									/*面积*/
bed varchar(50),							/*床型 0,1,2,2*/
people int,									/*限住人数*/
prop varchar(100),							/*房间属性 kt,yg,bycj,zxb,sz,cz*/
cancelTime int,								/*超过多少小时不能取消预定*/
state int,									/*0未开放 1已开放*/
primary key(id)
);
/*系统设置表*/
create table sysConfig
(
sysTitle varchar(10),						/*系统名称*/
bannerPics TEXT,							/*头部轮播图片*/
hotCity varchar(100),						/*热门城市1,2,3,4*/
newCityNum int,								/*上新酒店数量*/
recomCityNum int,							/*推荐酒店数量*/
maxOrderDay int,							/*最久可预约多少天后*/
seriesOrderDay int,							/*最大连续预约天数*/
rule TEXT,									/*使用规则*/
service TEXT,								/*客服中心*/
aboutUs TEXT,
close varchar(20),							/*暂时关闭系统提示*/
post TEXT,									/*公告*/
newHotel varchar(100),
primary key(sysTitle)
);
-- insert into sysConfig values('4', '2');