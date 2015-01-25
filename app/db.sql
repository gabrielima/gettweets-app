CREATE TABLE passwords (
  id int(11) NOT NULL,
  password_login varchar(256) NOT NULL,
  password_pass varchar(256) NOT NULL,
  password_website varchar(256) NOT NULL
);

CREATE TABLE tweets (
  id int(11) NOT NULL,
  tweet_text varchar(256) NOT NULL,
  tweet_date datetime NOT NULL,
  tweet_twitterId varchar(60)NOT NULL
)  ;

CREATE TABLE user (
  id int(11) NOT NULL,
  first_name varchar(256)  NOT NULL,
  middle_name varchar(256)  NOT NULL,
  last_name varchar(256) NOT NULL,
  email varchar(256) NOT NULL,
  password varchar(80) NOT NULL,
  twitter_account varchar(256) NOT NULL,
  acode int(11) NOT NULL,
  activated tinyint(1) NOT NULL
)  ;

CREATE TABLE user_passwords (
  password_id int(11) NOT NULL,
  user_id int(11) NOT NULL
) ;

CREATE TABLE user_tweets (
  user_id int(11) NOT NULL,
  tweet_id int(11) NOT NULL
) ;


ALTER TABLE passwords ADD PRIMARY KEY (id);
ALTER TABLE tweets ADD PRIMARY KEY (id), ADD UNIQUE KEY tweet_twitterId (tweet_twitterId);
ALTER TABLE user ADD PRIMARY KEY (id);

ALTER TABLE tweets MODIFY id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE user MODIFY id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE passwords MODIFY id int(11) NOT NULL AUTO_INCREMENT;